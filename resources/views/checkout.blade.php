@extends('layouts.app')

@section('title', 'Checkout - ' . $product['name'])

@section('content')
<div class="min-h-full bg-gray-50 py-12 px-4" data-page="checkout">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Back & judul --}}
        <div>
            <a href="{{ route('landing') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                <span class="text-lg">←</span>
                <span>Kembali ke Beranda</span>
            </a>

            <h2 class="mt-4 text-3xl font-bold text-gray-900">
                Checkout Pesanan
            </h2>
            <p class="mt-2 text-gray-600 text-sm md:text-base">
                Lengkapi data di bawah untuk menyelesaikan pre-order
                <span class="font-semibold text-gray-900">{{ $product['name'] }}</span>.
            </p>
        </div>

        {{-- Alert / pesan (server side) --}}
        @if (session('success'))
            <div class="border px-4 py-3 rounded-lg bg-green-50 border-green-400 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="border px-4 py-3 rounded-lg bg-red-50 border-red-400 text-red-800 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-8 mt-4">
            {{-- Form checkout --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 space-y-6">

                    {{-- Info produk singkat --}}
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                        <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center text-green-700 text-xl">
                            🍓
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">
                                Pre-order Produk
                            </p>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $product['name'] }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Fresh dari Batu, stok terbatas per hari. Pilih paket & tanggal pengiriman di bawah.
                            </p>
                        </div>
                    </div>

                    <form id="checkout-form"
                          action="{{ route('checkout.store', $product['slug']) }}"
                          method="POST"
                          class="space-y-5">
                        @csrf

                        {{-- Product data untuk JS --}}
                        <input type="hidden" id="product-data" value='@json($product)'>

                        {{-- Product ID untuk backend --}}
                        <input type="hidden" id="product-id" name="product_id" value="{{ $product['id'] }}">

                        {{-- Tanggal pengiriman --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Pengiriman
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="delivery-date"
                                name="delivery_date"
                                value="{{ old('delivery_date') }}"
                                class="w-full border rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white"
                                placeholder="Pilih tanggal pengiriman"
                                autocomplete="off"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">
                                Pre-order H+1 sampai H+7 dari hari ini (tanggal yang bisa dipilih akan otomatis dibatasi).
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
                                            {{-- ID varian untuk backend (WAJIB) --}}
                                            <input
                                                type="hidden"
                                                name="variants[{{ $i }}][variant_id]"
                                                value="{{ $pkg['id'] }}"
                                            >

                                            <button
                                                type="button"
                                                class="px-2 py-1 border rounded-lg text-sm"
                                                onclick="changeVariantQty({{ $i }}, -1)"
                                            >
                                                -
                                            </button>

                                            <input
                                                type="number"
                                                id="variant-qty-{{ $i }}"
                                                name="variants[{{ $i }}][qty]"
                                                class="w-12 text-center border rounded-lg py-1 text-sm"
                                                value="{{ old("variants.$i.qty", 0) }}"
                                                min="0"
                                                max="99"
                                                oninput="onVariantQtyInput({{ $i }})"
                                            >

                                            <button
                                                type="button"
                                                class="px-2 py-1 border rounded-lg text-sm"
                                                onclick="changeVariantQty({{ $i }}, 1)"
                                            >
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


                        {{-- Data pemesan --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Lengkap
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="customer-name"
                                    name="customer_name"
                                    value="{{ old('customer_name') }}"
                                    class="w-full border rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white"
                                    placeholder="Nama penerima pesanan"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor WhatsApp
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="customer-whatsapp"
                                    name="customer_whatsapp"
                                    value="{{ old('customer_whatsapp') }}"
                                    class="w-full border rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white"
                                    placeholder="Contoh: 62812xxxxxxx"
                                    required
                                >
                                <p class="text-xs text-gray-500 mt-1">
                                    Pastikan nomor aktif karena update pesanan akan dikirim ke sini.
                                </p>
                            </div>
                        </div>

                        {{-- Alamat pengiriman --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Pengiriman (Kota Batu)
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="customer-address"
                                name="customer_address"
                                rows="3"
                                class="w-full border rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400
                                    focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                    bg-gray-100 cursor-not-allowed"
                                readonly
                            >{{ old('customer_address', 'RSUD Karsa Husada Batu') }}</textarea>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="payment-method"
                                name="payment_method"
                                class="w-full border rounded-lg px-3 py-2 text-sm text-gray-900 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateOrderSummary()"
                                required
                            >
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>
                                    Pilih metode pembayaran
                                </option>
                                <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>
                                    COD / Cash saat barang diterima
                                </option>
                                <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>
                                    QRIS (akan diarahkan ke pembayaran online)
                                </option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="w-full mt-2 inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700
                                   text-white text-sm font-semibold py-3 rounded-lg shadow-sm transition-colors"
                        >
                            Proses Pesanan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ringkasan pesanan (JS) --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Ringkasan Pesanan
                    </h3>

                    <div id="order-summary" class="text-sm text-gray-700">
                        {{-- Diisi oleh JS --}}
                        <p class="text-gray-500 text-sm">
                            Pilih tanggal dan isi minimal 1 pack pada salah satu paket untuk melihat ringkasan.
                        </p>
                    </div>

                    <div id="total-section" class="mt-4 space-y-2 hidden">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span id="subtotal-price">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-base font-semibold text-gray-900">
                            <span>Total</span>
                            <span id="total-price">Rp 0</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                        <p>✅ Pre-order hanya untuk area Kota Batu.</p>
                        <p>✅ Jadwal pengiriman akan dikonfirmasi via WhatsApp.</p>
                        <p>❗ Mohon pastikan data sudah benar sebelum melanjutkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- SweetAlert2 untuk validasi manis --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    {{-- Script checkout --}}
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
