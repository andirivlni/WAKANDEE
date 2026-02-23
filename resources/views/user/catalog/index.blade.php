@extends('layouts.app')

@section('title', 'Katalog - WAKANDE')

@section('content')
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-4 py-2 mb-3">
                <i class="bi bi-grid me-2"></i>KATALOG
            </span>
            <h1 class="display-5 fw-bold mb-3">Temukan Kebutuhan Akademikmu</h1>
            <p class="text-secondary" style="max-width: 600px; margin: 0 auto;">
                Dari buku bekas berkualitas hingga alat praktikum, semua bisa kamu dapatkan secara gratis atau harga
                terjangkau
            </p>
        </div>

        <div class="glass-card p-4 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg-6">
                    <form action="{{ route('catalog.index') }}" method="GET" id="searchForm">
                        <div class="search-box position-relative">
                            <i
                                class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                            <input type="text" name="search"
                                class="form-control form-control-lg rounded-pill border-0 shadow-none"
                                style="padding-left: 45px; background: rgba(34, 197, 94,0.02);"
                                placeholder="Cari buku, seragam, alat praktikum..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <button class="btn btn-outline-secondary rounded-pill px-4 py-2" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#filterCanvas">
                            <i class="bi bi-sliders2 me-2"></i>Filter
                            @if (request()->hasAny(['category', 'type', 'condition', 'school', 'min_price', 'max_price']))
                                <span class="badge bg-success ms-2 rounded-pill">•</span>
                            @endif
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary rounded-pill px-4 py-2 dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-arrow-up-short me-2"></i>Urutkan
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-4 border-0 shadow-lg p-2">
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 {{ request('sort') == 'terbaru' ? 'active' : '' }}"
                                        href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'terbaru'])) }}">
                                        <i class="bi bi-clock me-2"></i>Terbaru
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 {{ request('sort') == 'populer' ? 'active' : '' }}"
                                        href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'populer'])) }}">
                                        <i class="bi bi-fire me-2"></i>Populer
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 {{ request('sort') == 'termurah' ? 'active' : '' }}"
                                        href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'termurah'])) }}">
                                        <i class="bi bi-arrow-up me-2"></i>Harga Terendah
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 {{ request('sort') == 'termahal' ? 'active' : '' }}"
                                        href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'termahal'])) }}">
                                        <i class="bi bi-arrow-down me-2"></i>Harga Tertinggi
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-outline-secondary rounded-pill px-3 py-2 view-toggle active"
                                data-view="grid">
                                <i class="bi bi-grid-3x3-gap-fill"></i>
                            </button>
                            <button class="btn btn-outline-secondary rounded-pill px-3 py-2 view-toggle" data-view="list">
                                <i class="bi bi-list-ul"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (request()->hasAny(['category', 'type', 'condition', 'school', 'min_price', 'max_price']))
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="text-secondary small">Filter aktif:</span>

                @if (request('category'))
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
                        Kategori: {{ ucfirst(request('category')) }}
                        <a href="{{ route('catalog.index', array_merge(request()->except('category'), ['page' => null])) }}"
                            class="text-decoration-none text-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    </span>
                @endif

                @if (request('type'))
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
                        Tipe: {{ request('type') == 'gift' ? 'Gratis' : 'Dijual' }}
                        <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['page' => null])) }}"
                            class="text-decoration-none text-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    </span>
                @endif

                @if (request('condition'))
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
                        Kondisi: {{ ucfirst(str_replace('_', ' ', request('condition'))) }}
                        <a href="{{ route('catalog.index', array_merge(request()->except('condition'), ['page' => null])) }}"
                            class="text-decoration-none text-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    </span>
                @endif

                @if (request('min_price') || request('max_price'))
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
                        Harga: Rp {{ number_format(request('min_price', 0)) }} - Rp
                        {{ number_format(request('max_price', '∞')) }}
                        <a href="{{ route('catalog.index', array_merge(request()->except(['min_price', 'max_price']), ['page' => null])) }}"
                            class="text-decoration-none text-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    </span>
                @endif

                @if (request('school'))
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
                        Sekolah: {{ request('school') }}
                        <a href="{{ route('catalog.index', array_merge(request()->except('school'), ['page' => null])) }}"
                            class="text-decoration-none text-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    </span>
                @endif

                <a href="{{ route('catalog.index') }}" class="text-decoration-none small ms-2" style="color: #22c55e;">
                    <i class="bi bi-x-circle me-1"></i>Hapus semua
                </a>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-secondary mb-0">
                <span class="fw-bold text-dark">{{ $items->total() }}</span> barang ditemukan
            </p>
        </div>

        @if ($items->count() > 0)
            <div id="gridView" class="row g-4">
                @foreach ($items as $item)
                    @php
                        $item->is_wishlisted = in_array($item->id, $wishlist_ids ?? []);
                    @endphp
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <x-item-card :item="$item" />
                    </div>
                @endforeach
            </div>

            <div id="listView" class="vstack gap-3" style="display: none;">
                @foreach ($items as $item)
                    <div class="list-item-card p-3 rounded-4">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2 col-4">
                                @php
                                    // PERBAIKAN: Mengecek apakah images sudah array (Eloquent Cast) atau masih string JSON
                                    $images = is_array($item->images)
                                        ? $item->images
                                        : (is_string($item->images)
                                            ? json_decode($item->images, true)
                                            : []);
                                    $images = $images ?? [];
                                    $firstImage =
                                        !empty($images) && isset($images[0])
                                            ? Storage::url($images[0])
                                            : asset('images/default-item.png');
                                @endphp
                                <div class="rounded-3 overflow-hidden" style="aspect-ratio: 1;">
                                    <img src="{{ $firstImage }}" alt="{{ $item->name }}" class="w-100 h-100"
                                        style="object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-7 col-8">
                                <div class="d-flex flex-column h-100">
                                    <div class="d-flex gap-2 mb-2">
                                        @if ($item->type == 'gift')
                                            <span class="badge bg-success rounded-pill px-3 py-1">Gratis</span>
                                        @else
                                            <span class="badge bg-success rounded-pill px-3 py-1">Dijual</span>
                                        @endif
                                        <span
                                            class="badge bg-light text-dark rounded-pill px-3 py-1">{{ $item->category_label }}</span>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $item->name }}</h6>
                                    <p class="small text-secondary mb-2">{{ Str::limit($item->legacy_message, 100) }}</p>
                                    <div class="d-flex align-items-center gap-3 small text-secondary">
                                        <span><i class="bi bi-person-circle me-1"></i>{{ $item->user->name }}</span>
                                        <span><i
                                                class="bi bi-building me-1"></i>{{ $item->user->school ?? 'Sekolah' }}</span>
                                        <span><i class="bi bi-eye me-1"></i>{{ $item->views_count }}x</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-md-end">
                                    @if ($item->type == 'sale')
                                        <span class="h5 fw-bold d-block mb-1" style="color: #22c55e;">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</span>
                                        <small class="text-secondary d-block mb-2">+ admin Rp1.000</small>
                                    @else
                                        <span class="h5 fw-bold text-success d-block mb-2">Gratis</span>
                                    @endif
                                    <a href="{{ route('catalog.show', $item->id) }}"
                                        class="btn btn-sm btn-outline-success rounded-pill px-4">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $items->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-search fs-1 text-secondary opacity-25"></i>
                </div>
                <h5 class="fw-bold mb-3">Barang Tidak Ditemukan</h5>
                <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">
                    Maaf, tidak ada barang yang sesuai dengan kriteria pencarianmu. Coba kata kunci lain atau hapus filter.
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('catalog.index') }}" class="btn btn-success btn-rounded px-5 py-3">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset Filter
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                            <i class="bi bi-gift me-2"></i>Mulai Berbagi
                        </a>
                    @endguest
                </div>
            </div>
        @endif
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterCanvas" aria-labelledby="filterCanvasLabel">
        <div class="offcanvas-header p-4">
            <h5 class="offcanvas-title fw-bold" id="filterCanvasLabel">
                <i class="bi bi-sliders2 me-2"></i>Filter
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4 pt-0">
            <form action="{{ route('catalog.index') }}" method="GET" id="filterForm">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if (request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <div class="filter-section mb-4">
                    <h6 class="fw-semibold mb-3">Kategori</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="categoryAll"
                                value="" {{ !request('category') ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoryAll">Semua</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="categoryBuku"
                                value="buku" {{ request('category') == 'buku' ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoryBuku">📚 Buku</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="categorySeragam"
                                value="seragam" {{ request('category') == 'seragam' ? 'checked' : '' }}>
                            <label class="form-check-label" for="categorySeragam">👕 Seragam</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="categoryAlatPraktikum"
                                value="alat_praktikum" {{ request('category') == 'alat_praktikum' ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoryAlatPraktikum">🔬 Alat Praktikum</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="categoryLainnya"
                                value="lainnya" {{ request('category') == 'lainnya' ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoryLainnya">📦 Lainnya</label>
                        </div>
                    </div>
                </div>

                <hr class="opacity-25">

                <div class="filter-section mb-4">
                    <h6 class="fw-semibold mb-3">Tipe Barang</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeAll" value=""
                                {{ !request('type') ? 'checked' : '' }}>
                            <label class="form-check-label" for="typeAll">Semua</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeGift" value="gift"
                                {{ request('type') == 'gift' ? 'checked' : '' }}>
                            <label class="form-check-label" for="typeGift">🎁 Gratis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeSale" value="sale"
                                {{ request('type') == 'sale' ? 'checked' : '' }}>
                            <label class="form-check-label" for="typeSale">💰 Dijual</label>
                        </div>
                    </div>
                </div>

                <hr class="opacity-25">

                <div class="filter-section mb-4">
                    <h6 class="fw-semibold mb-3">Kondisi Barang</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="conditionAll"
                                value="" {{ !request('condition') ? 'checked' : '' }}>
                            <label class="form-check-label" for="conditionAll">Semua</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="conditionBaru"
                                value="baru" {{ request('condition') == 'baru' ? 'checked' : '' }}>
                            <label class="form-check-label" for="conditionBaru">🆕 Baru</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="conditionSangatBaik"
                                value="sangat_baik" {{ request('condition') == 'sangat_baik' ? 'checked' : '' }}>
                            <label class="form-check-label" for="conditionSangatBaik">✨ Sangat Baik</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="conditionBaik"
                                value="baik" {{ request('condition') == 'baik' ? 'checked' : '' }}>
                            <label class="form-check-label" for="conditionBaik">👍 Baik</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="conditionCukup"
                                value="cukup" {{ request('condition') == 'cukup' ? 'checked' : '' }}>
                            <label class="form-check-label" for="conditionCukup">🆗 Cukup</label>
                        </div>
                    </div>
                </div>

                <hr class="opacity-25">

                <div class="filter-section mb-4">
                    <h6 class="fw-semibold mb-3">Rentang Harga</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="small text-secondary mb-1">Minimal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">Rp</span>
                                <input type="number" name="min_price" class="form-control border-start-0"
                                    placeholder="0" value="{{ request('min_price') }}" min="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="small text-secondary mb-1">Maksimal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">Rp</span>
                                <input type="number" name="max_price" class="form-control border-start-0"
                                    placeholder="No limit" value="{{ request('max_price') }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="opacity-25">

                <div class="filter-section mb-4">
                    <h6 class="fw-semibold mb-3">Asal Sekolah</h6>
                    <select name="school" class="form-select rounded-3">
                        <option value="">Semua Sekolah</option>
                        @foreach ($schools ?? [] as $school)
                            <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                                {{ $school }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-success rounded-pill py-3">
                        <i class="bi bi-check2 me-2"></i>Terapkan Filter
                    </button>
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary rounded-pill py-3">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // View toggle (grid/list)
            const viewToggles = document.querySelectorAll('.view-toggle');
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');

            viewToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    viewToggles.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    if (this.dataset.view === 'grid') {
                        gridView.style.display = 'flex';
                        listView.style.display = 'none';
                        localStorage.setItem('catalogView', 'grid');
                    } else {
                        gridView.style.display = 'none';
                        listView.style.display = 'flex';
                        localStorage.setItem('catalogView', 'list');
                    }
                });
            });

            // Load saved view preference
            const savedView = localStorage.getItem('catalogView');
            if (savedView === 'list') {
                const listBtn = document.querySelector('[data-view="list"]');
                if (listBtn) listBtn.click();
            }

            // Auto submit search on typing (with debounce)
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        document.getElementById('searchForm').submit();
                    }, 500);
                });
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .glass-card {
                background: white;
                border: 1px solid rgba(0, 0, 0, 0.02);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            }

            .list-item-card {
                background: white;
                border: 1px solid rgba(0, 0, 0, 0.02);
                transition: all 0.3s;
            }

            .list-item-card:hover {
                transform: translateX(4px);
                box-shadow: 0 8px 16px rgba(34, 197, 94, 0.08);
            }

            .view-toggle.active {
                background: rgba(34, 197, 94, 0.1);
                color: #22c55e;
                border-color: #22c55e;
            }

            .offcanvas {
                background: white;
            }

            [data-bs-theme="dark"] .glass-card,
            [data-bs-theme="dark"] .list-item-card {
                background: #1a1a2c;
                border-color: rgba(255, 255, 255, 0.05);
            }

            [data-bs-theme="dark"] .offcanvas {
                background: #1a1a2c;
            }

            .filter-section .form-check-input:checked {
                background-color: #22c55e;
                border-color: #22c55e;
            }
        </style>
    @endpush
@endsection
