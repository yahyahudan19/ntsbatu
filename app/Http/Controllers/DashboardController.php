<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'   => Order::count(),

            // Pending / belum bayar (coba cover beberapa kemungkinan nilai)
            'pending' => Order::whereIn('status', [
                'pending',
                'unpaid',
                'waiting_payment',
            ])->count(),

            // Terbayar / sukses
            'paid'    => Order::whereIn('status', [
                'paid',
                'success',
                'settlement',
            ])->count(),
        ];

        // Ambil 5 pesanan terbaru + relasi items (buat total quantity)
        $latestOrders = Order::with('items')
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

        return view('dashboard.index', compact('stats', 'latestOrders'));
    }
}
