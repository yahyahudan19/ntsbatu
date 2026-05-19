<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items', 'user', 'payment'); // ← tambah 'payment'

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search (kode / nama / phone)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter tanggal kirim
        if ($request->filled('delivery_date')) {
            $query->whereDate('delivery_date', $request->delivery_date);
        }

        $orders = $query->orderByDesc('created_at')->get();

        // List status untuk dropdown
        $statusOptions = [
            'all'       => 'Semua Status',
            'pending'   => 'Pending',
            'paid'      => 'Terbayar',
            'cancelled' => 'Dibatalkan',
            'failed'    => 'Gagal',
        ];

        return view('orders.index', compact('orders', 'statusOptions'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:draft,pending_payment,paid,processing,shipped,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function show(Order $order)
    {
        // eager load relasi yang dibutuhkan
        $order->load([
            'items.product',
            'payment',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function print(Order $order)
    {
        $order->load([
            'items.product',
            'payment',
        ]);

        // view khusus print (tanpa navbar dashboard)
        return view('admin.orders.print', compact('order'));
    }

    public function sendWhatsApp(Order $order, \App\Services\WhatsAppService $whatsappService)
    {
        // 1. Send the text notification
        $textResult = $whatsappService->sendOrderNotification($order);

        // 2. Send the PDF invoice
        $pdfResult = $whatsappService->sendOrderInvoicePdf($order);

        if (($textResult['success'] ?? false) || ($pdfResult['success'] ?? false)) {
            $message = 'Invoice berhasil dikirim ke WhatsApp pelanggan.';
            if (!($textResult['success'] ?? false)) {
                $message .= ' (Pesan teks gagal: ' . ($textResult['error'] ?? '') . ')';
            }
            if (!($pdfResult['success'] ?? false)) {
                $message .= ' (PDF invoice gagal: ' . ($pdfResult['error'] ?? '') . ')';
            }
            return back()->with('success', $message);
        } else {
            return back()->with('error', 'Gagal mengirim WhatsApp: ' . ($textResult['error'] ?? $pdfResult['error'] ?? 'Unknown error'));
        }
    }
}
