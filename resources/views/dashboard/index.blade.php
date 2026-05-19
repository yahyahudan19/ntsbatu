@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@php $activeNav = 'dashboard'; @endphp

@section('content')

    {{-- Welcome Banner --}}
    <div class="welcome-banner animate-in" style="animation-delay: 0.05s">
        <h1>Selamat Datang Kembali! 👋</h1>
        <p>Berikut ringkasan pesanan dan keuntungan strawberry Anda hari ini. Pantau semua data penting dari satu tempat.</p>
    </div>

    {{-- ===== ORDER STATS ===== --}}
    <div class="section-label animate-in" style="animation-delay: 0.1s">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.875 14.25l1.214 1.942a2.25 2.25 0 001.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 011.872 1.002l.164.246a2.25 2.25 0 001.872 1.002h2.092a2.25 2.25 0 001.872-1.002l.164-.246A2.25 2.25 0 0116.954 9h4.636M2.41 9a2.25 2.25 0 00-.16.832V12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 01.382-.632l3.285-3.832a2.25 2.25 0 011.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632" />
        </svg>
        Statistik Pesanan
    </div>

    <section class="stats-grid">
        <div class="stat-card stat-total animate-in" style="animation-delay: 0.15s">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h2.21a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value" data-count="{{ $stats['total'] }}">{{ $stats['total'] }}</div>
                    <div class="stat-footer">Semua pesanan masuk</div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-pending animate-in" style="animation-delay: 0.2s">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Pesanan Pending</div>
                    <div class="stat-value" data-count="{{ $stats['pending'] }}">{{ $stats['pending'] }}</div>
                    <div class="stat-footer">Menunggu pembayaran</div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-paid animate-in" style="animation-delay: 0.25s">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Pesanan Terbayar</div>
                    <div class="stat-value" data-count="{{ $stats['paid'] }}">{{ $stats['paid'] }}</div>
                    <div class="stat-footer">Sudah dibayar / sukses</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== REVENUE STATS ===== --}}
    <div class="section-label animate-in" style="animation-delay: 0.3s">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
        </svg>
        Statistik Keuntungan
    </div>

    <section class="stats-grid">
        <div class="stat-card stat-earn-today animate-in" style="animation-delay: 0.35s">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Keuntungan Hari Ini</div>
                    <div class="stat-value">Rp {{ number_format($revenue['today'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-footer">Total paid hari ini</div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-earn-month animate-in" style="animation-delay: 0.4s">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Keuntungan Bulan Ini</div>
                    <div class="stat-value">Rp {{ number_format($revenue['month'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-footer">Akumulasi bulan berjalan</div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-earn-total animate-in" style="animation-delay: 0.45s">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Keuntungan</div>
                    <div class="stat-value">Rp {{ number_format($revenue['total'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-footer">Semua order paid/success</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CONTENT GRID: Table + Quick Info ===== --}}
    <div class="content-grid">

        {{-- Latest Orders Table --}}
        <section class="section-card animate-in" style="animation-delay: 0.5s">
            <div class="section-header">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Pesanan Terbaru
                </h2>
                <a href="{{ route('orders.index') }}" class="btn-outline">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestOrders as $order)
                            <tr>
                                <td><span class="order-code">{{ $order->order_code }}</span></td>
                                <td>
                                    <div class="customer-name">{{ $order->customer_name ?? '-' }}</div>
                                    @if($order->customer_phone)
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $order->total_quantity }} pack</td>
                                <td>
                                    <span class="amount">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $order->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->delivery_date)
                                        {{ $order->delivery_date->format('d M Y') }}
                                    @else
                                        {{ $order->created_at?->format('d M Y H:i') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" width="36" height="36" style="display:block;margin:0 auto 8px;opacity:.3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h2.21a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                    Belum ada pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Right Sidebar: Quick Info --}}
        <div class="quick-info">

            {{-- Order Status Breakdown --}}
            <div class="info-card animate-in" style="animation-delay: 0.55s">
                <div class="info-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                    Status Pesanan
                </div>
                <div class="status-list">
                    <div class="status-item">
                        <div class="status-item-left">
                            <span class="status-dot sky"></span>
                            <span class="status-item-label">Total</span>
                        </div>
                        <span class="status-item-value" data-count="{{ $stats['total'] }}">{{ $stats['total'] }}</span>
                    </div>
                    <div class="status-item">
                        <div class="status-item-left">
                            <span class="status-dot amber"></span>
                            <span class="status-item-label">Pending</span>
                        </div>
                        <span class="status-item-value" data-count="{{ $stats['pending'] }}">{{ $stats['pending'] }}</span>
                    </div>
                    <div class="status-item">
                        <div class="status-item-left">
                            <span class="status-dot emerald"></span>
                            <span class="status-item-label">Terbayar</span>
                        </div>
                        <span class="status-item-value" data-count="{{ $stats['paid'] }}">{{ $stats['paid'] }}</span>
                    </div>
                </div>
                @php
                    $total = $stats['total'] ?: 1;
                    $paidPct = round(($stats['paid'] / $total) * 100);
                @endphp
                <div class="progress-bar">
                    <div class="progress-bar-inner" style="width: {{ $paidPct }}%; background: linear-gradient(90deg, var(--emerald), var(--cyan));"></div>
                </div>
                <div class="text-muted" style="margin-top: 6px;">{{ $paidPct }}% pesanan sudah terbayar</div>
            </div>

            {{-- Revenue Visual --}}
            <div class="info-card animate-in" style="animation-delay: 0.6s">
                <div class="info-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    Keuntungan
                </div>
                @php
                    $maxRevenue = max($revenue['today'] ?? 1, $revenue['month'] ?? 1, $revenue['total'] ?? 1) ?: 1;
                    $todayPct = round((($revenue['today'] ?? 0) / $maxRevenue) * 100);
                    $monthPct = round((($revenue['month'] ?? 0) / $maxRevenue) * 100);
                    $totalPct = 100;
                @endphp
                <div class="mini-chart">
                    <div class="mini-chart-bar" data-label="Hari Ini"
                         style="height: {{ max($todayPct, 8) }}%; background: linear-gradient(to top, rgba(52,211,153,0.3), var(--emerald));"></div>
                    <div class="mini-chart-bar" data-label="Bulan"
                         style="height: {{ max($monthPct, 8) }}%; background: linear-gradient(to top, rgba(34,211,238,0.3), var(--cyan));"></div>
                    <div class="mini-chart-bar" data-label="Total"
                         style="height: {{ max($totalPct, 8) }}%; background: linear-gradient(to top, rgba(167,139,250,0.3), var(--violet));"></div>
                </div>
                <div style="margin-top: 28px;">
                    <div class="status-item" style="margin-bottom: 6px;">
                        <span class="status-item-label">Hari Ini</span>
                        <span class="status-item-value" style="font-size: 0.82rem; color: var(--emerald);">
                            Rp {{ number_format($revenue['today'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="status-item" style="margin-bottom: 6px;">
                        <span class="status-item-label">Bulan Ini</span>
                        <span class="status-item-value" style="font-size: 0.82rem; color: var(--cyan);">
                            Rp {{ number_format($revenue['month'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-item-label">Total</span>
                        <span class="status-item-value" style="font-size: 0.82rem; color: var(--violet);">
                            Rp {{ number_format($revenue['total'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="info-card animate-in" style="animation-delay: 0.65s">
                <div class="info-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                    Aksi Cepat
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="{{ route('orders.index') }}" class="btn-secondary" style="justify-content: flex-start; gap: 10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        Kelola Pesanan
                    </a>
                    <a href="{{ route('products.index') }}" class="btn-secondary" style="justify-content: flex-start; gap: 10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        Kelola Produk
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
