@extends('layouts.app')

@section('title', 'Wishlist - WAKANDE')

@section('content')
<div class="container py-4">
    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-opacity-10"
                 style="width: 40px; height: 40px; background: rgba(220, 53, 69, 0.1);">
                <i class="bi bi-heart-fill" style="color: #dc3545; font-size: 1.2rem;"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-body">Wishlist</h5>
                <p class="small text-secondary mb-0" style="font-size: 0.8rem;">Barang yang kamu simpan untuk dibeli nanti</p>
            </div>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-sm rounded-5 px-4 py-2 text-white"
           style="background: #22c55e; border: none;">
            <i class="bi bi-plus-circle me-1"></i>Jelajahi Katalog
        </a>
    </div>

    @if($wishlist->count() > 0)
        {{-- STATS CARDS --}}
        <div class="row g-2 mb-4">
            <div class="col-md-4">
                <div class="stats-mini p-2 rounded-3 d-flex align-items-center gap-2 bg-body border">
                    <div class="stats-icon-mini rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(220, 53, 69, 0.1);">
                        <i class="bi bi-heart-fill" style="color: #dc3545; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="fw-bold d-block text-body" style="font-size: 1rem;">{{ $wishlist->total() }}</span>
                        <small class="text-secondary" style="font-size: 0.7rem;">Total Wishlist</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-mini p-2 rounded-3 d-flex align-items-center gap-2 bg-body border">
                    <div class="stats-icon-mini rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(34, 197, 94, 0.1);">
                        <i class="bi bi-gift" style="color: #22c55e; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="fw-bold d-block text-body" style="font-size: 1rem;">{{ $wishlist->where('item.type', 'gift')->count() }}</span>
                        <small class="text-secondary" style="font-size: 0.7rem;">Barang Gratis</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-mini p-2 rounded-3 d-flex align-items-center gap-2 bg-body border">
                    <div class="stats-icon-mini rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(25, 135, 84, 0.1);">
                        <i class="bi bi-tag" style="color: #198754; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="fw-bold d-block text-body" style="font-size: 1rem;">{{ $wishlist->where('item.type', 'sale')->count() }}</span>
                        <small class="text-secondary" style="font-size: 0.7rem;">Barang Dijual</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- WISHLIST GRID --}}
        <div class="row g-3">
            @foreach($wishlist as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="wishlist-card p-2 position-relative bg-body border rounded-4">
                        {{-- Remove Button --}}
                        <button class="btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-2 remove-wishlist shadow-sm"
                                data-id="{{ $item->id }}"
                                data-item-id="{{ $item->item_id }}"
                                style="width: 30px; height: 30px; background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); z-index: 10;">
                            <i class="bi bi-x" style="font-size: 1rem; color: var(--bs-body-color);"></i>
                        </button>

                        {{-- Image --}}
                        <div class="position-relative rounded-3 overflow-hidden mb-2" style="aspect-ratio: 1;">
                            @php
                                $images = is_array($item->item->images) ? $item->item->images : (is_string($item->item->images) ? json_decode($item->item->images, true) : []) ?? [];
                                $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                                $isOutOfStock = $item->item->status !== 'approved';
                            @endphp

                            <img src="{{ $firstImage }}" alt="{{ $item->item->name }}"
                                 class="w-100 h-100" style="object-fit: cover;">

                            {{-- Badges --}}
                            <div class="position-absolute top-0 start-0 m-2 d-flex gap-1">
                                @if($item->item->type == 'gift')
                                    <span class="badge bg-success" style="font-size: 0.6rem;">Gratis</span>
                                @else
                                    <span class="badge bg-success" style="font-size: 0.6rem;">Dijual</span>
                                @endif

                                @if($isOutOfStock)
                                    <span class="badge bg-secondary" style="font-size: 0.6rem;">Habis</span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="px-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <a href="{{ route('catalog.show', $item->item_id) }}"
                                   class="text-decoration-none fw-semibold small text-body">
                                    {{ Str::limit($item->item->name, 30) }}
                                </a>
                                <small class="text-secondary" style="font-size: 0.6rem;">
                                    <i class="bi bi-eye"></i> {{ $item->item->views_count }}
                                </small>
                            </div>

                            {{-- Legacy Message Preview --}}
                            <div class="legacy-mini p-2 rounded-2 mb-2" style="background: var(--bs-tertiary-bg); border-left: 2px solid #22c55e;">
                                <i class="bi bi-quote" style="color: #22c55e; font-size: 0.6rem;"></i>
                                <span class="small text-secondary" style="font-size: 0.65rem;">
                                    {{ Str::limit($item->item->legacy_message, 40) }}
                                </span>
                            </div>

                            {{-- Price & Seller --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($item->item->type == 'sale')
                                        <span class="fw-semibold" style="color: #22c55e; font-size: 0.9rem;">
                                            Rp {{ number_format($item->item->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="fw-semibold text-success" style="font-size: 0.9rem;">Gratis</span>
                                    @endif
                                </div>
                                <small class="text-secondary" style="font-size: 0.6rem;">
                                    <i class="bi bi-person-circle"></i> {{ Str::limit($item->item->user->name, 10) }}
                                </small>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex gap-1">
                                @if(!$isOutOfStock)
                                    <a href="{{ route('transactions.checkout', $item->item_id) }}"
                                       class="btn btn-sm w-100 rounded-5 py-1 text-white"
                                       style="background: #22c55e; border: none; font-size: 0.7rem;">
                                        <i class="bi bi-{{ $item->item->type == 'gift' ? 'gift' : 'cart' }} me-1"></i>
                                        {{ $item->item->type == 'gift' ? 'Ambil' : 'Beli' }}
                                    </a>
                                @else
                                    <button class="btn btn-sm w-100 rounded-5 py-1"
                                            style="background: var(--bs-tertiary-bg); color: var(--bs-secondary-color); border: 1px solid var(--bs-border-color); font-size: 0.7rem;" disabled>
                                        <i class="bi bi-clock me-1"></i>Tidak Tersedia
                                    </button>
                                @endif

                                <a href="{{ route('catalog.show', $item->item_id) }}"
                                   class="btn btn-sm rounded-5 px-3 py-1 text-body"
                                   style="background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); font-size: 0.7rem;">
                                    Detail
                                </a>
                            </div>

                            {{-- Date --}}
                            <div class="text-center mt-2">
                                <small class="text-secondary" style="font-size: 0.55rem;">
                                    <i class="bi bi-clock-history"></i> {{ $item->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $wishlist->withQueryString()->links() }}
        </div>

    @else
        {{-- EMPTY STATE - DARK MODE READY --}}
        <div class="text-center py-4 rounded-4" style="background: var(--bs-tertiary-bg);">
            <div class="mb-2 position-relative d-inline-block">
                <i class="bi bi-heart" style="color: #dc3545; opacity: 0.3; font-size: 2.5rem;"></i>
                <i class="bi bi-plus-circle-fill position-absolute bottom-0 end-0"
                   style="color: #22c55e; font-size: 1rem; background: var(--bs-body-bg); border-radius: 50%;"></i>
            </div>

            <h6 class="fw-semibold mb-1 text-body" style="font-size: 1rem;">Wishlist-mu Masih Kosong</h6>
            <p class="small text-secondary mb-3" style="max-width: 350px; margin: 0 auto; font-size: 0.8rem;">
                Belum ada barang yang kamu simpan. Yuk jelajahi katalog dan temukan barang impianmu!
            </p>

            <div class="d-flex gap-2 justify-content-center mb-4">
                <a href="{{ route('catalog.index') }}" class="btn btn-sm rounded-5 px-4 py-2 text-white"
                   style="background: #22c55e; border: none; font-size: 0.8rem;">
                    <i class="bi bi-grid me-1"></i>Jelajahi Katalog
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-sm rounded-5 px-4 py-2 text-body"
                       style="background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); font-size: 0.8rem;">
                        <i class="bi bi-person-plus me-1"></i>Daftar
                    </a>
                @endguest
            </div>

            {{-- Tips Cards - Dark Mode Ready --}}
            <div class="row g-2 justify-content-center">
                <div class="col-md-8">
                    <div class="p-3 rounded-4" style="background: var(--bs-body-bg); border: 1px solid var(--bs-border-color);">
                        <h6 class="fw-semibold small mb-2 d-flex align-items-center text-body">
                            <i class="bi bi-lightbulb me-1" style="color: #22c55e; font-size: 0.8rem;"></i>
                            Tips Menggunakan Wishlist
                        </h6>
                        <div class="d-flex justify-content-around">
                            <div class="text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                     style="width: 32px; height: 32px; background: var(--bs-tertiary-bg);">
                                    <i class="bi bi-heart" style="color: #dc3545; font-size: 0.9rem;"></i>
                                </div>
                                <small class="d-block fw-semibold text-body" style="font-size: 0.65rem;">Klik Hati</small>
                                <span class="text-secondary" style="font-size: 0.55rem;">di katalog</span>
                            </div>
                            <div class="text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                     style="width: 32px; height: 32px; background: var(--bs-tertiary-bg);">
                                    <i class="bi bi-bell" style="color: #22c55e; font-size: 0.9rem;"></i>
                                </div>
                                <small class="d-block fw-semibold text-body" style="font-size: 0.65rem;">Pantau</small>
                                <span class="text-secondary" style="font-size: 0.55rem;">harga turun</span>
                            </div>
                            <div class="text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                     style="width: 32px; height: 32px; background: var(--bs-tertiary-bg);">
                                    <i class="bi bi-cart-check" style="color: #198754; font-size: 0.9rem;"></i>
                                </div>
                                <small class="d-block fw-semibold text-body" style="font-size: 0.65rem;">Beli</small>
                                <span class="text-secondary" style="font-size: 0.55rem;">kapan saja</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Remove from wishlist with AJAX
    document.querySelectorAll('.remove-wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const wishlistId = this.dataset.id;
            const itemId = this.dataset.itemId;
            const card = this.closest('.col-lg-4');

            Swal.fire({
                title: 'Hapus dari Wishlist?',
                text: 'Barang akan dihapus dari daftar wishlist kamu',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: 'var(--bs-body-bg)',
                color: 'var(--bs-body-color)',
                customClass: {
                    popup: 'rounded-4 p-3',
                    title: 'small fw-bold',
                    htmlContainer: 'small'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/wishlist/${wishlistId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            card.style.transition = 'all 0.3s';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.8)';

                            setTimeout(() => {
                                card.remove();

                                if (document.querySelectorAll('.wishlist-card').length === 0) {
                                    location.reload();
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dihapus!',
                                    text: 'Barang dihapus dari wishlist',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    background: 'var(--bs-body-bg)',
                                    color: 'var(--bs-body-color)',
                                    customClass: {
                                        popup: 'rounded-4 p-3',
                                        title: 'small fw-bold'
                                    }
                                });
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal menghapus barang',
                            icon: 'error',
                            background: 'var(--bs-body-bg)',
                            color: 'var(--bs-body-color)'
                        });
                    });
                }
            });
        });
    });

    // Hover effect
    document.querySelectorAll('.wishlist-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const img = this.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1.05)';
            }
        });

        card.addEventListener('mouseleave', function() {
            const img = this.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* PROPORTIONAL WISHLIST STYLES */
    .wishlist-card {
        transition: all 0.2s;
        height: 100%;
        background: var(--bs-body-bg);
        border-color: var(--bs-border-color) !important;
    }

    .wishlist-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.2) !important;
    }

    .stats-mini {
        transition: all 0.2s;
        height: 100%;
        background: var(--bs-body-bg);
        border-color: var(--bs-border-color) !important;
    }

    .stats-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
    }

    .remove-wishlist {
        transition: all 0.2s;
        opacity: 0.7;
    }

    .remove-wishlist:hover {
        opacity: 1;
        background: #dc3545 !important;
    }

    .remove-wishlist:hover i {
        color: white !important;
    }

    /* Pagination */
    .pagination {
        gap: 0.2rem;
    }

    .page-link {
        border-radius: 30px !important;
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        background: var(--bs-tertiary-bg);
        border: 1px solid var(--bs-border-color);
        color: var(--bs-body-color);
    }

    .page-link:hover {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border-color: #22c55e;
    }

    .page-item.active .page-link {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
    }

    /* Dark mode specific */
    [data-bs-theme="dark"] .wishlist-card {
        background: #1A1A2C;
    }

    [data-bs-theme="dark"] .stats-mini {
        background: #1A1A2C;
    }

    [data-bs-theme="dark"] .bg-body {
        background: #1A1A2C !important;
    }

    [data-bs-theme="dark"] .text-body {
        color: #E0E0E0 !important;
    }
</style>
@endpush
