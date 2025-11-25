<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - NTS Batu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">


    {{-- CSS khusus dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- JS khusus dashboard --}}
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
</head>
<body class="dashboard-body dashboard-body--orders">

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-left">
            <span class="navbar-brand">Strawberry Order Admin</span>
        </div>

        <button class="navbar-toggle" id="navbarToggle">☰</button>

        <div class="navbar-right" id="navbarMenu">

            <a href="{{ route('dashboard') }}" class="navbar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('orders.index') }}" class="navbar-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                Data Order
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="navbar-link navbar-logout">
                    Logout
                </button>
            </form>

        </div>
    </nav>

    <main class="main-container main-container-orders">
        <header class="page-header">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">
                    Ringkasan pesanan strawberry.
                </p>
            </div>
        </header>

        {{-- Cards Statistik --}}
        <section class="stats-grid">
            <div class="stat-card stat-total">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-footer">Semua pesanan masuk</div>
            </div>

            <div class="stat-card stat-pending">
                <div class="stat-label">Pesanan Pending</div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-footer">Menunggu pembayaran / proses</div>
            </div>

            <div class="stat-card stat-paid">
                <div class="stat-label">Pesanan Terbayar</div>
                <div class="stat-value">{{ $stats['paid'] }}</div>
                <div class="stat-footer">Sudah dibayar / sukses</div>
            </div>
        </section>

        {{-- Tabel ringkas order terbaru --}}
        <section class="section-card">
            <div class="section-header">
                <h2 class="section-title">Pesanan Terbaru</h2>
                <a href="{{ route('orders.index') }}" class="btn-outline">
                    Lihat semua order
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
                                <td>{{ $order->order_code }}</td>
                                <td>
                                    <div>{{ $order->customer_name ?? '-' }}</div>
                                    @if($order->customer_phone)
                                        <small class="text-muted">
                                            {{ $order->customer_phone }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $order->total_quantity }} pack
                                </td>
                                <td>
                                    Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
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
                                    Belum ada pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
