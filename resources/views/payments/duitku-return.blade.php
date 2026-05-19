@extends('layouts.app')

@section('title', 'Status Pembayaran')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-zinc-50 to-white pt-28 md:pt-36 pb-16 px-4" data-page="payment-return">
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-zinc-100 p-8 md:p-12 text-center space-y-6">

            {{-- Icon berdasarkan status --}}
            @if(($statusType ?? 'pending') === 'success')
                <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
            @elseif(($statusType ?? 'pending') === 'failed')
                <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
            @else
                <div class="mx-auto w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            @endif

            {{-- Heading --}}
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-zinc-900 font-display tracking-tight">
                    @if(($statusType ?? 'pending') === 'success')
                        Pembayaran Berhasil! 🎉
                    @elseif(($statusType ?? 'pending') === 'failed')
                        Pembayaran Gagal
                    @else
                        Menunggu Pembayaran
                    @endif
                </h1>
                <p class="mt-3 text-zinc-500 text-sm md:text-base leading-relaxed">
                    {{ $statusMessage ?? 'Silakan cek status pembayaran kamu.' }}
                </p>
            </div>

            {{-- Info cards --}}
            <div class="space-y-3 text-left">
                @if(($statusType ?? 'pending') === 'success')
                    <div class="flex items-start gap-3 p-4 bg-green-50 rounded-2xl">
                        <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Pembayaran Terverifikasi</p>
                            <p class="text-xs text-green-600 mt-0.5">Pesanan kamu sedang diproses dan akan segera disiapkan.</p>
                        </div>
                    </div>
                @endif

                <div class="flex items-start gap-3 p-4 bg-zinc-50 rounded-2xl">
                    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-zinc-800">Konfirmasi via WhatsApp</p>
                        <p class="text-xs text-zinc-500 mt-0.5">Notifikasi pesanan dan jadwal pengiriman akan dikirim ke WhatsApp kamu.</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="pt-4 space-y-3">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center justify-center gap-2 w-full px-6 py-4 bg-brand-red hover:bg-brand-dark-red text-white font-bold rounded-xl shadow-xl shadow-red-100 hover:shadow-red-200 transition-all transform active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
