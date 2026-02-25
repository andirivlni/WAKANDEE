@extends('layouts.app')

@section('title', $item->name . ' - WAKANDE')

@section('content')
<div class="container py-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <a href="{{ route('items.index') }}" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
           style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24;">
            <i class="bi bi-arrow-left me-1" style="font-size: 0.8rem;"></i> Kembali
        </a>

        @if($item->status == 'pending')
        <div class="d-flex gap-1">
            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
               style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #22c55e;">
                <i class="bi bi-pencil me-1" style="font-size: 0.8rem;"></i> Edit
            </a>
            <button type="button" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
                    style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #dc3545;"
                    onclick="confirmDelete()">
                <i class="bi bi-trash me-1" style="font-size: 0.8rem;"></i> Hapus
            </button>
            <form id="delete-form" action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- LEFT COLUMN - IMAGES --}}
        <div class="col-lg-6">
            <div class="position-sticky" style="top: 80px;">
                @php
                    $images = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []) ?? [];
                    $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                @endphp

                {{-- MAIN IMAGE --}}
                <div class="main-image-container mb-2 rounded-3 overflow-hidden border" style="aspect-ratio: 1; background: #F8FBF8;">
                    <img src="{{ $firstImage }}" alt="{{ $item->name }}" id="mainImage" class="w-100 h-100" style="object-fit: contain;">
                </div>

                {{-- THUMBNAILS --}}
                @if(count($images) > 1)
                    <div class="d-flex gap-1 overflow-auto pb-1" style="scrollbar-width: thin;">
                        @foreach($images as $index => $image)
                            <div class="thumbnail-container flex-shrink-0 rounded-2 overflow-hidden border {{ $index == 0 ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ Storage::url($image) }}', this)"
                                 style="width: 60px; height: 60px; cursor: pointer;">
                                <img src="{{ Storage::url($image) }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- STATUS BADGE MOBILE --}}
                <div class="d-block d-lg-none mt-3">
                    @include('partials.item-status-badge', ['status' => $item->status, 'full' => true])
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
                    {{ $item->category_label }}
                </span>

                <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #F0F5F0 !important;">
                    {{ $item->condition_label }}
                </span>
            </div>

            {{-- TITLE --}}
            <h4 class="fw-bold mb-2" style="color: #1A2A24;">{{ $item->name }}</h4>

            {{-- PRICE --}}
            <div class="mb-3">
                @if($item->type == 'sale')
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="fw-bold" style="color: #22c55e; font-size: 1.3rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <small class="text-secondary">+ Rp1.000 admin</small>
                    </div>
                @else
                    <span class="fw-bold" style="color: #22c55e; font-size: 1.3rem;">Gratis</span>
                @endif
            </div>

            {{-- STATUS DESKTOP --}}
            <div class="d-none d-lg-block mb-3">
                @include('partials.item-status-badge', ['status' => $item->status, 'full' => true])
            </div>

            {{-- REJECTION REASON --}}
            @if($item->status == 'rejected' && $item->rejection_reason)
                <div class="p-2 rounded-3 mb-3" style="background: rgba(220,53,69,0.05); border-left: 2px solid #dc3545;">
                    <h6 class="fw-semibold small mb-1" style="color: #dc3545;">
                        <i class="bi bi-exclamation-triangle me-1"></i>Alasan Penolakan:
                    </h6>
                    <p class="small mb-1" style="color: #dc3545;">{{ $item->rejection_reason }}</p>
                    @if(isset($approval_log))
                        <small class="text-secondary" style="font-size: 0.6rem;">
                            Dimoderasi oleh {{ $approval_log->admin->name }} • {{ $approval_log->created_at->diffForHumans() }}
                        </small>
                    @endif
                </div>
            @endif

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <h6 class="fw-semibold small mb-2" style="color: #1A2A24;">Deskripsi</h6>
                <div class="p-3 rounded-3" style="background: #F8FBF8;">
                    <p class="small mb-0 text-secondary" style="line-height: 1.6;">{{ nl2br(e($item->description)) }}</p>
                </div>
            </div>

            {{-- LEGACY MESSAGE --}}
            <div class="p-3 rounded-3 mb-3" style="background: #F8FBF8; border-left: 3px solid #22c55e;">
                <div class="d-flex gap-2">
                    <i class="bi bi-quote" style="color: #22c55e; font-size: 1.5rem; opacity: 0.5;"></i>
                    <div>
                        <p class="fw-semibold small mb-1" style="color: #1A2A24;">Pesan Warisan</p>
                        <p class="small fst-italic mb-1" style="color: #1A2A24;">"{{ $item->legacy_message }}"</p>
                        <p class="small text-secondary mb-0">— {{ $item->user->name }}</p>
                    </div>
                </div>
            </div>

            {{-- SELLER INFO MINI --}}
            <div class="p-3 rounded-3 mb-3 border" style="border-color: #EDF2F0 !important;">
                <h6 class="fw-semibold small mb-2" style="color: #1A2A24;">Informasi Penjual</h6>
                <div class="d-flex align-items-center gap-2">
                    @if($item->user->profile_photo)
                        <img src="{{ Storage::url($item->user->profile_photo) }}" alt="" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #22c55e; color: white; font-weight: 600;">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="fw-semibold small mb-0">{{ $item->user->name }}</p>
                        <div class="d-flex gap-2 small text-secondary" style="font-size: 0.65rem;">
                            <span><i class="bi bi-building"></i> {{ $item->user->school ?? 'Sekolah' }}</span>
                            <span><i class="bi bi-box"></i> {{ $item->user->items->count() }} barang</span>
                        </div>
                    </div>
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
                        <span class="fw-semibold d-block small">{{ $item->wishlists->count() }}</span>
                        <small class="text-secondary" style="font-size: 0.55rem;">Wishlist</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-2 rounded-3" style="background: #F8FBF8;">
                        <i class="bi bi-calendar" style="color: #6c757d; font-size: 0.9rem;"></i>
                        <span class="fw-semibold d-block small">{{ $item->created_at->format('d M') }}</span>
                        <small class="text-secondary" style="font-size: 0.55rem;">Diupload</small>
                    </div>
                </div>
            </div>

            {{-- APPROVAL INFO --}}
            @if($item->approved_at)
                <div class="p-2 rounded-3 text-center" style="background: rgba(25,135,84,0.05);">
                    <small class="text-success" style="font-size: 0.65rem;">
                        <i class="bi bi-check-circle me-1"></i>
                        Disetujui {{ $item->approved_at->diffForHumans() }}
                    </small>
                </div>
            @endif
        </div>
    </div>
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
            cancelButtonText: 'Batal',
            background: 'var(--bs-body-bg)',
            color: 'var(--bs-body-color)',
            customClass: {
                popup: 'rounded-4'
            }
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
        background: #F8FBF8;
        transition: all 0.2s;
    }

    .thumbnail-container {
        border: 2px solid #EDF2F0;
        transition: all 0.2s;
    }

    .thumbnail-container.active {
        border-color: #22c55e !important;
    }

    .thumbnail-container:hover {
        opacity: 0.8;
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

    [data-bs-theme="dark"] .border {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .thumbnail-container {
        border-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .thumbnail-container.active {
        border-color: #22c55e !important;
    }

    [data-bs-theme="dark"] [style*="background: #F0F5F0"] {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] [style*="color: #1A2A24"] {
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .text-secondary {
        color: #9CA3AF !important;
    }
</style>
@endpush
