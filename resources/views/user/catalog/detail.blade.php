@extends('layouts.app')

@section('title', $item->name . ' - WAKANDE')

@section('content')
<div class="container py-4">
    {{-- BREADCRUMB MINI --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none text-secondary">Katalog</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index', ['category' => $item->category]) }}" class="text-decoration-none text-secondary">{{ $item->category_label }}</a></li>
            <li class="breadcrumb-item active text-secondary fw-semibold" aria-current="page">{{ Str::limit($item->name, 25) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- LEFT COLUMN - IMAGES --}}
        <div class="col-lg-6">
            <div class="position-sticky" style="top: 80px;">
                @php
                    $images = $item->images ?? [];
                    if (is_string($images)) {
                        $images = is_array($images) ? $images : (is_string($images) ? json_decode($images, true) : []) ?? [];
                    }
                    $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                @endphp

                {{-- MAIN IMAGE --}}
                <div class="main-image-container mb-2 rounded-3 overflow-hidden border" style="aspect-ratio: 1;">
                    <img src="{{ $firstImage }}"
                         alt="{{ $item->name }}"
                         id="mainImage"
                         class="w-100 h-100"
                         style="object-fit: contain; cursor: zoom-in; transition: transform 0.2s;"
                         onclick="if(window.innerWidth<768) window.open(this.src,'_blank')">
                </div>

                {{-- THUMBNAILS --}}
                @php
                    $thumbnailImages = is_array($images) ? $images : [];
                @endphp

                @if(count($thumbnailImages) > 1)
                    <div class="d-flex gap-1 overflow-auto pb-1" style="scrollbar-width: thin;">
                        @foreach($thumbnailImages as $index => $image)
                            @if(!empty($image))
                            <div class="thumbnail-container flex-shrink-0 rounded-2 overflow-hidden {{ $index == 0 ? 'active' : '' }} border"
                                 onclick="changeMainImage('{{ Storage::url($image) }}', this)"
                                 style="width: 60px; height: 60px; cursor: pointer;">
                                <img src="{{ Storage::url($image) }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- SHARE BUTTON ONLY --}}
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center" onclick="shareItem()"
                            style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24;">
                        <i class="bi bi-share me-1" style="font-size: 0.8rem;"></i> Bagikan
                    </button>
                    @auth
                        <button class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center {{ $is_wishlisted ? 'text-danger' : '' }}"
                                style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24;"
                                onclick="toggleWishlist({{ $item->id }})">
                            <i class="bi bi-heart{{ $is_wishlisted ? '-fill' : '' }} me-1" style="font-size: 0.8rem; color: {{ $is_wishlisted ? '#dc3545' : '' }};"></i>
                            <span id="wishlistText">{{ $is_wishlisted ? 'Disimpan' : 'Simpan' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
                           style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24;">
                            <i class="bi bi-heart me-1" style="font-size: 0.8rem;"></i> Simpan
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN - DETAILS --}}
        <div class="col-lg-6">
            {{-- BADGES MINI --}}
            <div class="d-flex flex-wrap gap-1 mb-2">
                @if($item->type == 'gift')
                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                        <i class="bi bi-gift me-1" style="font-size: 0.6rem;"></i>Gratis
                    </span>
                @else
                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                        <i class="bi bi-tag me-1" style="font-size: 0.6rem;"></i>Dijual
                    </span>
                @endif
                <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #F0F5F0 !important;">
                    <i class="bi bi-book me-1" style="font-size: 0.6rem;"></i>{{ $item->category_label }}
                </span>
                <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #F0F5F0 !important;">
                    <i class="bi bi-check-circle me-1" style="font-size: 0.6rem;"></i>{{ $item->condition_label }}
                </span>
            </div>

            {{-- TITLE --}}
            <h4 class="fw-bold mb-2" style="color: #1A2A24;">{{ $item->name }}</h4>

            {{-- PRICE CARD MINI --}}
            <div class="p-3 rounded-3 mb-3" style="background: #F8FBF8;">
                @if($item->type == 'sale')
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="fw-bold" style="color: #22c55e; font-size: 1.3rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <small class="text-secondary">+ Rp1.000 admin</small>
                    </div>
                @else
                    <span class="fw-bold" style="color: #22c55e; font-size: 1.3rem;">Gratis</span>
                    <small class="text-secondary d-block mt-1">
                        <i class="bi bi-gift me-1" style="font-size: 0.7rem;"></i>Barang hibah dari kakak kelas
                    </small>
                @endif
            </div>

            {{-- ACTION BUTTON --}}
            @auth
                @if($item->user_id !== auth()->id())
                    <a href="{{ route('transactions.checkout', $item->id) }}"
                       class="btn btn-sm w-100 rounded-4 py-2 mb-3 text-white"
                       style="background: #22c55e; border: none; font-size: 0.9rem;">
                        <i class="bi bi-{{ $item->type == 'gift' ? 'gift' : 'cart' }} me-2"></i>
                        {{ $item->type == 'gift' ? 'Ambil Gratis' : 'Beli Sekarang' }}
                    </a>
                @else
                    <div class="alert alert-info rounded-3 py-2 mb-3" style="background: rgba(13,202,240,0.05); border: none;">
                        <small><i class="bi bi-info-circle me-1"></i> Ini barangmu - <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none" style="color: #22c55e;">Kelola</a></small>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-sm w-100 rounded-4 py-2 mb-3 text-white" style="background: #22c55e; border: none;">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk {{ $item->type == 'gift' ? 'Ambil' : 'Beli' }}
                </a>
            @endauth

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <h6 class="fw-semibold small mb-2" style="color: #1A2A24;">
                    <i class="bi bi-file-text me-1" style="color: #22c55e;"></i>Deskripsi
                </h6>
                <div class="p-3 rounded-3" style="background: #F8FBF8;">
                    <p class="small mb-0 text-secondary" style="line-height: 1.6;">{{ nl2br(e($item->description)) }}</p>
                </div>
            </div>

            {{-- LEGACY MESSAGE MINI --}}
            <div class="p-3 rounded-3 mb-3" style="background: #F8FBF8; border-left: 3px solid #22c55e;">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0 mb-2" style="font-size: 0.6rem;">
                    <i class="bi bi-chat-quote me-1"></i>Legacy Message
                </span>
                <p class="small fst-italic mb-2" style="color: #1A2A24;">"{{ $item->legacy_message }}"</p>
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-circle" style="width: 28px; height: 28px; font-size: 0.7rem; background: #22c55e;">
                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                    </div>
                    <small class="text-secondary">{{ $item->user->name }}</small>
                    <small class="text-secondary">•</small>
                    <small class="text-secondary">{{ $item->created_at->format('d M Y') }}</small>
                </div>
            </div>

            {{-- SELLER INFO MINI --}}
            <div class="p-3 rounded-3 mb-3 border" style="border-color: #EDF2F0 !important;">
                <h6 class="fw-semibold small mb-2" style="color: #1A2A24;">
                    <i class="bi bi-person-circle me-1" style="color: #22c55e;"></i>Penjual
                </h6>
                <div class="d-flex align-items-center gap-2">
                    @if($item->user->profile_photo)
                        <img src="{{ Storage::url($item->user->profile_photo) }}" alt="" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle" style="width: 36px; height: 36px; font-size: 0.9rem; background: #22c55e;">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="fw-semibold small mb-0">{{ $item->user->name }}</p>
                        <div class="d-flex gap-2 small text-secondary" style="font-size: 0.65rem;">
                            <span><i class="bi bi-building"></i> {{ $item->user->school ?? 'Sekolah' }}</span>
                            <span><i class="bi bi-box"></i> {{ $item->user->items()->count() }} barang</span>
                        </div>
                    </div>
                    @if($item->user->phone)
                        <a href="https://wa.me/{{ $item->user->phone }}" target="_blank" class="btn btn-sm rounded-4 px-2 py-0 ms-auto" style="background: #25D366; color: white; font-size: 0.7rem;">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- STATS MINI --}}
            <div class="row g-1 mb-3">
                <div class="col-4">
                    <div class="text-center p-2 rounded-3" style="background: #F8FBF8;">
                        <i class="bi bi-eye" style="color: #22c55e; font-size: 0.9rem;"></i>
                        <span class="fw-semibold d-block small">{{ $item->views_count }}</span>
                        <small class="text-secondary" style="font-size: 0.55rem;">Dilihat</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-2 rounded-3" style="background: #F8FBF8;">
                        <i class="bi bi-heart" style="color: #dc3545; font-size: 0.9rem;"></i>
                        <span class="fw-semibold d-block small">{{ $item->wishlists_count ?? ($item->wishlists ? $item->wishlists->count() : 0) }}</span>
                        <small class="text-secondary" style="font-size: 0.55rem;">Wishlist</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-2 rounded-3" style="background: #F8FBF8;">
                        <i class="bi bi-clock-history" style="color: #6c757d; font-size: 0.9rem;"></i>
                        <span class="fw-semibold d-block small">{{ $item->created_at->diffForHumans(null, true) }}</span>
                        <small class="text-secondary" style="font-size: 0.55rem;">Lalu</small>
                    </div>
                </div>
            </div>

            {{-- SAFETY TIPS MINI --}}
            <div class="p-2 rounded-3" style="background: #F8FBF8;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check" style="color: #ffc107; font-size: 0.9rem;"></i>
                    <small class="text-secondary" style="font-size: 0.7rem;">Transaksi aman via WAKANDE</small>
                </div>
            </div>
        </div>
    </div>

    {{-- RELATED ITEMS --}}
    @if(isset($related_items) && $related_items->count() > 0)
        <div class="mt-4 pt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-semibold mb-0" style="color: #1A2A24;">Barang Serupa</h6>
                <a href="{{ route('catalog.index', ['category' => $item->category]) }}" class="small text-decoration-none" style="color: #22c55e;">
                    Lihat Semua <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                </a>
            </div>

            <div class="row g-2">
                @foreach($related_items->take(4) as $related)
                    <div class="col-6 col-md-3">
                        <x-item-card :item="$related" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Change main image
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;

        // Update active class
        document.querySelectorAll('.thumbnail-container').forEach(el => {
            el.classList.remove('active');
            el.style.borderColor = '#EDF2F0';
        });
        element.classList.add('active');
        element.style.borderColor = '#22c55e';
    }

    // Share item
    function shareItem() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $item->name }}',
                text: '{{ strip_tags($item->legacy_message) }}',
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link disalin!');
        }
    }

    // Toggle wishlist
    function toggleWishlist(itemId) {
        const btn = event.currentTarget;
        const icon = btn.querySelector('i');
        const text = document.getElementById('wishlistText');

        fetch(`/wishlist/toggle/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.status === 'added') {
                    icon.className = 'bi bi-heart-fill me-1';
                    icon.style.color = '#dc3545';
                    text.textContent = 'Disimpan';
                } else {
                    icon.className = 'bi bi-heart me-1';
                    icon.style.color = '';
                    text.textContent = 'Simpan';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Zoom on hover desktop
    document.getElementById('mainImage')?.addEventListener('mouseenter', function() {
        if (window.innerWidth >= 768) {
            this.style.transform = 'scale(1.02)';
        }
    });
    document.getElementById('mainImage')?.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
</script>
@endpush

@push('styles')
<style>
    .main-image-container {
        background: white;
        transition: all 0.2s;
    }

    .thumbnail-container {
        transition: all 0.2s;
        border-color: #EDF2F0 !important;
    }

    .thumbnail-container.active {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.1);
    }

    .thumbnail-container:hover {
        border-color: #22c55e !important;
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-weight: 600;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .main-image-container {
        background: #1A1A2C;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
    }

    [data-bs-theme="dark"] .border {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .text-secondary {
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] .thumbnail-container {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .thumbnail-container.active {
        border-color: #22c55e !important;
    }

    /* Pagination */
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
