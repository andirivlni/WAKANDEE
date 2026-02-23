@extends('layouts.app')

@section('title', $item->name . ' - WAKANDE')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none text-secondary">Katalog</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index', ['category' => $item->category]) }}" class="text-decoration-none text-secondary">{{ $item->category_label }}</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ Str::limit($item->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Left Column - Image Gallery -->
        <div class="col-lg-7">
            <div class="position-sticky" style="top: 100px;">
                @php
                    $images = $item->images ?? [];
                    if (is_string($images)) {
                        $images = is_array($images) ? $images : (is_string($images) ? json_decode($images, true) : []) ?? [];
                    }
                    $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                @endphp

                <!-- Main Image -->
                <div class="main-image-container mb-3 rounded-4 overflow-hidden" style="background: white; aspect-ratio: 1; border: 1px solid rgba(0,0,0,0.02);">
                    <img src="{{ $firstImage }}"
                         alt="{{ $item->name }}"
                         id="mainImage"
                         class="w-100 h-100"
                         style="object-fit: contain; transition: transform 0.3s;"
                         onmouseover="this.style.transform='scale(1.02)'"
                         onmouseout="this.style.transform='scale(1)'">
                </div>

                <!-- Thumbnails -->
                @php
                    $thumbnailImages = is_array($images) ? $images : [];
                @endphp

                @if(count($thumbnailImages) > 1)
                    <div class="row g-2">
                        @foreach($thumbnailImages as $index => $image)
                            @if(!empty($image))
                            <div class="col-3">
                                <div class="thumbnail-container rounded-3 overflow-hidden {{ $index == 0 ? 'active' : '' }}"
                                     onclick="changeMainImage('{{ Storage::url($image) }}', this)"
                                     style="aspect-ratio: 1; cursor: pointer; border: 2px solid transparent;">
                                    <img src="{{ Storage::url($image) }}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Share & Save -->
                <div class="d-flex gap-3 mt-4">
                    <button class="btn btn-outline-secondary rounded-pill grow py-3" onclick="shareItem()">
                        <i class="bi bi-share me-2"></i>Bagikan
                    </button>
                    @auth
                        <button class="btn {{ $is_wishlisted ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill grow py-3" id="wishlistBtn" onclick="toggleWishlist({{ $item->id }})">
                            <i class="bi bi-heart{{ $is_wishlisted ? '-fill' : '' }} me-2"></i>
                            <span id="wishlistText">{{ $is_wishlisted ? 'Di Wishlist' : 'Simpan' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill grow py-3">
                            <i class="bi bi-heart me-2"></i>Simpan
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Right Column - Item Details -->
        <div class="col-lg-5">
            <!-- Badges -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                @if($item->type == 'gift')
                    <span class="badge bg-success rounded-pill px-4 py-3">
                        <i class="bi bi-gift me-2"></i>Gratis
                    </span>
                @else
                    <span class="badge bg-success rounded-pill px-4 py-3">
                        <i class="bi bi-tag me-2"></i>Dijual
                    </span>
                @endif

                <span class="badge bg-light text-dark rounded-pill px-4 py-3">
                    <i class="bi bi-book me-2"></i>{{ $item->category_label }}
                </span>

                <span class="badge bg-light text-dark rounded-pill px-4 py-3">
                    <i class="bi bi-check-circle me-2"></i>{{ $item->condition_label }}
                </span>
            </div>

            <!-- Title -->
            <h1 class="display-6 fw-bold mb-3">{{ $item->name }}</h1>

            <!-- Price -->
            <div class="mb-4 p-4 rounded-4" style="background: rgba(34, 197, 94, 0.05);">
                @if($item->type == 'sale')
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="display-5 fw-bold" style="color: #22c55e;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-secondary">+ admin Rp1.000</span>
                    </div>
                    <small class="text-secondary d-block mt-2">
                        <i class="bi bi-info-circle"></i> Biaya admin Rp1.000 untuk transaksi berbayar
                    </small>
                @else
                    <span class="display-5 fw-bold text-success">Gratis</span>
                    <small class="text-secondary d-block mt-2">
                        <i class="bi bi-gift"></i> Barang hibah dari kakak kelas
                    </small>
                @endif
            </div>

            <!-- Action Button -->
            @auth
                @if($item->user_id !== auth()->id())
                    <div class="d-grid mb-4">
                        <a href="{{ route('transactions.checkout', $item->id) }}" class="btn btn-success btn-rounded py-3" style="background: #22c55e; border: none;">
                            <i class="bi bi-{{ $item->type == 'gift' ? 'gift' : 'cart' }} me-2"></i>
                            {{ $item->type == 'gift' ? 'Ambil Gratis' : 'Beli Sekarang' }}
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                @else
                    <div class="alert alert-info rounded-4 border-0 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-info-circle-fill fs-4"></i>
                            <div>
                                <p class="fw-semibold mb-1">Ini adalah barangmu</p>
                                <p class="small mb-0">
                                    <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none" style="color: #22c55e;">Kelola barang</a>
                                    atau
                                    <a href="{{ route('catalog.index') }}" class="text-decoration-none" style="color: #22c55e;">cari barang lain</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="d-grid mb-4">
                    <a href="{{ route('login') }}" class="btn btn-success btn-rounded py-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login untuk {{ $item->type == 'gift' ? 'Ambil Gratis' : 'Beli' }}
                    </a>
                </div>
            @endauth

            <!-- Description -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-file-text me-2" style="color: #22c55e;"></i>Deskripsi Barang
                </h6>
                <div class="p-4 rounded-4" style="background: rgba(34, 197, 94,0.02);">
                    <p class="text-secondary mb-0" style="line-height: 1.8;">{{ nl2br(e($item->description)) }}</p>
                </div>
            </div>

            <!-- Legacy Message -->
            <div class="legacy-message p-4 rounded-4 mb-4">
                <div class="d-flex gap-3">
                    <div>
                        <i class="bi bi-quote fs-1" style="color: #22c55e; opacity: 0.3;"></i>
                    </div>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2">
                            <i class="bi bi-chat-quote me-1"></i>Legacy Message
                        </span>
                        <p class="fw-light fst-italic mb-3" style="font-size: 1.2rem; color: var(--bs-body-color);">
                            "{{ $item->legacy_message }}"
                        </p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle" style="width: 48px; height: 48px; background: #22c55e;">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="fw-semibold mb-1">{{ $item->user->name }}</p>
                                <small class="text-secondary">
                                    <i class="bi bi-building me-1"></i>{{ $item->user->school ?? 'Sekolah' }} •
                                    <i class="bi bi-calendar me-1"></i>{{ $item->created_at->format('d F Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seller Info -->
            <div class="seller-info p-4 rounded-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-person-circle me-2" style="color: #22c55e;"></i>Informasi Penjual
                </h6>
                <div class="d-flex align-items-center gap-3">
                    @if($item->user->profile_photo)
                        <img src="{{ Storage::url($item->user->profile_photo) }}" alt="{{ $item->user->name }}" class="rounded-circle" width="64" height="64" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle" style="width: 64px; height: 64px; font-size: 1.5rem; background: #22c55e;">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="fw-bold mb-1">{{ $item->user->name }}</h5>
                        <div class="d-flex flex-wrap gap-3 mb-2">
                            <small class="text-secondary">
                                <i class="bi bi-building me-1"></i>{{ $item->user->school ?? 'Sekolah tidak tersedia' }}
                            </small>
                            <small class="text-secondary">
                                <i class="bi bi-box me-1"></i>{{ $item->user->items()->count() }} barang
                            </small>
                            <small class="text-secondary">
                                <i class="bi bi-calendar-check me-1"></i>Bergabung {{ $item->user->created_at->format('M Y') }}
                            </small>
                        </div>
                        @if($item->user->phone)
                            <a href="https://wa.me/{{ $item->user->phone }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                <i class="bi bi-whatsapp me-1"></i>Hubungi via WA
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row g-3">
                <div class="col-4">
                    <div class="text-center p-3 rounded-4" style="background: rgba(34, 197, 94,0.02);">
                        <i class="bi bi-eye fs-4 d-block mb-2" style="color: #22c55e;"></i>
                        <span class="fw-bold d-block">{{ $item->views_count }}</span>
                        <small class="text-secondary">Dilihat</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-4" style="background: rgba(34, 197, 94,0.02);">
                        <i class="bi bi-heart fs-4 d-block mb-2" style="color: #dc3545;"></i>
                        <span class="fw-bold d-block">
                            {{ $item->wishlists_count ?? ($item->wishlists ? $item->wishlists->count() : 0) }}
                        </span>
                        <small class="text-secondary">Wishlist</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-4" style="background: rgba(34, 197, 94,0.02);">
                        <i class="bi bi-clock-history fs-4 d-block mb-2" style="color: #6c757d;"></i>
                        <span class="fw-bold d-block">{{ $item->created_at->diffForHumans() }}</span>
                        <small class="text-secondary">Diupload</small>
                    </div>
                </div>
            </div>

            <!-- Safety Tips -->
            <div class="alert alert-warning rounded-4 border-0 mt-4" style="background: rgba(255,193,7,0.05);">
                <div class="d-flex gap-3">
                    <i class="bi bi-shield-check fs-4" style="color: #ffc107;"></i>
                    <div>
                        <h6 class="fw-semibold mb-2">Tips Aman Bertransaksi</h6>
                        <ul class="small text-secondary mb-0" style="padding-left: 1rem;">
                            <li>Lakukan transaksi hanya di platform WAKANDE</li>
                            <li>Serah terima barang di Drop-off Point sekolah</li>
                            <li>Jangan transfer ke rekening pribadi</li>
                            <li>Konfirmasi penerimaan barang di aplikasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Items -->
    @if(isset($related_items) && $related_items->count() > 0)
        <div class="mt-5 pt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Barang Serupa</h4>
                    <p class="text-secondary mb-0">Temukan barang lain dalam kategori {{ $item->category_label }}</p>
                </div>
                <a href="{{ route('catalog.index', ['category' => $item->category]) }}" class="btn btn-link text-decoration-none" style="color: #22c55e;">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($related_items as $related)
                    <div class="col-lg-3 col-md-6">
                        <x-item-card :item="$related" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Change main image
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;

        // Update active class
        document.querySelectorAll('.thumbnail-container').forEach(el => {
            el.classList.remove('active');
            el.style.borderColor = 'transparent';
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
            })
            .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback
            navigator.clipboard.writeText(window.location.href);
            alert('Link katalog berhasil disalin!');
        }
    }

    // Toggle wishlist
    function toggleWishlist(itemId) {
        const btn = document.getElementById('wishlistBtn');
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
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-danger');
                    icon.className = 'bi bi-heart-fill me-2';
                    text.textContent = 'Di Wishlist';
                } else {
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-secondary');
                    icon.className = 'bi bi-heart me-2';
                    text.textContent = 'Simpan';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Zoom image on click (for mobile)
    document.getElementById('mainImage')?.addEventListener('click', function() {
        if (window.innerWidth < 768) {
            // Open image in new tab for mobile
            window.open(this.src, '_blank');
        }
    });
</script>
@endpush

@push('styles')
<style>
    .main-image-container {
        background: white;
        transition: all 0.3s;
    }

    .thumbnail-container.active {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 3px rgba(34, 197, 94,0.1);
    }

    .legacy-message {
        background: rgba(34, 197, 94, 0.05);
        border-left: 6px solid #22c55e;
    }

    .seller-info {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    [data-bs-theme="dark"] .main-image-container {
        background: #1a1a2c;
    }

    [data-bs-theme="dark"] .seller-info {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .thumbnail-container {
        background: #1a1a2c;
    }

    .breadcrumb a:hover {
        color: #22c55e !important;
    }
</style>
@endpush
@endsection
