@extends('layouts.app')

@section('title', 'Checkout - ' . $product['name'])

@section('content')
<div class="min-h-full bg-gradient-to-b from-zinc-50 to-white pt-28 md:pt-32 pb-16 px-4" data-page="checkout">
    <div class="max-w-5xl mx-auto space-y-8">

        {{-- Judul --}}
        <div>
            <div class="inline-flex items-center gap-2 mb-4 px-4 py-1.5 bg-brand-red/10 text-brand-red rounded-full text-sm font-bold">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 9.4l-9-5.19"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                Pre-order
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 font-display tracking-tight">
                Checkout Pesanan
            </h2>
            <p class="mt-3 text-zinc-500 text-base md:text-lg">
                Lengkapi data di bawah untuk menyelesaikan pre-order
                <span class="font-semibold text-zinc-900">{{ $product['name'] }}</span>.
            </p>
        </div>

        {{-- Alert / pesan (server side) --}}
        @if (session('success'))
            <div class="px-5 py-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="px-5 py-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm">
                <div class="flex items-center gap-2 mb-2 font-semibold">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Terjadi kesalahan
                </div>
                <ul class="list-disc list-inside space-y-1 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-8">
            {{-- Form checkout --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-10 space-y-8 border border-zinc-100">

                    {{-- Info produk singkat --}}
                    <div class="flex items-start gap-4 pb-6 border-b border-zinc-100">
                        <div class="h-14 w-14 rounded-2xl bg-brand-red/10 flex items-center justify-center shrink-0">
                            <svg class="w-7 h-7 text-brand-red" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 9.4l-9-5.19"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-zinc-400 font-bold mb-1">
                                Pre-order Produk
                            </p>
                            <h3 class="text-xl font-bold text-zinc-900 font-display">
                                {{ $product['name'] }}
                            </h3>
                            <p class="text-sm text-zinc-500 mt-1">
                                Fresh dari Batu, stok terbatas per hari. Pilih paket & tanggal pengiriman di bawah.
                            </p>
                        </div>
                    </div>

                    <form id="checkout-form"
                          action="{{ route('checkout.store', $product['slug']) }}"
                          method="POST"
                          class="space-y-7">
                        @csrf

                        {{-- Product data untuk JS --}}
                        <input type="hidden" id="product-data" value='@json($product)'>

                        {{-- Product ID untuk backend --}}
                        <input type="hidden" id="product-id" name="product_id" value="{{ $product['id'] }}">

                        {{-- Tanggal pengiriman --}}
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Tanggal Pengiriman
                                    <span class="text-brand-red">*</span>
                                </span>
                            </label>
                            <input
                                type="text"
                                id="delivery-date"
                                name="delivery_date"
                                value="{{ old('delivery_date') }}"
                                class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm text-zinc-900 placeholder:text-zinc-400
                                       focus:outline-none focus:ring-2 focus:ring-brand-red/30 focus:border-brand-red bg-white transition-all"
                                placeholder="Pilih tanggal pengiriman"
                                autocomplete="off"
                                required
                            >
                            <p class="text-xs text-zinc-400 mt-2">
                                Pre-order H+1 sampai H+7 dari hari ini (tanggal yang bisa dipilih akan otomatis dibatasi).
                            </p>
                        </div>

                        {{-- Paket/varian --}}
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 mb-3">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 9.4l-9-5.19"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                    Pilih Paket & Jumlah
                                    <span class="text-brand-red">*</span>
                                </span>
                            </label>

                            <div class="space-y-3" id="package-list">
                                @foreach($product['packages'] as $i => $pkg)
                                    <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-2xl border border-zinc-100 hover:border-brand-red/20 hover:bg-red-50/30 transition-all">
                                        <div>
                                            <div class="font-bold text-zinc-900">{{ $pkg['label'] }}</div>
                                            <div class="text-sm text-zinc-500 mt-0.5">
                                                Rp {{ number_format($pkg['price'], 0, ',', '.') }} / pack
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-1.5">
                                            {{-- ID varian untuk backend (WAJIB) --}}
                                            <input
                                                type="hidden"
                                                name="variants[{{ $i }}][variant_id]"
                                                value="{{ $pkg['id'] }}"
                                            >

                                            <button
                                                type="button"
                                                class="w-9 h-9 flex items-center justify-center border border-zinc-200 rounded-xl text-zinc-500 hover:bg-brand-red hover:text-white hover:border-brand-red transition-all font-bold text-sm"
                                                onclick="changeVariantQty({{ $i }}, -1)"
                                            >
                                                −
                                            </button>

                                            <input
                                                type="number"
                                                id="variant-qty-{{ $i }}"
                                                name="variants[{{ $i }}][qty]"
                                                class="w-12 text-center border border-zinc-200 rounded-xl py-2 text-sm font-bold text-zinc-900 focus:outline-none focus:ring-2 focus:ring-brand-red/30 focus:border-brand-red"
                                                value="{{ old("variants.$i.qty", 0) }}"
                                                min="0"
                                                max="99"
                                                oninput="onVariantQtyInput({{ $i }})"
                                            >

                                            <button
                                                type="button"
                                                class="w-9 h-9 flex items-center justify-center border border-zinc-200 rounded-xl text-zinc-500 hover:bg-brand-red hover:text-white hover:border-brand-red transition-all font-bold text-sm"
                                                onclick="changeVariantQty({{ $i }}, 1)"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <p class="text-xs text-zinc-400 mt-3">
                                Kamu bisa mengkombinasikan beberapa paket sekaligus. Biarkan 0 jika tidak ingin paket tersebut.
                            </p>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-zinc-100"></div>

                        {{-- Data pemesan --}}
                        <div>
                            <h4 class="text-sm font-bold text-zinc-700 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a8.38 8.38 0 0 1 13 0"/></svg>
                                Data Pemesan
                            </h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-600 mb-1.5">
                                        Nama Lengkap
                                        <span class="text-brand-red">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="customer-name"
                                        name="customer_name"
                                        value="{{ old('customer_name') }}"
                                        class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm text-zinc-900 placeholder:text-zinc-400
                                               focus:outline-none focus:ring-2 focus:ring-brand-red/30 focus:border-brand-red bg-white transition-all"
                                        placeholder="Nama penerima pesanan"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-600 mb-1.5">
                                        Nomor WhatsApp
                                        <span class="text-brand-red">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="customer-whatsapp"
                                        name="customer_whatsapp"
                                        value="{{ old('customer_whatsapp') }}"
                                        class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm text-zinc-900 placeholder:text-zinc-400
                                               focus:outline-none focus:ring-2 focus:ring-brand-red/30 focus:border-brand-red bg-white transition-all"
                                        placeholder="Contoh: 62812xxxxxxx"
                                        required
                                    >
                                    <p class="text-xs text-zinc-400 mt-1.5">
                                        Pastikan nomor aktif karena update pesanan akan dikirim ke sini.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Alamat pengiriman --}}
                        <div>
                            <label class="block text-sm font-medium text-zinc-600 mb-1.5">
                                Alamat Pengiriman (Kota Batu)
                                <span class="text-brand-red">*</span>
                            </label>
                            <textarea
                                id="customer-address"
                                name="customer_address"
                                rows="3"
                                class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm text-zinc-900 placeholder:text-zinc-400
                                    focus:outline-none focus:ring-2 focus:ring-brand-red/30 focus:border-brand-red
                                    bg-zinc-50 cursor-not-allowed transition-all"
                                readonly
                            >{{ old('customer_address', 'RSUD Karsa Husada Batu') }}</textarea>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 mb-3">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    Metode Pembayaran
                                    <span class="text-brand-red">*</span>
                                </span>
                            </label>

                            <select
                                id="payment-method"
                                name="payment_method"
                                class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm text-zinc-900 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-brand-red/30 focus:border-brand-red transition-all appearance-none"
                                onchange="updateOrderSummary()"
                                required
                                style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; padding-right: 2.5rem;"
                            >
                                <option value="qris" selected>
                                    QRIS (akan diarahkan ke pembayaran online)
                                </option>
                                {{-- 
                                <option value="cod">
                                    COD / Cash saat barang diterima
                                </option>
                                --}}
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="w-full mt-2 inline-flex items-center justify-center gap-2 bg-brand-red hover:bg-brand-dark-red
                                   text-white font-bold py-4 rounded-xl shadow-xl shadow-red-100 hover:shadow-red-200
                                   transition-all transform active:scale-[0.98] text-base"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Proses Pesanan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ringkasan pesanan (JS) --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 space-y-5 border border-zinc-100 md:sticky md:top-28">
                    <h3 class="text-lg font-bold text-zinc-900 font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-red" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="15" y2="16"/></svg>
                        Ringkasan Pesanan
                    </h3>

                    <div id="order-summary" class="text-sm text-zinc-700">
                        {{-- Diisi oleh JS --}}
                        <p class="text-zinc-400 text-sm">
                            Pilih tanggal dan isi minimal 1 pack pada salah satu paket untuk melihat ringkasan.
                        </p>
                    </div>

                    <div id="total-section" class="mt-4 space-y-3 hidden">
                        <div class="flex justify-between text-sm text-zinc-500">
                            <span>Subtotal</span>
                            <span id="subtotal-price">Rp 0</span>
                        </div>
                        <div class="h-px bg-zinc-100"></div>
                        <div class="flex justify-between text-lg font-bold text-zinc-900">
                            <span>Total</span>
                            <span id="total-price" class="text-brand-dark-red">Rp 0</span>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-zinc-100 space-y-3">
                        <div class="flex items-start gap-2.5 text-xs text-zinc-500">
                            <svg class="w-4 h-4 text-brand-green shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span>Pre-order hanya untuk area Kota Batu.</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-zinc-500">
                            <svg class="w-4 h-4 text-brand-green shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span>Jadwal pengiriman akan dikonfirmasi via WhatsApp.</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-zinc-500">
                            <svg class="w-4 h-4 text-yellow-500 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>Mohon pastikan data sudah benar sebelum melanjutkan.</span>
                        </div>
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
