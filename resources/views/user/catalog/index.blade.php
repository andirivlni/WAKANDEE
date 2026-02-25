@extends('layouts.app')

@section('title', 'Katalog - WAKANDE')

@section('content')
<div class="container py-4">
    {{-- HEADER --}}
    <div class="text-center mb-4">
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 mb-2" style="font-size: 0.8rem;">
            <i class="bi bi-grid me-1"></i>KATALOG
        </span>
        <h5 class="fw-bold mb-1" style="color: #1A2A24;">Temukan Kebutuhan Akademikmu</h5>
        <p class="small text-secondary mx-auto" style="max-width: 500px; font-size: 0.85rem;">
            Dari buku bekas berkualitas hingga alat praktikum, semua bisa kamu dapatkan secara gratis atau harga terjangkau
        </p>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="search-filter-card p-3 mb-4" style="background: white; border-radius: 16px; border: 1px solid #EDF2F0; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <div class="row g-2 align-items-center">
            <div class="col-lg-5">
                <form action="{{ route('catalog.index') }}" method="GET" id="searchForm">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.85rem;"></i>
                        <input type="text" name="search" class="form-control rounded-5 border-0"
                               style="padding-left: 35px; padding-top: 0.5rem; padding-bottom: 0.5rem; background: #F8FBF8; font-size: 0.85rem; color: #1A2A24;"
                               placeholder="Cari buku, seragam, alat praktikum..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-7">
                <div class="d-flex flex-wrap justify-content-lg-end align-items-center" style="gap: 0.75rem;">
                    {{-- Filter Button --}}
                    <button class="btn btn-sm rounded-5 px-3 py-1 d-flex align-items-center"
                            style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.85rem;"
                            data-bs-toggle="offcanvas" data-bs-target="#filterCanvas">
                        <i class="bi bi-sliders2 me-1"></i>Filter
                        @if (request()->hasAny(['category', 'type', 'condition', 'school', 'min_price', 'max_price']))
                            <span class="badge bg-success ms-1 rounded-circle p-1" style="width: 4px; height: 4px;"></span>
                        @endif
                    </button>

                    {{-- Sort Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-sm rounded-5 px-3 py-1 dropdown-toggle d-flex align-items-center"
                                style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.85rem;"
                                type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-arrow-up-short me-1"></i>Urutkan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rounded-4 border-0 shadow-sm p-2" style="min-width: 160px; background: white;">
                            <li>
                                <a class="dropdown-item rounded-3 py-1 small {{ request('sort') == 'terbaru' ? 'active' : '' }}"
                                   href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'terbaru'])) }}">
                                    <i class="bi bi-clock me-2"></i>Terbaru
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 py-1 small {{ request('sort') == 'populer' ? 'active' : '' }}"
                                   href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'populer'])) }}">
                                    <i class="bi bi-fire me-2"></i>Populer
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-1 small {{ request('sort') == 'termurah' ? 'active' : '' }}"
                                   href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'termurah'])) }}">
                                    <i class="bi bi-arrow-up me-2"></i>Harga Terendah
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 py-1 small {{ request('sort') == 'termahal' ? 'active' : '' }}"
                                   href="{{ route('catalog.index', array_merge(request()->all(), ['sort' => 'termahal'])) }}">
                                    <i class="bi bi-arrow-down me-2"></i>Harga Tertinggi
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- View Toggle --}}
                    <div class="btn-group" style="gap: 0.25rem;">
                        <button class="btn btn-sm rounded-5 px-3 py-1 view-toggle active d-flex align-items-center"
                                style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.85rem;"
                                data-view="grid">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button class="btn btn-sm rounded-5 px-3 py-1 view-toggle d-flex align-items-center"
                                style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.85rem;"
                                data-view="list">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVE FILTERS --}}
    @if (request()->hasAny(['category', 'type', 'condition', 'school', 'min_price', 'max_price']))
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="small text-secondary me-1" style="font-size: 0.8rem;">Filter aktif:</span>

            @if (request('category'))
                <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-5"
                      style="background: #F0F5F0; font-size: 0.75rem; color: #1A2A24;">
                    Kategori: {{ ucfirst(request('category')) }}
                    <a href="{{ route('catalog.index', array_merge(request()->except('category'), ['page' => null])) }}"
                       class="text-decoration-none" style="color: #6c757d;">
                        <i class="bi bi-x" style="font-size: 1rem; line-height: 1;"></i>
                    </a>
                </span>
            @endif

            @if (request('type'))
                <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-5"
                      style="background: #F0F5F0; font-size: 0.75rem; color: #1A2A24;">
                    Tipe: {{ request('type') == 'gift' ? 'Gratis' : 'Dijual' }}
                    <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['page' => null])) }}"
                       class="text-decoration-none" style="color: #6c757d;">
                        <i class="bi bi-x" style="font-size: 1rem; line-height: 1;"></i>
                    </a>
                </span>
            @endif

            @if (request('condition'))
                <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-5"
                      style="background: #F0F5F0; font-size: 0.75rem; color: #1A2A24;">
                    Kondisi: {{ ucfirst(str_replace('_', ' ', request('condition'))) }}
                    <a href="{{ route('catalog.index', array_merge(request()->except('condition'), ['page' => null])) }}"
                       class="text-decoration-none" style="color: #6c757d;">
                        <i class="bi bi-x" style="font-size: 1rem; line-height: 1;"></i>
                    </a>
                </span>
            @endif

            @if (request('min_price') || request('max_price'))
                <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-5"
                      style="background: #F0F5F0; font-size: 0.75rem; color: #1A2A24;">
                    Harga: Rp {{ number_format(request('min_price', 0)) }} - Rp {{ number_format(request('max_price', '∞')) }}
                    <a href="{{ route('catalog.index', array_merge(request()->except(['min_price', 'max_price']), ['page' => null])) }}"
                       class="text-decoration-none" style="color: #6c757d;">
                        <i class="bi bi-x" style="font-size: 1rem; line-height: 1;"></i>
                    </a>
                </span>
            @endif

            @if (request('school'))
                <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-5"
                      style="background: #F0F5F0; font-size: 0.75rem; color: #1A2A24;">
                    Sekolah: {{ request('school') }}
                    <a href="{{ route('catalog.index', array_merge(request()->except('school'), ['page' => null])) }}"
                       class="text-decoration-none" style="color: #6c757d;">
                        <i class="bi bi-x" style="font-size: 1rem; line-height: 1;"></i>
                    </a>
                </span>
            @endif

            <a href="{{ route('catalog.index') }}" class="small text-decoration-none ms-2" style="color: #22c55e; font-size: 0.75rem;">
                <i class="bi bi-x-circle me-1"></i>Hapus semua
            </a>
        </div>
    @endif

    {{-- RESULT COUNT --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="small text-secondary mb-0" style="font-size: 0.85rem;">
            <span class="fw-semibold" style="color: #1A2A24;">{{ $items->total() }}</span> barang ditemukan
        </p>
    </div>

    {{-- ITEMS GRID/LIST --}}
    @if ($items->count() > 0)
        {{-- Grid View --}}
        <div id="gridView" class="row g-3">
            @foreach ($items as $item)
                @php
                    $item->is_wishlisted = in_array($item->id, $wishlist_ids ?? []);
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <x-item-card :item="$item" />
                </div>
            @endforeach
        </div>

        {{-- List View --}}
        <div id="listView" class="vstack gap-2" style="display: none;">
            @foreach ($items as $item)
                <div class="list-item-card p-2 rounded-4" style="background: white; border: 1px solid #EDF2F0;">
                    <div class="row align-items-center g-2">
                        <div class="col-md-2 col-3">
                            @php
                                $images = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []);
                                $images = $images ?? [];
                                $firstImage = !empty($images) && isset($images[0]) ? Storage::url($images[0]) : asset('images/default-item.png');
                            @endphp
                            <div class="rounded-3 overflow-hidden" style="aspect-ratio: 1;">
                                <img src="{{ $firstImage }}" alt="{{ $item->name }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-md-7 col-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex gap-1 mb-1">
                                    @if ($item->type == 'gift')
                                        <span class="badge bg-success rounded-pill px-2 py-0" style="font-size: 0.65rem;">Gratis</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-2 py-0" style="font-size: 0.65rem;">Dijual</span>
                                    @endif
                                    <span class="badge rounded-pill px-2 py-0" style="font-size: 0.65rem; background: #F0F5F0; color: #1A2A24;">{{ $item->category_label }}</span>
                                </div>
                                <h6 class="fw-semibold mb-0" style="font-size: 0.9rem; color: #1A2A24;">{{ $item->name }}</h6>
                                <p class="small mb-0" style="font-size: 0.7rem; color: #6c757d;">{{ Str::limit($item->legacy_message, 50) }}</p>
                                <div class="d-flex align-items-center gap-2 small mt-1" style="font-size: 0.65rem; color: #6c757d;">
                                    <span><i class="bi bi-person-circle me-1"></i>{{ $item->user->name }}</span>
                                    <span><i class="bi bi-building me-1"></i>{{ $item->user->school ?? 'Sekolah' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-3">
                            <div class="text-end">
                                @if ($item->type == 'sale')
                                    <span class="fw-semibold d-block mb-0" style="color: #22c55e; font-size: 0.9rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <small class="d-block mb-1" style="font-size: 0.6rem; color: #6c757d;">+ admin Rp1.000</small>
                                @else
                                    <span class="fw-semibold d-block mb-1" style="color: #22c55e; font-size: 0.9rem;">Gratis</span>
                                @endif
                                <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-sm rounded-5 px-2 py-0" style="font-size: 0.7rem; border: 1px solid #22c55e; color: #22c55e; background: transparent;">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $items->withQueryString()->links() }}
        </div>

    @else
        {{-- EMPTY STATE --}}
        <div class="text-center py-4 rounded-4" style="background: #F8FBF8;">
            <div class="mb-2">
                <i class="bi bi-inbox" style="color: #22c55e; opacity: 0.3; font-size: 2rem;"></i>
            </div>
            <h6 class="fw-semibold mb-1" style="color: #1A2A24; font-size: 1rem;">Barang Tidak Ditemukan</h6>
            <p class="small mb-3" style="max-width: 350px; margin: 0 auto; font-size: 0.8rem; color: #6c757d;">
                Maaf, tidak ada barang yang sesuai dengan kriteria pencarianmu. Coba kata kunci lain atau hapus filter.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('catalog.index') }}" class="btn btn-sm rounded-5 px-3 py-1"
                   style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                    <i class="bi bi-arrow-repeat me-1"></i>Reset Filter
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-sm rounded-5 px-3 py-1"
                       style="background: white; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.8rem;">
                        <i class="bi bi-gift me-1"></i>Mulai Berbagi
                    </a>
                @endguest
            </div>
        </div>
    @endif
</div>

{{-- FILTER OFF CANVAS - DARK MODE FIX --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterCanvas" aria-labelledby="filterCanvasLabel">
    <div class="offcanvas-header p-3 border-bottom">
        <h6 class="offcanvas-title fw-semibold" id="filterCanvasLabel" style="font-size: 1rem; color: #1A2A24;">
            <i class="bi bi-sliders2 me-2" style="color: #22c55e;"></i>Filter
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" style="font-size: 0.8rem;"></button>
    </div>
    <div class="offcanvas-body p-3 pt-3">
        <form action="{{ route('catalog.index') }}" method="GET" id="filterForm">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if (request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            {{-- Kategori --}}
            <div class="filter-section mb-3">
                <h6 class="fw-semibold mb-2" style="font-size: 0.85rem; color: #1A2A24;">Kategori</h6>
                <div class="d-flex flex-column gap-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="categoryAll" value="" {{ !request('category') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="categoryAll" style="color: #1A2A24;">Semua</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="categoryBuku" value="buku" {{ request('category') == 'buku' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="categoryBuku" style="color: #1A2A24;">📚 Buku</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="categorySeragam" value="seragam" {{ request('category') == 'seragam' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="categorySeragam" style="color: #1A2A24;">👕 Seragam</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="categoryAlatPraktikum" value="alat_praktikum" {{ request('category') == 'alat_praktikum' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="categoryAlatPraktikum" style="color: #1A2A24;">🔬 Alat Praktikum</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="categoryLainnya" value="lainnya" {{ request('category') == 'lainnya' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="categoryLainnya" style="color: #1A2A24;">📦 Lainnya</label>
                    </div>
                </div>
            </div>

            <hr class="opacity-25 my-2">

            {{-- Tipe Barang --}}
            <div class="filter-section mb-3">
                <h6 class="fw-semibold mb-2" style="font-size: 0.85rem; color: #1A2A24;">Tipe Barang</h6>
                <div class="d-flex flex-column gap-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeAll" value="" {{ !request('type') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="typeAll" style="color: #1A2A24;">Semua</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeGift" value="gift" {{ request('type') == 'gift' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="typeGift" style="color: #1A2A24;">🎁 Gratis</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeSale" value="sale" {{ request('type') == 'sale' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="typeSale" style="color: #1A2A24;">💰 Dijual</label>
                    </div>
                </div>
            </div>

            <hr class="opacity-25 my-2">

            {{-- Kondisi Barang --}}
            <div class="filter-section mb-3">
                <h6 class="fw-semibold mb-2" style="font-size: 0.85rem; color: #1A2A24;">Kondisi Barang</h6>
                <div class="d-flex flex-column gap-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="condition" id="conditionAll" value="" {{ !request('condition') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="conditionAll" style="color: #1A2A24;">Semua</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="condition" id="conditionBaru" value="baru" {{ request('condition') == 'baru' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="conditionBaru" style="color: #1A2A24;">🆕 Baru</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="condition" id="conditionSangatBaik" value="sangat_baik" {{ request('condition') == 'sangat_baik' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="conditionSangatBaik" style="color: #1A2A24;">✨ Sangat Baik</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="condition" id="conditionBaik" value="baik" {{ request('condition') == 'baik' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="conditionBaik" style="color: #1A2A24;">👍 Baik</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="condition" id="conditionCukup" value="cukup" {{ request('condition') == 'cukup' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="conditionCukup" style="color: #1A2A24;">🆗 Cukup</label>
                    </div>
                </div>
            </div>

            <hr class="opacity-25 my-2">

            {{-- Rentang Harga --}}
            <div class="filter-section mb-3">
                <h6 class="fw-semibold mb-2" style="font-size: 0.85rem; color: #1A2A24;">Rentang Harga</h6>
                <div class="row g-1">
                    <div class="col-6">
                        <label class="small mb-0" style="font-size: 0.7rem; color: #6c757d;">Minimal</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0 px-2" style="font-size: 0.7rem; border-color: #EDF2F0; color: #1A2A24;">Rp</span>
                            <input type="number" name="min_price" class="form-control border-start-0" style="font-size: 0.7rem; padding: 0.25rem; border-color: #EDF2F0; color: #1A2A24;" placeholder="0" value="{{ request('min_price') }}" min="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="small mb-0" style="font-size: 0.7rem; color: #6c757d;">Maksimal</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0 px-2" style="font-size: 0.7rem; border-color: #EDF2F0; color: #1A2A24;">Rp</span>
                            <input type="number" name="max_price" class="form-control border-start-0" style="font-size: 0.7rem; padding: 0.25rem; border-color: #EDF2F0; color: #1A2A24;" placeholder="∞" value="{{ request('max_price') }}" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="opacity-25 my-2">

            {{-- Asal Sekolah --}}
            <div class="filter-section mb-4">
                <h6 class="fw-semibold mb-2" style="font-size: 0.85rem; color: #1A2A24;">Asal Sekolah</h6>
                <select name="school" class="form-select form-select-sm rounded-3" style="font-size: 0.8rem; padding: 0.3rem; border-color: #EDF2F0; color: #1A2A24;">
                    <option value="" style="color: #1A2A24;">Semua Sekolah</option>
                    @foreach ($schools ?? [] as $school)
                        <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }} style="color: #1A2A24;">{{ $school }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Actions --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-sm rounded-5 py-2" style="background: #22c55e; color: white; border: none; font-size: 0.85rem;">
                    <i class="bi bi-check2 me-1"></i>Terapkan Filter
                </button>
                <a href="{{ route('catalog.index') }}" class="btn btn-sm rounded-5 py-2" style="background: white; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.85rem;">
                    <i class="bi bi-arrow-repeat me-1"></i>Reset
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
    /* PROPORTIONAL CATALOG STYLES */
    .view-toggle.active {
        background: rgba(34, 197, 94, 0.1) !important;
        color: #22c55e !important;
        border-color: #22c55e !important;
    }

    .list-item-card {
        transition: all 0.2s;
    }

    .list-item-card:hover {
        transform: translateX(2px);
        box-shadow: 0 4px 8px rgba(34, 197, 94, 0.05);
    }

    .filter-section .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }

    .form-check-input {
        margin-top: 0.2rem;
    }

    /* DARK MODE FIXES */
    [data-bs-theme="dark"] .search-filter-card,
    [data-bs-theme="dark"] .list-item-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.05) !important;
    }

    [data-bs-theme="dark"] .offcanvas {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .offcanvas-header {
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .offcanvas-title,
    [data-bs-theme="dark"] h6,
    [data-bs-theme="dark"] label,
    [data-bs-theme="dark"] [style*="color: #1A2A24"],
    [data-bs-theme="dark"] .form-check-label,
    [data-bs-theme="dark"] select,
    [data-bs-theme="dark"] option {
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] [style*="color: #6c757d"] {
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select,
    [data-bs-theme="dark"] .input-group-text {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .form-control::placeholder {
        color: #6c757d !important;
    }

    [data-bs-theme="dark"] .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    [data-bs-theme="dark"] .dropdown-menu {
        background: #1A1A2C !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .dropdown-item {
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05) !important;
    }

    [data-bs-theme="dark"] hr {
        border-color: rgba(255, 255, 255, 0.1) !important;
        opacity: 0.5;
    }

    [data-bs-theme="dark"] [style*="background: #F0F5F0"] {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .page-link {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .page-item.active .page-link {
        background: #22c55e !important;
        color: white !important;
    }

    /* Pagination styling */
    .pagination {
        gap: 0.2rem;
    }

    .page-link {
        border-radius: 30px !important;
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        border: 1px solid #EDF2F0;
        color: #1A2A24;
    }

    .page-item.active .page-link {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
    }
</style>
@endpush
@endsection
