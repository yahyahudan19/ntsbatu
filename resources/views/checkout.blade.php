@extends('layouts.app')

@section('title', 'Checkout - ' . $product['name'])

@section('content')
<div class="min-h-full bg-gray-50 py-12 px-4" data-page="checkout">
    <div class="max-w-5xl mx-auto">

        {{-- Back & judul --}}
        <div class="mb-8">
            <a href="{{ route('landing') }}"
               class="text-gray-600 hover:text-gray-900 flex items-center gap-2 mb-4">
                <span>←</span> Kembali ke Beranda
            </a>
            <h2 class="text-3xl font-bold text-gray-900">Checkout Pesanan</h2>
            <p class="mt-2 text-gray-600">
                Lengkapi data di bawah untuk menyelesaikan pre-order {{ $product['name'] }}.
            </p>
        </div>

        {{-- Alert / pesan (server side) --}}
        @if (session('success'))
            <div class="mb-4 border px-4 py-3 rounded bg-green-50 border-green-400 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 border px-4 py-3 rounded bg-red-50 border-red-400 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Alert JS lama masih bisa dipakai --}}
        <div id="alert-box" class="alert-box mb-4"></div>

        <div class="grid md:grid-cols-3 gap-8">
            {{-- Form checkout --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">

                    <h3 class="text-xl font-semibold mb-4">Data Pesanan</h3>

                    <form id="checkout-form"
                          action="{{ route('checkout.store', $product['slug']) }}"
                          method="POST">
                        @csrf

                        {{-- Product data untuk JS --}}
                        <input type="hidden" id="product-data" value='@json($product)'>

                        {{-- Product ID untuk backend --}}
                        <input type="hidden" id="product-id" name="product_id" value="{{ $product['id'] }}">

                        {{-- Tanggal pengiriman --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Pengiriman
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="delivery-date"
                                   name="delivery_date"
                                   value="{{ old('delivery_date') }}"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Pilih tanggal pengiriman"
                                   autocomplete="off"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                Pre-order H+1 sampai H+7 dari hari ini.
                            </p>
                        </div>

                        {{-- Paket/varian --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Paket & Jumlah
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="space-y-3" id="package-list">
                                @foreach($product['packages'] as $i => $pkg)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $pkg['label'] }}</div>
                                            <div class="text-sm text-gray-600">
                                                Rp {{ number_format($pkg['price'], 0, ',', '.') }} / pack
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            {{-- Hidden variant_id untuk backend --}}
                                            <input type="hidden"
                                                   name="variants[{{ $i }}][variant_id]"
                                                   value="{{ $pkg['id'] }}">

                                            <button type="button"
                                                    class="px-2 py-1 border rounded-lg text-sm"
                                                    onclick="changeVariantQty({{ $i }}, -1)">
                                                -
                                            </button>

                                            <input type="number"
                                                   id="variant-qty-{{ $i }}"
                                                   name="variants[{{ $i }}][qty]"
                                                   class="w-12 text-center border rounded-lg py-1 text-sm"
                                                   value="{{ old("variants.$i.qty", 0) }}"
                                                   min="0"
                                                   max="99"
                                                   oninput="onVariantQtyInput({{ $i }})">

                                            <button type="button"
                                                    class="px-2 py-1 border rounded-lg text-sm"
                                                    onclick="changeVariantQty({{ $i }}, 1)">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                Kamu bisa mengkombinasikan beberapa paket sekaligus. Biarkan 0 jika tidak ingin paket tersebut.
                            </p>
                        </div>

                        <hr class="my-5">

                        {{-- Data pelanggan --}}
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="customer-name"
                                   name="customer_name"
                                   value="{{ old('customer_name') }}"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor WhatsApp
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="customer-whatsapp"
                                   name="customer_whatsapp"
                                   value="{{ old('customer_whatsapp') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Pengiriman (Kota Batu)
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea id="customer-address"
                                      name="customer_address"
                                      rows="3"
                                      class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      required>{{ old('customer_address') }}</textarea>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran
                                <span class="text-red-500">*</span>
                            </label>

                            <select id="payment-method"
                                    name="payment_method"
                                    class="w-full border rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                    onchange="updateOrderSummary()">
                                <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>
                                    COD / Cash saat barang diterima
                                </option>
                                <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>
                                    QRIS (simulasi / nanti Duitku)
                                </option>
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full btn-primary text-white py-3 rounded-lg font-semibold mt-2">
                            Proses Pesanan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ringkasan pesanan (JS) --}}
            <div class="md:col-span-1">
                <div class="order-summary">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>

                    <div id="order-summary">
                        {{-- Diisi oleh JS --}}
                    </div>

                    <div id="total-section" class="mt-4 hidden">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Subtotal</span>
                            <span id="subtotal-price">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-base font-semibold">
                            <span>Total</span>
                            <span id="total-price">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal QRIS simulasi masih boleh dipakai nanti kalau mau --}}
    <div id="qris-modal" class="sweet-modal">
        <div class="sweet-modal__overlay" onclick="closeQrisModal()"></div>
        <div class="sweet-modal__container">
            <h3 class="text-xl font-semibold mb-2">Bayar dengan QRIS</h3>
            <p class="text-sm text-gray-600 mb-4">
                Untuk sementara ini QRIS masih simulasi. Nanti diintegrasikan dengan payment gateway.
            </p>
            <div class="bg-gray-200 h-40 flex items-center justify-center rounded-lg mb-4">
                <span>QR Code Placeholder</span>
            </div>
            <button class="btn-primary text-white py-2 px-4 rounded-lg w-full"
                    onclick="simulateQrisPaid()">
                Saya sudah bayar
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/checkout.js') }}"></script>
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Bahasa Indonesia --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
@endpush
