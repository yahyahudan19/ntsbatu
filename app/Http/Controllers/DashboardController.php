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
            'pending' => Order::whereIn('status', [
                'pending',
                'unpaid',
                'waiting_payment',
            ])->count(),
            'paid'    => Order::whereIn('status', [
                'paid',
                'success',
                'settlement',
            ])->count(),
        ];

        $latestOrders = Order::with('items')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ️⬇️ Tambahan di sini
        $paidStatuses = ['paid', 'success', 'settlement'];
        $today        = now(); // pakai timezone app

        $revenue = [
            'total' => Order::whereIn('status', $paidStatuses)
                ->sum('grand_total'),

            'today' => Order::whereIn('status', $paidStatuses)
                ->whereDate('created_at', $today->toDateString())
                ->sum('grand_total'),

            'month' => Order::whereIn('status', $paidStatuses)
                ->whereYear('created_at', $today->year)
                ->whereMonth('created_at', $today->month)
                ->sum('grand_total'),
        ];

        return view('dashboard.index', compact('stats', 'latestOrders', 'revenue'));
    }
}
