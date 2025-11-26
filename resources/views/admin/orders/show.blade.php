<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Order #{{ $order->order_code ?? $order->id }} - NTS Batu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">


    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .invoice-wrapper {
            display: grid;
            grid-template-columns: 2.2fr 1.3fr;
            gap: 18px;
            margin-top: 10px;
        }

        .invoice-section {
            background: var(--bg-card);
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            padding: 16px 18px;
            box-shadow: var(--shadow-soft);
        }

        .invoice-header-top {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0 0 4px;
        }

        .invoice-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .invoice-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .invoice-value {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .invoice-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 12px;
            margin-top: 8px;
        }

        .invoice-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
            margin-top: 10px;
        }

        .invoice-items-table th,
        .invoice-items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .invoice-items-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .invoice-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.86rem;
        }

        .invoice-summary-label {
            color: var(--text-muted);
        }

        .invoice-summary-value {
            font-weight: 500;
        }

        .invoice-summary-total {
            font-size: 1rem;
            font-weight: 700;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .badge-status.pending {
            color: #92400e;
            background: #fef3c7;
            border-color: #facc15;
        }

        .badge-status.paid {
            color: #166534;
            background: #dcfce7;
            border-color: #22c55e;
        }

        .badge-status.cancelled {
            color: #b91c1c;
            background: #fee2e2;
            border-color: #ef4444;
        }

        .badge-status.cod {
            color: #1f2937;
            background: #e5e7eb;
            border-color: #9ca3af;
        }

        @media (max-width: 900px) {
            .invoice-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            font-family: "Onest", sans-serif;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: none;
            text-decoration: none;
        }

        /* PRIMARY BUTTON — warna utama dashboard */
        .btn-primary {
            background: #2563eb; /* biru */
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.2);
        }
        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:active {
            background: #1e40af;
            transform: scale(0.97);
        }

        /* SECONDARY BUTTON — abu netral */
        .btn-secondary {
            background: #f3f4f6; /* abu terang */
            color: #374151;
            border: 1px solid #d1d5db;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        .btn-secondary:active {
            background: #d1d5db;
            transform: scale(0.97);
        }

    </style>
</head>
<body class="dashboard-body">
    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-brand">
            Strawberry Order Admin
        </div>
        <div class="navbar-right" id="navbarMenu">
            <a href="{{ route('orders.index') }}" class="navbar-link">< Kembali</a>
            {{-- tambahkan link lain jika perlu --}}
        </div>
        <button class="navbar-toggle" onclick="document.getElementById('navbarMenu').classList.toggle('show')">
            ☰
        </button>
    </nav>

    <main class="main-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Detail Order</h1>
                <p class="page-subtitle">
                    Invoice untuk pesanan
                    <strong>#{{ $order->order_code ?? $order->id }}</strong>
                </p>
            </div>

            <div class="action-buttons">
                {{-- tombol kirim WA (belum dihubungkan, nanti saja) --}}
                <button type="button" class="btn-secondary" id="btn-send-wa"
                        data-order-id="{{ $order->id }}">
                    Kirim via WhatsApp
                </button>

                {{-- tombol print: buka tab baru ke route print --}}
                <a href="{{ route('orders.print', $order) }}"
                   target="_blank"
                   class="btn-primary">
                    Print Invoice
                </a>
            </div>
        </div>

        <div class="invoice-wrapper">
            {{-- Kiri: info order + items --}}
            <section class="invoice-section">
                <div class="invoice-header-top">
                    <div>
                        <h2 class="invoice-title">
                            Invoice #{{ $order->order_code ?? $order->id }}
                        </h2>
                        <div class="invoice-meta">
                            Tanggal order:
                            {{ $order->created_at?->format('d M Y H:i') }}
                            <br>
                            Tanggal kirim:
                            {{ $order->delivery_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <div class="invoice-label">Status Order</div>
                        <div>
                            @php
                                $status = $order->status ?? 'pending';
                            @endphp
                            <span class="badge-status {{ $status }}">
                                {{ strtoupper($status) }}
                            </span>
                        </div>

                        @if(isset($order->payment_method))
                            <div class="invoice-label" style="margin-top: 8px;">Metode Bayar</div>
                            <div>
                                <span class="badge-status {{ $order->payment_method }}">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="invoice-grid-2">
                    <div>
                        <div class="invoice-label">Kepada</div>
                        <div class="invoice-value">
                            {{ $order->customer_name ?? '-' }}<br>
                            @if(!empty($order->customer_whatsapp))
                                WA: {{ $order->customer_whatsapp }}<br>
                            @endif
                            <span class="text-muted" style="font-size: 0.8rem;">
                                {{ $order->customer_address ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="invoice-label">Ringkasan</div>
                        <div class="invoice-meta">
                            Kode: <strong>{{ $order->order_code ?? '-' }}</strong><br>
                            Status pembayaran:
                            @php
                                $paymentStatus = $order->payment->status ?? $order->payment_status ?? null;
                            @endphp
                            @if($paymentStatus)
                                <strong>{{ strtoupper($paymentStatus) }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tabel item --}}
                <table class="invoice-items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                   <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product->name ?? $item->product_name ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->variant_name ?? $item->variant_label ?? '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $item->quantity }}
                                </td>
                                <td class="text-right">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted" style="text-align:center; padding:12px 0;">
                                    Tidak ada item pada order ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            {{-- Kanan: ringkasan pembayaran --}}
            <section class="invoice-section">
                <h3 class="section-title" style="margin-bottom: 10px;">Ringkasan Pembayaran</h3>

                <div class="invoice-summary-row">
                    <span class="invoice-summary-label">Subtotal</span>
                    <span class="invoice-summary-value">
                        Rp {{ number_format($order->subtotal ?? $order->total_amount ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                @if(!empty($order->delivery_fee))
                    <div class="invoice-summary-row">
                        <span class="invoice-summary-label">Ongkir</span>
                        <span class="invoice-summary-value">
                            Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                @if(!empty($order->discount_amount))
                    <div class="invoice-summary-row">
                        <span class="invoice-summary-label">Diskon</span>
                        <span class="invoice-summary-value">
                            - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                <hr style="margin: 10px 0; border-color:#e5e7eb;">

                <div class="invoice-summary-row">
                    <span class="invoice-summary-label invoice-summary-total">Total Bayar</span>
                    <span class="invoice-summary-value invoice-summary-total">
                        Rp {{ number_format($order->grand_total ?? $order->total_amount ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="text-muted" style="margin-top: 14px; font-size: 0.78rem;">
                    @if(isset($paymentStatus))
                        Status pembayaran:
                        <strong>{{ strtoupper($paymentStatus) }}</strong>
                        @if($order->payment?->paid_at)
                            <br>Dibayar pada:
                            {{ $order->payment->paid_at->format('d M Y H:i') }}
                        @endif
                    @else
                        Belum ada data pembayaran tercatat.
                    @endif
                </div>

                <div class="text-muted" style="margin-top: 16px; font-size: 0.75rem;">
                    Catatan:
                    <br>
                    <em>Invoice ini hanya untuk keperluan internal dan dapat dikirim ke pelanggan melalui WhatsApp.</em>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('btn-send-wa').addEventListener('click', function () {
            const orderId = this.dataset.orderId;

            // TODO: ganti dengan panggilan route ajax / post ke WA nanti
            alert('Fitur kirim WhatsApp akan diintegrasikan nanti. Order ID: ' + orderId);
        });
    </script>
</body>
</html>
