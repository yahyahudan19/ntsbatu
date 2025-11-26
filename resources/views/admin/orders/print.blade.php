<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Invoice #{{ $order->order_code ?? $order->id }} - NTS Batu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">


    {{-- Boleh pakai CSS yang sama biar rapi --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        body {
            font-family: "Onest", system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", sans-serif;
            background: #ffffff;
            padding: 16px;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px 22px;
        }

        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .print-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .print-meta {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
            margin-top: 12px;
        }

        .print-table th,
        .print-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .print-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.86rem;
            margin-bottom: 4px;
        }

        .summary-label {
            color: #6b7280;
        }

        .summary-total {
            font-size: 1rem;
            font-weight: 700;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .print-container {
                border: none;
                border-radius: 0;
            }
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info-title {
            margin-bottom: 8px;
        }

        .invoice-meta-group {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .meta-section {
            margin-bottom: 8px;
        }

        .meta-row {
            display: flex;
            justify-content: flex-end;
            gap: 6px;
            margin-bottom: 2px;
        }

        .meta-label {
            font-weight: 600;
            min-width: 95px;
            text-align: right;
        }

        .meta-value {
            max-width: 220px;
            word-wrap: break-word;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px 22px;
            position: relative;
            /* ⬅️ penting untuk posisi stempel */
        }

        /* STAMPEL PAID */
        .paid-stamp {
            margin-top: 3px;
            margin-bottom: 10px;
            display: inline-block;

            float: right;

            padding: 8px 20px;
            border: 3px solid #16a34a;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #16a34a;
            background: rgba(240, 253, 244, 0.9);
            transform: rotate(-5deg);
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.2);
        }


        /* FOOTER */
        .invoice-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #e5e7eb;
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .invoice-footer-left {
            max-width: 60%;
        }

        .invoice-footer-right {
            text-align: right;
            max-width: 35%;
        }

        .invoice-footer-strong {
            font-weight: 600;
        }

        /* Saat print, tetap rapi */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .print-container {
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <div class="print-header">
            {{-- Logo dan Info Toko --}}
            <div style="display:flex; gap:14px;">
                <img src="/images/logos/logo.png" alt="Logo NTS Batu"
                    style="width:65px; height:auto; border-radius:8px;">

                <div>
                    <h2 style="margin:0; font-size:1.1rem; font-weight:700;">NTS Batu</h2>
                    <div class="print-meta">
                        Jl. Nurul Kamil No.4<br>
                        Pandanrejo, Kota Batu<br>
                        WA: 082-331-560-207<br>
                        {{-- <span>Instagram: @ntsbatu</span> --}}
                    </div>
                </div>
            </div>

            {{-- Informasi Invoice --}}
            <div style="text-align:right;">
                <h1 class="print-title" style="margin-bottom:6px;">
                    Invoice #{{ $order->order_code ?? $order->id }}
                </h1>

                <div class="print-meta">
                    Tanggal Order:
                    {{ $order->created_at?->format('d M Y H:i') }}<br>

                    Tanggal Kirim:
                    {{ $order->delivery_date?->format('d M Y') ?? '-' }}<br>

                    <br>
                    <strong>Pelanggan:</strong>
                    {{ $order->customer_name }}<br>
                    <strong>Whatsapp : </strong>
                    {{ $order->customer_phone }}<br>
                    <strong> Alamat : </strong>
                    {{ $order->customer_address }}
                </div>
            </div>
        </div>
            @php
                $paymentStatus = $order->payment->status ?? $order->payment_status ?? null;
                $isPaid = in_array(strtolower($paymentStatus), ['paid', 'success', 'settlement']);
            @endphp

            @if($isPaid)
                <div class="paid-stamp">PAID</div>
            @endif

        <table class="print-table">
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
                            {{ $item->product->name ?? ($item->product_name ?? '-') }}
                        </td>
                        <td>
                            {{ $item->variant_name ?? ($item->variant_label ?? '-') }}
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
                        <td colspan="5" style="text-align:center; padding:10px 0;">
                            Tidak ada item pada order ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span>
                    Rp {{ number_format($order->subtotal ?? ($order->total_amount ?? 0), 0, ',', '.') }}
                </span>
            </div>

            @if (!empty($order->delivery_fee))
                <div class="summary-row">
                    <span class="summary-label">Ongkir</span>
                    <span>
                        Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                    </span>
                </div>
            @endif

            @if (!empty($order->discount_amount))
                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span>
                        - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                    </span>
                </div>
            @endif

            <hr style="margin: 10px 0; border-color:#e5e7eb;">

            <div class="summary-row">
                <span class="summary-label summary-total">Total Bayar</span>
                <span class="summary-total">
                    Rp {{ number_format($order->grand_total ?? ($order->total_amount ?? 0), 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="invoice-footer-left">
                <span class="invoice-footer-strong">Terima kasih telah berbelanja di NTS Batu 🍓</span><br>
                Produk segar dipetik langsung dari kebun Pandanrejo, Kota Batu.
                <br>
                Simpan invoice ini sebagai bukti transaksi.
            </div>

            <div class="invoice-footer-right">
                <span class="invoice-footer-strong">Kontak Kami</span><br>
                WA: 082-331-560-207<br>
                Alamat: Jl. Nurul Kamil No.4,<br>
                Pandanrejo, Kota Batu
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>

</html>
