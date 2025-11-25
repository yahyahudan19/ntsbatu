<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Terjadi Kesalahan') - NTS Batu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicon (sesuaikan kalau beda) --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">
    

    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (bisa diganti Vite di produksi) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Onest', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-emerald-50 to-white flex flex-col">
    {{-- Header simple --}}
    <header class="border-b bg-white/80 backdrop-blur sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="/images/logos/logo.png" alt="NTS Batu" class="h-9 w-9 object-contain">
                <div class="flex flex-col leading-tight">
                    <span class="text-sm font-semibold text-gray-900">NTS Batu</span>
                    <span class="text-[11px] text-gray-500">Fresh Strawberry & Berry</span>
                </div>
            </a>

            <a href="{{ url('/') }}" class="hidden sm:inline-flex items-center text-xs font-medium text-emerald-700 hover:text-emerald-900 transition">
                Kembali ke Beranda
            </a>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="max-w-xl w-full">
            <div class="bg-white/90 backdrop-blur shadow-lg shadow-emerald-100 rounded-3xl px-6 sm:px-10 py-10 sm:py-12 relative overflow-hidden">

                {{-- Badge kode error --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50/80 px-3 py-1 mb-5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] uppercase tracking-[0.16em] font-semibold text-emerald-700">
                        Error @yield('code', 'Error')
                    </span>
                </div>

                {{-- Title & message --}}
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 mb-3">
                    @yield('headline', 'Wah, ada yang salah')
                </h1>

                <p class="text-sm sm:text-base text-gray-600 mb-6 leading-relaxed">
                    @yield('message', 'Terjadi kesalahan saat memproses permintaanmu. Silakan coba beberapa saat lagi.')
                </p>

                {{-- Detail / description --}}
                @hasSection('description')
                    <div class="text-xs sm:text-sm text-gray-500 mb-6">
                        @yield('description')
                    </div>
                @endif

                {{-- Action buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-2">
                    <a href="{{ url('/') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition shadow-md shadow-emerald-200">
                        <span>Ke Beranda</span>
                    </a>

                    <button type="button"
                            onclick="window.history.back()"
                            class="inline-flex items-center justify-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 active:bg-emerald-200 transition border border-emerald-100">
                        <span>Kembali ke halaman sebelumnya</span>
                    </button>
                </div>

                {{-- Info kecil --}}
                <p class="mt-6 text-[11px] text-gray-400">
                    Kode error: @yield('code', '—')
                </p>

                {{-- Accent dekor --}}
                <div class="pointer-events-none absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-emerald-100/60 blur-3xl"></div>
            </div>
        </div>
    </main>

    {{-- Footer kecil --}}
    <footer class="py-4 text-center text-[11px] text-gray-400">
        &copy; {{ date('Y') }} NTS Batu. Fresh Strawberry & Berry.
    </footer>
</body>
</html>
