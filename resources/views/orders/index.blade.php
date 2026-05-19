@extends('layouts.admin')

@section('title', 'Data Orders')
@section('topbar-title', 'Data Orders')

@php $activeNav = 'orders'; @endphp

@section('head')
    {{-- DataTable + Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .status-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-status-change,
        .btn-view-order {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.3rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 8px;
            border: 1px solid rgba(148, 163, 184, 0.15);
            background: rgba(148, 163, 184, 0.06);
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-family: inherit;
        }
        .btn-status-change:hover,
        .btn-view-order:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
        }
        .btn-status-change:active,
        .btn-view-order:active {
            transform: translateY(1px);
        }
    </style>
@endsection

@section('content')

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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr
                            data-created-at="{{ optional($order->created_at)->toIso8601String() }}"
                            data-delivery-date="{{ optional($order->delivery_date)->toDateString() }}"
                            data-delivery-slot="{{ $order->delivery_time_slot }}"
                        >
                            <td><span class="order-code">{{ $order->order_code }}</span></td>
                            <td>
                                <div class="customer-name">{{ $order->customer_name ?? '-' }}</div>
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
                            <td><span class="amount">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</span></td>
                            <td>{{ $order->payment_method_label }}</td>
                            <td>
                                    <div class="status-cell">
                                        <span class="badge badge-{{ $order->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                </td>

                            <td class="order-date-cell"></td>
                           <td>
                            <div class="action-buttons">

                                {{-- Tombol ubah status --}}
                                <form method="POST"
                                    action="{{ route('orders.updateStatus', $order) }}"
                                    class="inline status-update-form">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $order->status }}">

                                    <button type="button"
                                            class="btn-status-change"
                                            data-current-status="{{ $order->status }}">
                                        Ubah Status
                                    </button>
                                </form>

                                <a href="{{ route('orders.show', $order) }}"
                                class="btn-view-order">
                                    Detail
                                </a>

                            </div>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
       
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('js/orders-index.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusOptions = {
                draft: 'Draft',
                pending_payment: 'Pending Payment',
                paid: 'Paid',
                processing: 'Processing',
                shipped: 'Shipped',
                completed: 'Completed',
                cancelled: 'Cancelled',
            };

            document.querySelectorAll('.btn-status-change').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const form = this.closest('form');
                    const currentStatus = this.dataset.currentStatus;

                    Swal.fire({
                        title: 'Ubah Status Pesanan',
                        text: 'Pilih status baru untuk pesanan ini.',
                        input: 'select',
                        inputOptions: statusOptions,
                        inputValue: currentStatus,
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Status tidak boleh kosong';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.querySelector('input[name="status"]').value = result.value;
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
