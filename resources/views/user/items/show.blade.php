@extends('layouts.app')

@section('title', $item->name . ' - WAKANDE')

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary btn-rounded">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>

        <div class="d-flex gap-2">
            @if($item->status == 'pending')
                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-primary btn-rounded">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <button type="button" class="btn btn-outline-danger btn-rounded" onclick="confirmDelete()">
                    <i class="bi bi-trash me-2"></i>Hapus
                </button>
                <form id="delete-form" action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>

    <div class="row g-5">
        <!-- Image Gallery -->
        <div class="col-lg-6">
            <div class="position-sticky" style="top: 100px;">
                @php
                    $images = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []) ?? [];
                    $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                @endphp

                <!-- Main Image -->
                <div class="main-image-container mb-3 rounded-4 overflow-hidden" style="background: #f8f9fa; aspect-ratio: 1;">
                    <img src="{{ $firstImage }}" alt="{{ $item->name }}" id="mainImage" class="w-100 h-100" style="object-fit: contain;">
                </div>

                <!-- Thumbnails -->
                @if(count($images) > 1)
                    <div class="row g-2">
                        @foreach($images as $index => $image)
                            <div class="col-3">
                                <div class="thumbnail-container rounded-3 overflow-hidden {{ $index == 0 ? 'active' : '' }}" onclick="changeMainImage('{{ Storage::url($image) }}', this)" style="aspect-ratio: 1; cursor: pointer;">
                                    <img src="{{ Storage::url($image) }}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Status Badge Mobile -->
                <div class="d-block d-lg-none mt-4">
                    <div class="d-flex gap-2">
                        @if($item->status == 'pending')
                            <span class="badge bg-warning rounded-pill px-4 py-3 w-100">
                                <i class="bi bi-clock me-2"></i>Menunggu Moderasi Admin
                            </span>
                        @elseif($item->status == 'approved')
                            <span class="badge bg-success rounded-pill px-4 py-3 w-100">
                                <i class="bi bi-check-circle me-2"></i>Disetujui - Tampil di Katalog
                            </span>
                        @elseif($item->status == 'rejected')
                            <span class="badge bg-danger rounded-pill px-4 py-3 w-100">
                                <i class="bi bi-exclamation-circle me-2"></i>Ditolak Admin
                            </span>
                        @elseif($item->status == 'sold')
                            <span class="badge bg-secondary rounded-pill px-4 py-3 w-100">
                                <i class="bi bi-tag me-2"></i>Terjual/Terdonasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details -->
        <div class="col-lg-6">
            <!-- Badges -->
            <div class="d-flex gap-2 mb-3">
                @if($item->type == 'gift')
                    <span class="badge bg-success rounded-pill px-4 py-2">
                        <i class="bi bi-gift me-1"></i>Gratis
                    </span>
                @else
                    <span class="badge bg-primary rounded-pill px-4 py-2">
                        <i class="bi bi-tag me-1"></i>Dijual
                    </span>
                @endif

                <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                    {{ $item->category_label }}
                </span>

                <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                    {{ $item->condition_label }}
                </span>
            </div>

            <!-- Title -->
            <h1 class="display-6 fw-bold mb-3">{{ $item->name }}</h1>

            <!-- Price -->
            <div class="mb-4">
                @if($item->type == 'sale')
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="h2 fw-bold" style="color: #667eea;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-secondary">+ admin Rp1.000</span>
                    </div>
                @else
                    <span class="h2 fw-bold text-success">Gratis</span>
                @endif
            </div>

            <!-- Status Desktop -->
            <div class="d-none d-lg-block mb-4">
                <div class="p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle" style="color: #667eea;"></i>
                        @if($item->status == 'pending')
                            <span class="text-warning fw-semibold">Barang sedang menunggu moderasi admin</span>
                        @elseif($item->status == 'approved')
                            <span class="text-success fw-semibold">Barang disetujui dan tampil di katalog</span>
                        @elseif($item->status == 'rejected')
                            <span class="text-danger fw-semibold">Barang ditolak oleh admin</span>
                        @elseif($item->status == 'sold')
                            <span class="text-secondary fw-semibold">Barang sudah terjual/terdonasi</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rejection Reason -->
            @if($item->status == 'rejected' && $item->rejection_reason)
                <div class="alert alert-danger rounded-4 border-0 mb-4">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan:
                    </h6>
                    <p class="mb-0 small">{{ $item->rejection_reason }}</p>
                    @if(isset($approval_log))
                        <hr class="my-2 opacity-25">
                        <small class="d-block text-danger opacity-75">
                            Dimoderasi oleh {{ $approval_log->admin->name }} • {{ $approval_log->created_at->diffForHumans() }}
                        </small>
                    @endif
                </div>
            @endif

            <!-- Description -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Deskripsi Barang</h6>
                <p class="text-secondary" style="line-height: 1.8;">{{ nl2br(e($item->description)) }}</p>
            </div>

            <!-- Legacy Message -->
            <div class="legacy-message p-4 rounded-4 mb-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-quote fs-1" style="color: #667eea; opacity: 0.5;"></i>
                    <div>
                        <p class="fw-semibold mb-2">Legacy Message 💫</p>
                        <p class="mb-2 fst-italic" style="font-size: 1.1rem;">"{{ $item->legacy_message }}"</p>
                        <p class="small text-secondary mb-0">
                            — {{ $item->user->name }}, {{ $item->user->school ?? 'Sekolah' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seller Info -->
            <div class="seller-info p-4 rounded-4 mb-4">
                <h6 class="fw-bold mb-3">Informasi Penjual</h6>
                <div class="d-flex align-items-center gap-3">
                    @if($item->user->profile_photo)
                        <img src="{{ Storage::url($item->user->profile_photo) }}" alt="{{ $item->user->name }}" class="rounded-circle" width="64" height="64" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle" style="width: 64px; height: 64px; font-size: 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="fw-bold mb-1">{{ $item->user->name }}</h5>
                        <p class="text-secondary small mb-2">{{ $item->user->school ?? 'Sekolah tidak tersedia' }} • Kelas {{ $item->user->grade ?? '-' }}</p>
                        <div class="d-flex gap-3">
                            <span class="small text-secondary">
                                <i class="bi bi-box me-1"></i>{{ $item->user->items->count() }} barang
                            </span>
                            <span class="small text-secondary">
                                <i class="bi bi-check-circle me-1"></i>Member since {{ $item->user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row g-3">
                <div class="col-4">
                    <div class="text-center p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                        <i class="bi bi-eye fs-5 d-block mb-1" style="color: #667eea;"></i>
                        <span class="fw-bold">{{ $item->views_count }}</span>
                        <small class="d-block text-secondary">Dilihat</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                        <i class="bi bi-heart fs-5 d-block mb-1" style="color: #dc3545;"></i>
                        <span class="fw-bold">{{ $item->wishlists->count() }}</span>
                        <small class="d-block text-secondary">Wishlist</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                        <i class="bi bi-calendar fs-5 d-block mb-1" style="color: #6c757d;"></i>
                        <span class="fw-bold">{{ $item->created_at->format('d M') }}</span>
                        <small class="d-block text-secondary">Diupload</small>
                    </div>
                </div>
            </div>

            <!-- Approval Info -->
            @if($item->approved_at)
                <div class="mt-4 p-3 rounded-4 text-center" style="background: rgba(25,135,84,0.05);">
                    <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Disetujui pada {{ $item->approved_at->format('d F Y H:i') }}
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Change main image
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;

        // Update active class
        document.querySelectorAll('.thumbnail-container').forEach(el => {
            el.classList.remove('active');
        });
        element.classList.add('active');
    }

    // Delete confirmation
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Barang?',
            text: 'Barang yang dihapus tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .main-image-container {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .thumbnail-container {
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .thumbnail-container.active {
        border-color: #667eea;
    }

    .thumbnail-container:hover {
        opacity: 0.8;
    }

    .legacy-message {
        background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
        border-left: 6px solid #667eea;
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
</style>
@endpush
@endsection
