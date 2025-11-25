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

}
