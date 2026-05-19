<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">
    <title>@yield('title', 'NTS Batu - Fresh Strawberry & Berry')</title>

    {{-- Google Fonts: Instrument Sans & DM Serif Display --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
