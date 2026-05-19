@extends('layouts.admin')

@section('title', 'Detail Order #' . ($order->order_code ?? $order->id))
@section('topbar-title', 'Detail Order')

@php $activeNav = 'orders'; @endphp

@section('head')
    <style>
        /* ===== BACK BUTTON ===== */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: 10px;
            border: 1px solid var(--card-border);
            background: rgba(148, 163, 184, 0.06);
            color: var(--text-secondary);
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
        }
        .btn-back:hover {
            background: rgba(148, 163, 184, 0.15);
            color: var(--text);
            border-color: var(--accent);
        }
        .btn-back svg {
            width: 16px;
            height: 16px;
        }

        /* ===== INVOICE LAYOUT ===== */
        .invoice-wrapper {
            display: grid;
            grid-template-columns: 2.2fr 1.3fr;
            gap: 20px;
            margin-top: 12px;
        }
        .invoice-section {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            padding: 22px 24px;
            box-shadow: var(--shadow);
        }
        .invoice-header-top {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }
        .invoice-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 6px;
            color: var(--text);
            letter-spacing: -0.01em;
        }
        .invoice-meta {
            font-size: 0.82rem;
            color: var(--text-dim);
            line-height: 1.6;
        }
        .invoice-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-dim);
            margin-bottom: 4px;
            font-weight: 600;
        }
        .invoice-value {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text);
            line-height: 1.5;
        }
        .invoice-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 16px;
            margin-top: 12px;
            padding-top: 14px;
            border-top: 1px solid rgba(148, 163, 184, 0.08);
        }

        /* ===== TABLE ===== */
        .invoice-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin-top: 16px;
        }
        .invoice-items-table th,
        .invoice-items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.08);
            text-align: left;
        }
        .invoice-items-table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            color: var(--text-secondary);
            background: rgba(148, 163, 184, 0.06);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }
        .invoice-items-table th:first-child { border-radius: 8px 0 0 0; }
        .invoice-items-table th:last-child  { border-radius: 0 8px 0 0; }
        .invoice-items-table tbody tr {
            transition: background 0.15s ease;
        }
        .invoice-items-table tbody tr:hover {
            background: rgba(148, 163, 184, 0.04);
        }
        .text-right { text-align: right; }

        /* ===== SUMMARY ===== */
        .invoice-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.86rem;
        }
        .invoice-summary-label { color: var(--text-dim); }
        .invoice-summary-value { font-weight: 500; color: var(--text); }
        .invoice-summary-total {
            font-size: 1.05rem;
            font-weight: 700;
        }
        .invoice-summary-total .invoice-summary-value {
            color: var(--accent);
        }

        /* ===== BADGE ===== */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }
        .badge-status.pending, .badge-status.pending_payment {
            color: var(--amber);
            background: rgba(251, 191, 36, 0.1);
            border-color: rgba(251, 191, 36, 0.15);
        }
        .badge-status.paid, .badge-status.settlement, .badge-status.completed {
            color: var(--emerald);
            background: rgba(52, 211, 153, 0.1);
            border-color: rgba(52, 211, 153, 0.15);
        }
        .badge-status.cancelled, .badge-status.failed {
            color: var(--rose);
            background: rgba(251, 113, 133, 0.1);
            border-color: rgba(251, 113, 133, 0.15);
        }
        .badge-status.cod, .badge-status.draft, .badge-status.processing, .badge-status.shipped {
            color: var(--text-secondary);
            background: rgba(148, 163, 184, 0.1);
            border-color: rgba(148, 163, 184, 0.15);
        }

        /* ===== PAYMENT INFO ===== */
        .payment-info {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid rgba(148, 163, 184, 0.08);
        }
        .payment-info-item {
            font-size: 0.8rem;
            color: var(--text-dim);
            margin-bottom: 4px;
            line-height: 1.5;
        }
        .payment-info-item strong {
            color: var(--text-secondary);
        }

        .invoice-note {
            margin-top: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(148, 163, 184, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.08);
            font-size: 0.76rem;
            color: var(--text-dim);
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .invoice-wrapper {
                grid-template-columns: 1fr;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 14px;">
            <a href="{{ route('orders.index') }}" class="btn-back" title="Kembali ke Daftar Order">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
            <div>
                <h1 class="page-title">Detail Order</h1>
                <p class="page-subtitle">
                    Invoice untuk pesanan
                    <strong>#{{ $order->order_code ?? $order->id }}</strong>
                </p>
            </div>
        </div>

        <div class="action-buttons">
            <form id="form-send-wa" action="{{ route('orders.sendWhatsApp', $order) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="button" class="btn-secondary" id="btn-send-wa">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    Kirim via WhatsApp
                </button>
            </form>
            <a href="{{ route('orders.print', $order) }}"
               target="_blank"
               class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;margin-right:4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 12h.008v.008h-.008V12Zm-1.5 0h.008v.008H17.25V12Z" />
                </svg>
                Print Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <strong>Error!</strong> {{ session('error') }}
            </div>
        </div>
    @endif

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
                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                        </span>
                    </div>

                    @if(isset($order->payment_method))
                        <div class="invoice-label" style="margin-top: 10px;">Metode Bayar</div>
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
                        {{ $order->customer_name ?? '-' }}
                        @if(!empty($order->customer_whatsapp))
                            <br>
                            <span style="font-size: 0.82rem; color: var(--text-dim);">
                                WA: {{ $order->customer_whatsapp }}
                            </span>
                        @endif
                        @if(!empty($order->customer_address))
                            <br>
                            <span style="font-size: 0.8rem; color: var(--text-dim);">
                                {{ $order->customer_address }}
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="invoice-label">Ringkasan</div>
                    <div class="invoice-value" style="font-size: 0.84rem;">
                        Kode: <strong>{{ $order->order_code ?? '-' }}</strong>
                        <br>
                        @php
                            $paymentStatus = $order->payment->status ?? $order->payment_status ?? null;
                        @endphp
                        Status pembayaran:
                        @if($paymentStatus)
                            <strong style="color: var(--accent);">{{ strtoupper($paymentStatus) }}</strong>
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
                            <td style="font-weight: 500;">
                                {{ $item->product->name ?? $item->product_name ?? '-' }}
                            </td>
                            <td style="color: var(--text-secondary);">
                                {{ $item->variant_name ?? $item->variant_label ?? '-' }}
                            </td>
                            <td class="text-right" style="font-weight: 500;">
                                {{ $item->quantity }}
                            </td>
                            <td class="text-right" style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="text-right" style="font-weight: 600; font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted" style="text-align:center; padding:18px 0;">
                                Tidak ada item pada order ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        {{-- Kanan: ringkasan pembayaran --}}
        <section class="invoice-section">
            <h3 class="section-title" style="margin-bottom: 14px;">Ringkasan Pembayaran</h3>

            <div class="invoice-summary-row">
                <span class="invoice-summary-label">Subtotal</span>
                <span class="invoice-summary-value" style="font-family: 'JetBrains Mono', monospace;">
                    Rp {{ number_format($order->subtotal ?? $order->total_amount ?? 0, 0, ',', '.') }}
                </span>
            </div>

            @if(!empty($order->delivery_fee))
                <div class="invoice-summary-row">
                    <span class="invoice-summary-label">Ongkir</span>
                    <span class="invoice-summary-value" style="font-family: 'JetBrains Mono', monospace;">
                        Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                    </span>
                </div>
            @endif

            @if(!empty($order->discount_amount))
                <div class="invoice-summary-row">
                    <span class="invoice-summary-label">Diskon</span>
                    <span class="invoice-summary-value" style="color: var(--rose); font-family: 'JetBrains Mono', monospace;">
                        - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                    </span>
                </div>
            @endif

            <hr style="margin: 12px 0; border-color: rgba(148, 163, 184, 0.1);">

            <div class="invoice-summary-row invoice-summary-total">
                <span class="invoice-summary-label">Total Bayar</span>
                <span class="invoice-summary-value" style="font-family: 'JetBrains Mono', monospace;">
                    Rp {{ number_format($order->grand_total ?? $order->total_amount ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <div class="payment-info">
                @if(isset($paymentStatus))
                    <div class="payment-info-item">
                        Status pembayaran:
                        <strong>{{ strtoupper($paymentStatus) }}</strong>
                    </div>
                    @if($order->payment?->paid_at)
                        <div class="payment-info-item">
                            Dibayar pada:
                            <strong>{{ $order->payment->paid_at->format('d M Y H:i') }}</strong>
                        </div>
                    @endif
                @else
                    <div class="payment-info-item">
                        Belum ada data pembayaran tercatat.
                    </div>
                @endif
            </div>

            <div class="invoice-note">
                <strong>Catatan:</strong><br>
                Invoice ini hanya untuk keperluan internal dan dapat dikirim ke pelanggan melalui WhatsApp.
            </div>
        </section>
    </div>

@endsection

@section('scripts')
    <script>
        document.getElementById('btn-send-wa').addEventListener('click', function () {
            Swal.fire({
                title: 'Kirim Invoice?',
                text: "Invoice akan dikirim ke nomor WhatsApp pelanggan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-send-wa').submit();
                }
            });
        });
    </script>
@endsection
