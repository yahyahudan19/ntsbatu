<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">
    <title>@yield('title', 'NTS Batu - Fresh Strawberry & Berry')</title>

    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (boleh diganti Vite nanti) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- CSS custom --}}
    <link rel="stylesheet" href="{{ asset('css/nts.css') }}">

    <style>
        html, body {
            font-family: "Onest", system-ui, sans-serif !important;
        }
    </style>
</head>
<body class="min-h-screen">

    {{-- Header --}}
    @include('partials.header')

    {{-- Konten --}}
    @yield('content')

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Modal global (QRIS, SweetAlert custom) --}}
    @include('partials.modals.qris')
    @include('partials.modals.sweet-modal')

    @stack('scripts')
</body>
</html>
