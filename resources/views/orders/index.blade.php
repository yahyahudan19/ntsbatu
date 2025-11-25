<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">


    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS dashboard + datatable + datetime picker --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- JS: jQuery + datatable + flatpickr + custom --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    <script src="{{ asset('js/orders-index.js') }}" defer></script>


</head>

<body class="dashboard-body">

    {{-- Navbar sama seperti dashboard --}}
    <nav class="navbar">
        <div class="navbar-left">
            <span class="navbar-brand">Strawberry Order Admin</span>
        </div>
        <button class="navbar-toggle" id="navbarToggle">
            ☰
        </button>
        <div class="navbar-right" id="navbarMenu">
            <a href="{{ route('dashboard') }}" class="navbar-link">Dashboard</a>
            <a href="{{ route('orders.index') }}" class="navbar-link active">Data Order</a>
        </div>
    </nav>

    <main class="main-container">
        <header class="page-header">
            <div>
                <h1 class="page-title">Data Orders</h1>
                <p class="page-subtitle">
                    Daftar semua pemesanan strawberry yang masuk.
                </p>
            </div>
        </header>

        {{-- Filter & Search --}}
        <section class="section-card" style="margin-bottom: 20px;">
            <form method="GET" action="{{ route('orders.index') }}" class="filter-form">
                <div class="filter-grid">
                    {{-- Search --}}
                    <div class="filter-group">
                        <label class="filter-label">Cari</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Kode, nama customer, atau nomor HP..."
                            class="filter-input"
                        >
                    </div>

                    {{-- Status --}}
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select name="status" class="filter-input">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(request('status', 'all') == $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Kirim --}}
                    <div class="filter-group">
                        <label class="filter-label">Tanggal Kirim</label>
                        <input
                            type="text"
                            name="delivery_date"
                            value="{{ request('delivery_date') }}"
                            class="filter-input"
                            id="filterDeliveryDate"
                            placeholder="Pilih tanggal kirim..."
                            autocomplete="off"
                        >
                    </div>


                    {{-- Tombol --}}
                    <div class="filter-actions">
                        <button type="submit" class="btn-outline" style="width: 100%;">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn-outline" style="margin-top: 6px; width: 100%; text-align:center;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </section>

        {{-- Tabel Orders --}}
        <section class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    {{ $orders->count() }} Orders
                </h2>
            </div>

            <div class="table-wrapper">
                <table class="table" id="ordersTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Lokasi</th>
                            <th>Qty</th>
                            <th>Grand Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr
                                data-created-at="{{ optional($order->created_at)->toIso8601String() }}"
                                data-delivery-date="{{ optional($order->delivery_date)->toDateString() }}"
                                data-delivery-slot="{{ $order->delivery_time_slot }}"
                            >
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
                                    @if($order->city || $order->area)
                                        {{ $order->city ?? '-' }}<br>
                                        <small class="text-muted">{{ $order->area }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $order->total_quantity }} pack</td>
                                <td>Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</td>
                                <td>{{ strtoupper($order->payment_method ?? '-') }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="order-date-cell">
                                    {{-- isi awal boleh kosong, nanti di-format via JS --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{-- @if($orders->hasPages())
                <div style="margin-top: 14px;">
                    {{ $orders->links() }}
                </div>
            @endif --}}
        </section>
    </main>
</body>
</html>
