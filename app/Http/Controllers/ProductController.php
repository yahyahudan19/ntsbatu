<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('variants');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter status (active / inactive / all)
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $status);
        }

        $products = $query
            ->orderBy('name')
            ->get(); // kalau mau paginate: ->paginate(20);

        return view('admin.product.index', compact('products'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['required', 'boolean'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $product->name        = $validated['name'];
        $product->slug        = $validated['slug'] ?? null;
        $product->description = $validated['description'] ?? null;
        $product->is_active   = $validated['is_active'];

        // Kalau upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus file lama (optional)
            if ($product->image && file_exists(public_path('images/product/' . $product->image))) {
                @unlink(public_path('images/product/' . $product->image));
            }

            $file     = $request->file('image');
            $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '-' . Str::slug($basename) . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/product'), $filename);

            $product->image = $filename;
        }

        $product->save();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function updatePrices(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variants'              => ['required', 'array'],
            'variants.*.id'         => ['required', 'exists:product_variants,id'],
            'variants.*.price'      => ['required', 'integer', 'min:0'],
            'variants.*.is_active'  => ['required', 'boolean'],
        ]);

        foreach ($validated['variants'] as $variantData) {
            /** @var \App\Models\ProductVariant|null $variant */
            $variant = $product->variants()
                ->where('id', $variantData['id'])
                ->first();

            if (! $variant) {
                // skip kalau varian bukan milik product ini
                continue;
            }

            $variant->price     = $variantData['price'];
            $variant->is_active = $variantData['is_active'];
            $variant->save();
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Harga varian produk berhasil diperbarui.');
    }
}
