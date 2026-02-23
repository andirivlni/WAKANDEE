@extends('layouts.app')

@section('title', 'Wishlist - WAKANDE')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-heart-fill me-2" style="color: #dc3545;"></i>
                Wishlist
            </h1>
            <p class="text-secondary mb-0">Barang-barang yang kamu simpan untuk dibeli nanti</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-rounded px-4 py-2">
            <i class="bi bi-plus-circle me-2"></i>Jelajahi Katalog
        </a>
    </div>

    @if($wishlist->count() > 0)
        <!-- Wishlist Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card p-3 rounded-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(220,53,69,0.1);">
                            <i class="bi bi-heart-fill fs-4" style="color: #dc3545;"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $wishlist->total() }}</h4>
                            <small class="text-secondary">Total Wishlist</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 rounded-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1);">
                            <i class="bi bi-gift fs-4" style="color: #667eea;"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $wishlist->where('item.type', 'gift')->count() }}</h4>
                            <small class="text-secondary">Barang Gratis</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 rounded-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(25,135,84,0.1);">
                            <i class="bi bi-tag fs-4" style="color: #198754;"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $wishlist->where('item.type', 'sale')->count() }}</h4>
                            <small class="text-secondary">Barang Dijual</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wishlist Items Grid -->
        <div class="row g-4">
            @foreach($wishlist as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="wishlist-card h-100 position-relative">
                        <!-- Remove Button -->
                        <button class="btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-3 shadow-sm remove-wishlist"
                                data-id="{{ $item->id }}"
                                data-item-id="{{ $item->item_id }}"
                                style="width: 36px; height: 36px; z-index: 10; background: white; border: none;">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <!-- Item Image -->
                        <div class="position-relative" style="padding-top: 75%; overflow: hidden; border-radius: 20px 20px 0 0;">
                            @php
                                $images = is_array($item->item->images) ? $item->item->images : (is_string($item->item->images) ? json_decode($item->item->images, true) : []) ?? [];
                                $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                                $isOutOfStock = $item->item->status !== 'approved';
                            @endphp

                            <img src="{{ $firstImage }}"
                                 alt="{{ $item->item->name }}"
                                 class="position-absolute top-0 start-0 w-100 h-100"
                                 style="object-fit: cover; transition: transform 0.3s;"
                                 loading="lazy">

                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                                @if($item->item->type == 'gift')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-gift me-1"></i>Gratis
                                    </span>
                                @else
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="bi bi-tag me-1"></i>Dijual
                                    </span>
                                @endif

                                @if($isOutOfStock)
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>Habis
                                    </span>
                                @endif
                            </div>

                            <!-- Category Badge -->
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-2">
                                    <i class="bi bi-book me-1"></i> {{ $item->item->category_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0" style="font-size: 1.1rem;">
                                    <a href="{{ route('catalog.show', $item->item_id) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($item->item->name, 40) }}
                                    </a>
                                </h6>
                                <span class="small text-secondary">
                                    <i class="bi bi-eye me-1"></i>{{ $item->item->views_count }}
                                </span>
                            </div>

                            <!-- Legacy Message Preview -->
                            <div class="legacy-preview p-3 rounded-3 mb-3">
                                <i class="bi bi-quote me-1" style="color: #667eea; font-size: 0.8rem;"></i>
                                <span class="small text-secondary">
                                    {{ Str::limit($item->item->legacy_message, 60) }}
                                </span>
                            </div>

                            <!-- Price & Seller -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($item->item->type == 'sale')
                                        <span class="fw-bold" style="color: #667eea; font-size: 1.1rem;">
                                            Rp {{ number_format($item->item->price, 0, ',', '.') }}
                                        </span>
                                        <small class="text-secondary d-block">+ admin Rp1.000</small>
                                    @else
                                        <span class="fw-bold text-success">Gratis</span>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <small class="text-secondary d-block">
                                        <i class="bi bi-person-circle me-1"></i>
                                        {{ Str::limit($item->item->user->name, 15) }}
                                    </small>
                                    <small class="text-secondary">
                                        <i class="bi bi-building me-1"></i>
                                        {{ Str::limit($item->item->user->school ?? 'Sekolah', 20) }}
                                    </small>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                @if(!$isOutOfStock)
                                    <a href="{{ route('transactions.checkout', $item->item_id) }}"
                                       class="btn btn-primary grow rounded-pill py-2"
                                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                        <i class="bi bi-{{ $item->item->type == 'gift' ? 'gift' : 'cart' }} me-1"></i>
                                        {{ $item->item->type == 'gift' ? 'Ambil' : 'Beli' }}
                                    </a>
                                @else
                                    <button class="btn btn-secondary grow rounded-pill py-2" disabled>
                                        <i class="bi bi-clock me-1"></i>Tidak Tersedia
                                    </button>
                                @endif

                                <a href="{{ route('catalog.show', $item->item_id) }}"
                                   class="btn btn-outline-secondary rounded-circle p-2"
                                   style="width: 42px; height: 42px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>

                            <!-- Added Date -->
                            <div class="mt-3 text-center">
                                <small class="text-secondary">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Disimpan {{ $item->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $wishlist->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="mb-4 position-relative">
                <i class="bi bi-heart fs-1 text-secondary opacity-25"></i>
                <i class="bi bi-plus-circle-fill position-absolute bottom-0 end-0 translate-middle"
                   style="color: #667eea; font-size: 1.5rem; background: white; border-radius: 50%; padding: 0.2rem;"></i>
            </div>

            <h5 class="fw-bold mb-3">Wishlist-mu Masih Kosong</h5>
            <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">
                Belum ada barang yang kamu simpan. Yuk jelajahi katalog dan temukan barang impianmu!
            </p>

            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-rounded px-5 py-3">
                    <i class="bi bi-grid me-2"></i>Jelajahi Katalog
                </a>

                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-rounded px-5 py-3">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </a>
                @endguest
            </div>

            <!-- Tips -->
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    <div class="tips-card p-4 rounded-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-lightbulb me-2" style="color: #667eea;"></i>
                            Tips Menggunakan Wishlist
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="tip-icon mx-auto mb-2">
                                        <i class="bi bi-heart fs-4" style="color: #dc3545;"></i>
                                    </div>
                                    <small class="d-block fw-semibold">Klik Hati</small>
                                    <span class="text-secondary small">Klik ikon hati di katalog untuk menyimpan</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="tip-icon mx-auto mb-2">
                                        <i class="bi bi-bell fs-4" style="color: #667eea;"></i>
                                    </div>
                                    <small class="d-block fw-semibold">Pantau Harga</small>
                                    <span class="text-secondary small">Dapat notifikasi jika harga turun</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="tip-icon mx-auto mb-2">
                                        <i class="bi bi-cart-check fs-4" style="color: #198754;"></i>
                                    </div>
                                    <small class="d-block fw-semibold">Beli Nanti</small>
                                    <span class="text-secondary small">Checkout kapanpun kamu siap</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

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
                cancelButtonText: 'Batal'
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
                            // Animate removal
                            card.style.transition = 'all 0.3s';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.8)';

                            setTimeout(() => {
                                card.remove();

                                // Check if wishlist is empty
                                if (document.querySelectorAll('.wishlist-card').length === 0) {
                                    location.reload();
                                }

                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dihapus!',
                                    text: 'Barang dihapus dari wishlist',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Gagal menghapus barang',
                            'error'
                        );
                    });
                }
            });
        });
    });

    // Hover effect on wishlist cards
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
    .wishlist-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .wishlist-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 24px rgba(102,126,234,0.08);
    }

    .stat-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102,126,234,0.08);
    }

    .legacy-preview {
        background: rgba(102,126,234,0.03);
        border-left: 3px solid #667eea;
    }

    .remove-wishlist {
        transition: all 0.2s;
    }

    .remove-wishlist:hover {
        background: #dc3545 !important;
        color: white !important;
    }

    .tips-card {
        background: linear-gradient(135deg, rgba(102,126,234,0.03) 0%, rgba(118,75,162,0.03) 100%);
        border: 1px solid rgba(102,126,234,0.1);
    }

    .tip-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.02);
    }

    .empty-state {
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [data-bs-theme="dark"] .wishlist-card,
    [data-bs-theme="dark"] .stat-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .tips-card {
        background: rgba(255,255,255,0.02);
    }

    [data-bs-theme="dark"] .tip-icon {
        background: #1a1a2c;
        border: 1px solid rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .text-dark {
        color: white !important;
    }

    [data-bs-theme="dark"] .bg-white {
        background: #1a1a2c !important;
    }

    /* Pagination styling */
    .pagination {
        gap: 0.5rem;
    }

    .page-link {
        border-radius: 12px !important;
        border: none;
        padding: 0.75rem 1rem;
        color: var(--bs-body-color);
        background: transparent;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: rgba(102,126,234,0.1);
        color: #667eea;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    [data-bs-theme="dark"] .page-link {
        background: rgba(255,255,255,0.02);
        color: white;
    }

    [data-bs-theme="dark"] .page-link:hover {
        background: rgba(102,126,234,0.2);
    }
</style>
@endpush
@endsection
