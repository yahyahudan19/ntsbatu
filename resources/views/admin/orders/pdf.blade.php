<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_code ?? $order->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 15px;
            border: 1px solid #eee;
        }
        .table-header {
            width: 100%;
            margin-bottom: 20px;
        }
        .table-header td {
            vertical-align: top;
        }
        .company-info {
            font-size: 11px;
            color: #666;
            line-height: 1.5;
        }
        .invoice-details {
            text-align: right;
            font-size: 11px;
            color: #666;
            line-height: 1.5;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .paid-stamp {
            display: inline-block;
            padding: 5px 15px;
            border: 2px solid #16a34a;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            color: #16a34a;
            text-transform: uppercase;
            margin: 10px 0;
            background: #f0fdf4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
            padding: 8px;
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            text-align: left;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 4px 8px;
        }
        .summary-label {
            color: #6b7280;
        }
        .summary-value {
            text-align: right;
            font-weight: bold;
        }
        .total-row td {
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px dashed #e5e7eb;
            padding-top: 15px;
            font-size: 10px;
            color: #6b7280;
            width: 100%;
        }
        .footer td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="table-header">
            <tr>
                <td>
                    <h2 style="margin:0; font-size:16px; color: #b91c1c;">NTS Batu</h2>
                    <div class="company-info">
                        Jl. Nurul Kamil No.4<br>
                        Pandanrejo, Kota Batu<br>
                        WA: 082-331-560-207
                    </div>
                </td>
                <td class="invoice-details">
                    <div class="invoice-title">Invoice #{{ $order->order_code ?? $order->id }}</div>
                    Tanggal Order: {{ $order->created_at?->format('d M Y H:i') }}<br>
                    Tanggal Kirim: {{ $order->delivery_date?->format('d M Y') ?? '-' }}<br><br>
                    <strong>Pelanggan:</strong><br>
                    {{ $order->customer_name }}<br>
                    WA: {{ $order->customer_phone }}<br>
                    Alamat: {{ $order->customer_address }}
                </td>
            </tr>
        </table>

        @php
            $paymentStatus = $order->payment->status ?? $order->payment_status ?? null;
            $isPaid = in_array(strtolower($paymentStatus), ['paid', 'success', 'settlement']);
        @endphp

        @if($isPaid)
            <div style="text-align: right;">
                <span class="paid-stamp">LUNAS / PAID</span>
            </div>
        @endif

        <table class="items-table">
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
                        <td>{{ $item->product->name ?? ($item->product_name ?? '-') }}</td>
                        <td>{{ $item->variant_name ?? ($item->variant_label ?? '-') }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada item pada order ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="summary-table" style="width: 40%; float: right; margin-top: 10px;">
            <tr>
                <td class="summary-label">Subtotal</td>
                <td class="summary-value">Rp {{ number_format($order->subtotal ?? ($order->total_amount ?? 0), 0, ',', '.') }}</td>
            </tr>
            @if (!empty($order->delivery_fee))
                <tr>
                    <td class="summary-label">Ongkir</td>
                    <td class="summary-value">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if (!empty($order->discount_amount))
                <tr>
                    <td class="summary-label">Diskon</td>
                    <td class="summary-value">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="summary-label">Total Bayar</td>
                <td class="summary-value" style="color: #b91c1c;">Rp {{ number_format($order->grand_total ?? ($order->total_amount ?? 0), 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <div style="clear: both;"></div>

        <table class="footer">
            <tr>
                <td>
                    <strong>Terima kasih telah berbelanja di NTS Batu 🍓</strong><br>
                    Produk segar dipetik langsung dari kebun Pandanrejo, Kota Batu.
                </td>
                <td style="text-align: right;">
                    <strong>Kontak Kami</strong><br>
                    WA: 082-331-560-207
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
