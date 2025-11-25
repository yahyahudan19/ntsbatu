<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - NTS Batu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">


    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS custom --}}
    <link rel="stylesheet" href="{{ asset('css/nts.css') }}">

    {{-- Sedikit helper layout --}}
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
            padding: 16px;
        }
        .auth-card {
            max-width: 420px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            padding: 32px 28px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.16);
        }
        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        .auth-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 1.75rem;
        }
        .auth-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            overflow: hidden;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .auth-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.35rem;
        }
        .form-input {
            width: 100%;
            border-radius: 0.6rem;
            border: 1px solid #d1d5db;
            padding: 0.7rem 0.85rem;
            font-size: 0.95rem;
            color: #111827;
        }
        .form-input::placeholder {
            color: #9ca3af;
        }
        .form-footer {
            margin-top: 1.25rem;
        }
        .link-muted {
            color: #6b7280;
            font-size: 0.85rem;
        }
        .link-primary {
            color: #16a34a;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
        }
        .link-primary:hover {
            text-decoration: underline;
        }
        .text-danger {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 0.2rem;
        }
        .flex-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .checkbox-inline {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            color: #374151;
        }
        .checkbox-inline input[type="checkbox"] {
            width: 14px;
            height: 14px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 0.6rem;
            border: none;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
        }
        .text-center {
            text-align: center;
        }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 0.75rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
    </style>
</head>
<body>

<div class="auth-page">
    <div class="auth-card fade-in">

        {{-- Logo + Title --}}
        <div class="auth-logo">
            <img src="/images/logos/logo.png" alt="NTS Batu">
        </div>
        <h1 class="auth-title">Selamat Datang Kembali</h1>
        <p class="auth-subtitle">
            Masuk untuk mengelola pesanan dan pre-order strawberry segar Anda.
        </p>

        {{-- Session status / flash message --}}
        @if (session('status'))
            <div class="alert-box alert-info" style="display:block;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Error global (validation) --}}
        @if ($errors->any())
            <div class="alert-box alert-error" style="display:block;">
                <strong>Wah Maaf</strong><br>
                <span>Lengkapi data di bawah dengan benar untuk melanjutkan.</span>
            </div>
        @endif

        {{-- Form login --}}
        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input"
                    placeholder="email@example.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                >
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember + forgot --}}
            <div class="flex-between mt-2">
                <label class="checkbox-inline">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>

                {{-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-primary">
                        Lupa password?
                    </a>
                @endif --}}
            </div>

            {{-- Tombol submit --}}
            <div class="form-footer mt-4">
                <button type="submit" class="btn-primary">
                    Masuk
                </button>
            </div>
        </form>

        {{-- Link register --}}
        @if (Route::has('register'))
            <div class="text-center mt-4">
                <span class="link-muted">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="link-primary">Daftar sekarang</a>
            </div>
        @endif
    </div>
</div>

</body>
</html>
