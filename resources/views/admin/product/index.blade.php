<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Produk - NTS Batu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logos/logo.png">

    {{-- Font Onest --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS dashboard + datatable --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">

    {{-- JS: jQuery + datatable + custom --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    {{-- Nanti kalau mau dipisah, bisa buat js/products-index.js sendiri --}}
    <script src="{{ asset('js/product.js') }}" defer></script>

    <style>
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 500;
            border-radius: 0.45rem;
            border: 1px solid #e5e7eb;
            background-color: #111827;
            color: #f9fafb;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-secondary {
            background-color: #f9fafb;
            color: #374151;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            background-color: #111827;
            color: #f9fafb;
            border-color: #111827;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-primary:active,
        .btn-secondary:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        .product-image {
            width: 52px;
            height: 52px;
            border-radius: 0.75rem;
            object-fit: cover;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }

        .product-image-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 0.75rem;
            border: 1px dashed #d1d5db;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: #9ca3af;
            background-color: #f9fafb;
        }

        .product-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #111827;
        }

        .product-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .badge-status-active {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            background-color: #ecfdf3;
            color: #166534;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .badge-status-inactive {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            background-color: #fef2f2;
            color: #b91c1c;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .variant-list {
            font-size: 0.75rem;
            color: #4b5563;
            line-height: 1.4;
        }

        .variant-label {
            font-weight: 500;
        }

        /* Modal basic style */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.55);
            display: flex;                 /* selalu flex, tapi dikendalikan dengan opacity */
            align-items: center;
            justify-content: center;
            z-index: 50;

            opacity: 0;
            pointer-events: none;
            transition: opacity 0.18s ease-out;
        }

        .modal-backdrop.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-panel {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem 1.75rem;
            max-width: 820px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
        }

        .modal-close {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            cursor: pointer;
            color: #6b7280;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #374151;
        }

        .form-input,
        .form-textarea,
        .form-select {
            border-radius: 0.6rem;
            border: 1px solid #d1d5db;
            padding: 0.45rem 0.7rem;
            font-size: 0.85rem;
            color: #111827;
            font-family: inherit;
        }

        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #111827;
            box-shadow: 0 0 0 1px #11182711;
        }

        .variants-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .variants-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }

        .variants-subtitle {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .variant-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.25rem;
        }

        .variant-table th,
        .variant-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 0.4rem 0.4rem;
            font-size: 0.8rem;
            vertical-align: middle;
        }

        .variant-table th {
            font-weight: 600;
            color: #6b7280;
        }

        .variant-remove-btn {
            border: none;
            background-color: #fee2e2;
            color: #b91c1c;
            border-radius: 999px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            cursor: pointer;
        }

        .modal-footer {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="dashboard-body dashboard-body--products">
    {{-- Navbar sama seperti dashboard --}}
    <nav class="navbar">
        <div class="navbar-left">
            <span class="navbar-brand">Strawberry Order Admin</span>
        </div>
        <button class="navbar-toggle" id="navbarToggle">
            ☰
        </button>
        <div class="navbar-right" id="navbarMenu">
            <a href="{{ route('dashboard') }}" class="navbar-link">Dashboard</a>
            <a href="{{ route('products.index') }}" class="navbar-link active">Data Produk</a>
            <a href="{{ route('orders.index') }}" class="navbar-link ">Data Order</a>
            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="navbar-link navbar-logout">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="main-container main-container-products">
        <header class="page-header">
            <div>
                <h1 class="page-title">Data Produk</h1>
                <p class="page-subtitle">
                    Kelola daftar produk strawberry & berry yang tampil di landing page.
                </p>
            </div>

            <div>
                <button type="button" class="btn-primary" id="btnOpenCreateModal">
                    + Tambah Produk
                </button>
            </div>
        </header>

        {{-- Filter & Search sederhana --}}
        <section class="section-card" style="margin-bottom: 20px;">
            <form method="GET" action="{{ route('products.index') }}" class="filter-form">
                <div class="filter-grid">
                    {{-- Search --}}
                    <div class="filter-group">
                        <label class="filter-label">Cari</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nama produk atau slug..."
                            class="filter-input"
                        >
                    </div>

                    {{-- Status --}}
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select name="status" class="filter-input">
                            <option value="all" @selected(request('status', 'all') === 'all')>Semua</option>
                            <option value="active" @selected(request('status') === 'active')>Aktif</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="filter-actions">
                        <button type="submit" class="btn-outline" style="width: 100%;">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('products.index') }}" class="btn-outline" style="margin-top: 6px; width: 100%; text-align:center;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </section>

        {{-- Tabel Produk --}}
        <section class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    {{ $products->count() }} Produk
                </h2>
            </div>

            <div class="table-wrapper">
                <table class="table" id="productsTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga (range)</th>
                            <th>Varian</th>
                            <th>Status</th>
                            <th>Diupdate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $variants = $product->variants ?? collect();
                                $minPrice = $variants->min('price');
                                $maxPrice = $variants->max('price');
                            @endphp
                            <tr>
                                {{-- Produk + gambar --}}
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.7rem;">
                                        @if (!empty($product->image))
                                            <img
                                                src="{{ asset('images/products/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="product-image"
                                            >
                                        @else
                                            <div class="product-image-placeholder">
                                                No Image
                                            </div>
                                        @endif
                                        <div>
                                            <div class="product-name">{{ $product->name }}</div>
                                            @if($product->slug)
                                                <div class="product-meta">/{{ $product->slug }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Harga range dari variant --}}
                                <td>
                                    @if($variants->count() > 0)
                                        <span class="product-meta">
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @if($minPrice != $maxPrice)
                                                - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="product-meta">Belum ada harga</span>
                                    @endif
                                </td>

                                {{-- Varian --}}
                                <td>
                                    @if($variants->count() > 0)
                                        <div class="variant-list">
                                            @foreach($variants->take(3) as $variant)
                                                <div>
                                                    <span class="variant-label">{{ $variant->name ?? $variant->label ?? 'Varian' }}</span>
                                                    @if($variant->price)
                                                        · Rp {{ number_format($variant->price, 0, ',', '.') }}
                                                    @endif
                                                </div>
                                            @endforeach

                                            @if($variants->count() > 3)
                                                <div class="product-meta">
                                                    + {{ $variants->count() - 3 }} varian lain
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="product-meta">-</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($product->is_active)
                                        <span class="badge-status-active">Aktif</span>
                                    @else
                                        <span class="badge-status-inactive">Nonaktif</span>
                                    @endif
                                </td>

                                {{-- Updated at --}}
                                <td>
                                    <span class="product-meta">
                                        {{ optional($product->updated_at)->format('d M Y H:i') ?? '-' }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td>
                                    <div class="action-buttons">
                                        {{-- Edit informasi produk --}}
                                        <button type="button"
                                            class="btn-secondary btn-edit-product"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-slug="{{ $product->slug }}"
                                            data-description="{{ $product->description }}"
                                            data-is-active="{{ $product->is_active ? 1 : 0 }}"
                                            data-image="{{ $product->image }}"
                                            data-update-url="{{ route('products.update', $product) }}"
                                        >
                                            Edit
                                        </button>

                                        @php
                                            $variantJson = $product->variants->map(function($variant) {
                                                return [
                                                    'id'        => $variant->id,
                                                    'name'      => $variant->name ?? $variant->label ?? 'Varian',
                                                    'price'     => $variant->price,
                                                    'is_active' => (bool) $variant->is_active,
                                                ];
                                            });
                                        @endphp

                                        {{-- Edit harga / varian --}}
                                        <button type="button"
                                            class="btn-secondary btn-edit-price"
                                            data-update-url="{{ route('products.updatePrices', $product) }}"
                                            data-variants='@json($variantJson)'
                                        >
                                            Harga
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; font-size:0.9rem; color:#6b7280;">
                                    Belum ada produk. Klik "Tambah Produk" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    {{-- Modal Tambah Produk + Variant --}}
    <div class="modal-backdrop" id="productModal">
        <div class="modal-panel">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Produk Baru</h2>
                <button type="button" class="modal-close" data-close-modal>&times;</button>
            </div>

            {{-- <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"> --}}
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    {{-- Informasi Produk --}}
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="name">Nama Produk</label>
                            <input
                                type="text"
                                class="form-input"
                                id="name"
                                name="name"
                                placeholder="Contoh: Strawberry Fresh Pack"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">Slug (opsional)</label>
                            <input
                                type="text"
                                class="form-input"
                                id="slug"
                                name="slug"
                                placeholder="strawberry-fresh-pack"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="is_active">Status</label>
                            <select name="is_active" id="is_active" class="form-select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="image">Gambar Produk</label>
                            <input
                                type="file"
                                class="form-input"
                                id="image"
                                name="image"
                                accept="image/*"
                            >
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label" for="description">Deskripsi (opsional)</label>
                            <textarea
                                id="description"
                                name="description"
                                class="form-textarea"
                                placeholder="Tuliskan deskripsi singkat, misal ukuran pack, rasa, catatan kualitas, dsb."></textarea>
                        </div>
                    </div>

                    {{-- Varian Produk --}}
                    <div>
                        <div class="variants-header">
                            <div>
                                <div class="variants-title">Varian Produk</div>
                                <div class="variants-subtitle">
                                    Misal: Pack kecil, pack besar, 2 pack besar, dll.
                                </div>
                            </div>

                            <button type="button" class="btn-secondary" id="btnAddVariant">
                                + Tambah Varian
                            </button>
                        </div>

                        <table class="variant-table" id="variantTable">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Nama / Label</th>
                                    <th style="width: 20%;">Harga (Rp)</th>
                                    <th style="width: 20%;">Qty per Pack</th>
                                    <th style="width: 20%;">Aktif</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1 baris default --}}
                                <tr>
                                    <td>
                                        <input
                                            type="text"
                                            name="variants[0][name]"
                                            class="form-input"
                                            placeholder="Contoh: Pack kecil"
                                            required
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="variants[0][price]"
                                            class="form-input"
                                            min="0"
                                            step="1000"
                                            placeholder="35000"
                                            required
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="variants[0][qty_per_pack]"
                                            class="form-input"
                                            min="1"
                                            placeholder="Misal: 1"
                                        >
                                    </td>
                                    <td>
                                        <select name="variants[0][is_active]" class="form-select">
                                            <option value="1" selected>Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </td>
                                    <td style="text-align:center;">
                                        {{-- baris pertama tidak bisa dihapus --}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Produk --}}
    <div class="modal-backdrop" id="productEditModal">
        <div class="modal-panel">
            <div class="modal-header">
                <h2 class="modal-title">Edit Produk</h2>
                <button type="button" class="modal-close" data-close-modal>&times;</button>
            </div>

            <form method="POST" id="productEditForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="edit_name">Nama Produk</label>
                            <input
                                type="text"
                                class="form-input"
                                id="edit_name"
                                name="name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="edit_slug">Slug (opsional)</label>
                            <input
                                type="text"
                                class="form-input"
                                id="edit_slug"
                                name="slug"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="edit_is_active">Status</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="edit_image">Gambar Produk (opsional)</label>
                            <input
                                type="file"
                                class="form-input"
                                id="edit_image"
                                name="image"
                                accept="image/*"
                            >
                            <div style="margin-top: 6px; display:flex; align-items:center; gap:0.5rem;">
                                <img id="editImagePreview"
                                    src=""
                                    alt="Preview"
                                    class="product-image"
                                    style="display:none;">
                                <span id="editImageInfo" class="product-meta"></span>
                            </div>
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label" for="edit_description">Deskripsi</label>
                            <textarea
                                id="edit_description"
                                name="description"
                                class="form-textarea"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Harga Varian --}}
    <div class="modal-backdrop" id="priceEditModal">
        <div class="modal-panel">
            <div class="modal-header">
                <h2 class="modal-title">Edit Harga Varian</h2>
                <button type="button" class="modal-close" data-close-modal>&times;</button>
            </div>

            <form method="POST" id="priceEditForm">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <p class="product-meta" id="priceEditProductInfo" style="margin-bottom: 0.5rem;">
                        {{-- nama produk bisa diisi via JS nanti kalau mau --}}
                    </p>

                    <table class="variant-table" id="priceVariantTable">
                        <thead>
                            <tr>
                                <th>Varian</th>
                                <th style="width: 35%;">Harga (Rp)</th>
                                <th style="width: 20%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Diisi dinamis via JS --}}
                        </tbody>
                    </table>

                    <p class="product-meta" style="margin-top: 0.5rem;">
                        Ubah harga lalu klik "Simpan Perubahan". Perubahan berlaku untuk produk ini saja.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


</body>
</html>
