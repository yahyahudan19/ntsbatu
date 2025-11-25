<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Services\DuitkuService;

class CheckoutController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with('variants')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $dataProduct = [
            'id'          => $product->id,
            'slug'        => $product->slug,
            'name'        => $product->name,
            'description' => $product->description,
            'image'       => asset('images/products/' . $product->image),
            'packages'    => $product->variants->map(function ($variant) {
                return [
                    'id'    => $variant->id,
                    'label' => $variant->name,
                    'price' => $variant->price,
                ];
            })->toArray(),
        ];

        $productJson = json_encode($dataProduct);

        return view('checkout', [
            'product'     => $dataProduct,
            'productJson' => $productJson,
        ]);
    }

    public function store(Request $request, string $slug)
    {
        // 1. Validasi dasar
        $validated = $request->validate([
            'product_id'        => ['required', 'exists:products,id'],
            'delivery_date'     => ['required', 'date'],
            'customer_name'     => ['required', 'string', 'max:255'],
            'customer_whatsapp' => ['required', 'string', 'max:50'],
            'customer_address'  => ['required', 'string', 'max:500'],
            'payment_method'    => ['required', 'in:cod,qris'],
            'variants'          => ['required', 'array'],
            'variants.*.variant_id' => ['nullable', 'exists:product_variants,id'],
            'variants.*.qty'        => ['nullable', 'integer', 'min:0'],
        ]);

        // 2. Ambil produk berdasarkan slug untuk cross-check
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Pastikan product_id di form sama dengan slug
        if ((int) $validated['product_id'] !== $product->id) {
            return back()->withErrors(['product_id' => 'Produk tidak valid.'])->withInput();
        }

        // 3. Filter varian yang qty > 0
        $selectedVariants = collect($validated['variants'])
            ->filter(function ($row) {
                return !empty($row['variant_id']) && ($row['qty'] ?? 0) > 0;
            });

        if ($selectedVariants->isEmpty()) {
            return back()->withErrors(['variants' => 'Minimal pilih satu paket dengan jumlah > 0.'])->withInput();
        }

        // 4. Ambil data varian dari DB
        $variantIds = $selectedVariants->pluck('variant_id')->all();

        $variants = ProductVariant::with('product')
            ->whereIn('id', $variantIds)
            ->get()
            ->keyBy('id');

        // Pastikan semua varian milik produk yang sama
        foreach ($variants as $variant) {
            if ($variant->product_id !== $product->id) {
                return back()->withErrors(['variants' => 'Paket tidak sesuai dengan produk.'])->withInput();
            }
        }

        // 5. Hitung subtotal & siapkan item
        $subtotal = 0;
        $itemsData = [];

        foreach ($selectedVariants as $row) {
            $variant = $variants[$row['variant_id']];
            $qty     = (int) $row['qty'];
            $price   = (int) $variant->price;
            $sub     = $price * $qty;

            $subtotal += $sub;

            $itemsData[] = [
                'product_id'   => $variant->product_id,
                'variant_id'   => $variant->id,
                'product_name' => $product->name,
                'variant_name' => $variant->name,
                'unit_price'   => $price,
                'quantity'     => $qty,
                'subtotal'     => $sub,
            ];
        }

        if ($subtotal <= 0) {
            return back()->withErrors(['variants' => 'Subtotal tidak valid.'])->withInput();
        }

        // Ongkir & diskon (sementara 0)
        $deliveryFee = 0;
        $discount    = 0;
        $grandTotal  = $subtotal + $deliveryFee - $discount;

        $paymentMethod = $validated['payment_method'];

        // 6. Buat order_code
        $orderCode = 'ORD-' . now()->format('YmdHis') . '-' . rand(100, 999);

        // 7. Simpan ORDER
        $order = Order::create([
            'user_id'          => null, // nanti bisa isi Auth::id() kalau ada login
            'order_code'       => $orderCode,
            'customer_name'    => $validated['customer_name'],
            'customer_phone'   => $validated['customer_whatsapp'],
            'customer_address' => $validated['customer_address'],
            'city'             => 'Kota Batu',
            'area'             => null,
            'delivery_date'    => $validated['delivery_date'],
            'delivery_time_slot' => null,
            'notes'              => null,

            'subtotal'        => $subtotal,
            'delivery_fee'    => $deliveryFee,
            'discount_amount' => $discount,
            'grand_total'     => $grandTotal,

            'status'          => $paymentMethod === 'cod'
                                    ? 'processing'
                                    : 'pending_payment',

            // kalau di tabel orders sudah kamu tambah kolom payment_method:
            // 'payment_method'   => $paymentMethod,
        ]);

        // 8. Simpan ORDER ITEMS
        foreach ($itemsData as $item) {
            OrderItem::create(array_merge($item, [
                'order_id' => $order->id,
            ]));
        }

        // 9. Branching flow: COD vs QRIS/Payment Gateway
        if ($paymentMethod === 'cod') {
            // FLOW COD:
            // - Tidak buat record pembayaran dulu
            // - Tidak redirect ke Duitku
            // - Bisa kirim WA / email ke admin di sini (nanti)

            return redirect()
                ->route('checkout.show', $slug)
                ->with('success', 'Pesanan COD kamu sudah tercatat. Kami akan menghubungi via WhatsApp untuk konfirmasi.');
        } else {
            // FLOW QRIS (Duitku)
            try {
                /** @var \App\Services\DuitkuService $duitku */
                $duitku  = app(DuitkuService::class);
                $payment = $duitku->createPopInvoice($order);

                // Kalau ada payment_url dari Duitku, langsung redirect
                if ($payment->payment_url) {
                    return redirect()->away($payment->payment_url);
                }

                // Fallback kalau tidak ada payment_url (misal pakai JS pop nanti)
                return redirect()
                    ->route('checkout.show', $slug)
                    ->with('success', 'Tagihan berhasil dibuat. Silakan lanjutkan pembayaran melalui halaman Duitku.');
            } catch (\Throwable $e) {
                \Log::error('Duitku create invoice error: '.$e->getMessage());

                // Rollback status order kalau mau
                $order->status = 'failed';
                $order->save();

                return redirect()
                    ->route('checkout.show', $slug)
                    ->withErrors(['payment' => 'Gagal membuat tagihan pembayaran. Silakan coba lagi atau pilih COD.']);
            }
        }

    }

}
