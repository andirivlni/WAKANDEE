@extends('layouts.app')

@section('title', 'Barang Saya - WAKANDE')

@section('content')
<div class="container py-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 36px; height: 36px; background: rgba(34, 197, 94, 0.1);">
                <i class="bi bi-box-seam" style="color: #22c55e; font-size: 1rem;"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color: #1A2A24;">Barang Saya</h5>
                <p class="small text-secondary mb-0" style="font-size: 0.75rem;">Kelola semua barang yang telah kamu upload</p>
            </div>
        </div>
        <a href="{{ route('items.create') }}" class="btn btn-sm rounded-5 px-3 py-1"
           style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
            <i class="bi bi-plus-circle me-1"></i>Upload Barang
        </a>
    </div>

    {{-- STATS TABS MINI --}}
    <div class="row g-2 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-tab p-2 rounded-3 text-center {{ !request('status') ? 'active' : '' }}"
                 onclick="window.location='{{ route('items.index') }}'"
                 style="cursor: pointer; background: #F8FBF8; border: 1px solid #EDF2F0;">
                <span class="fw-bold d-block" style="color: #1A2A24; font-size: 1rem;">{{ $items->total() }}</span>
                <small class="text-secondary" style="font-size: 0.65rem;">Semua</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-tab p-2 rounded-3 text-center {{ request('status') == 'pending' ? 'active' : '' }}"
                 onclick="window.location='{{ route('items.index', ['status' => 'pending']) }}'"
                 style="cursor: pointer; background: #F8FBF8; border: 1px solid #EDF2F0;">
                <span class="fw-bold d-block" style="color: #ffc107; font-size: 1rem;">{{ $items->where('status', 'pending')->count() }}</span>
                <small class="text-secondary" style="font-size: 0.65rem;">Menunggu</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-tab p-2 rounded-3 text-center {{ request('status') == 'approved' ? 'active' : '' }}"
                 onclick="window.location='{{ route('items.index', ['status' => 'approved']) }}'"
                 style="cursor: pointer; background: #F8FBF8; border: 1px solid #EDF2F0;">
                <span class="fw-bold d-block" style="color: #198754; font-size: 1rem;">{{ $items->where('status', 'approved')->count() }}</span>
                <small class="text-secondary" style="font-size: 0.65rem;">Disetujui</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-tab p-2 rounded-3 text-center {{ request('status') == 'rejected' ? 'active' : '' }}"
                 onclick="window.location='{{ route('items.index', ['status' => 'rejected']) }}'"
                 style="cursor: pointer; background: #F8FBF8; border: 1px solid #EDF2F0;">
                <span class="fw-bold d-block" style="color: #dc3545; font-size: 1rem;">{{ $items->where('status', 'rejected')->count() }}</span>
                <small class="text-secondary" style="font-size: 0.65rem;">Ditolak</small>
            </div>
        </div>
    </div>

    {{-- ITEMS GRID --}}
    @if($items->count() > 0)
        <div class="row g-3">
            @foreach($items as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="item-card h-100 p-2" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                        {{-- Image --}}
                        <div class="position-relative rounded-3 overflow-hidden mb-2" style="aspect-ratio: 1;">
                            @php
                                $images = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []) ?? [];
                                $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                            @endphp
                            <img src="{{ $firstImage }}" alt="{{ $item->name }}" class="w-100 h-100" style="object-fit: cover;">

                            {{-- Status Badges --}}
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning" style="font-size: 0.6rem;">
                                        <i class="bi bi-clock me-1" style="font-size: 0.5rem;"></i>Menunggu
                                    </span>
                                @elseif($item->status == 'approved')
                                    <span class="badge bg-success" style="font-size: 0.6rem;">
                                        <i class="bi bi-check-circle me-1" style="font-size: 0.5rem;"></i>Disetujui
                                    </span>
                                @elseif($item->status == 'rejected')
                                    <span class="badge bg-danger" style="font-size: 0.6rem;">
                                        <i class="bi bi-exclamation-circle me-1" style="font-size: 0.5rem;"></i>Ditolak
                                    </span>
                                @elseif($item->status == 'sold')
                                    <span class="badge bg-secondary" style="font-size: 0.6rem;">
                                        <i class="bi bi-tag me-1" style="font-size: 0.5rem;"></i>Terjual
                                    </span>
                                @endif
                            </div>

                            {{-- Type Badge --}}
                            <div class="position-absolute top-0 start-0 m-2">
                                @if($item->type == 'gift')
                                    <span class="badge bg-success" style="font-size: 0.6rem;">
                                        <i class="bi bi-gift me-1" style="font-size: 0.5rem;"></i>Gratis
                                    </span>
                                @else
                                    <span class="badge bg-success" style="font-size: 0.6rem;">
                                        <i class="bi bi-tag me-1" style="font-size: 0.5rem;"></i>Dijual
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="px-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">{{ Str::limit($item->name, 30) }}</h6>
                                <small class="text-secondary" style="font-size: 0.6rem;">
                                    <i class="bi bi-eye"></i> {{ $item->views_count }}
                                </small>
                            </div>

                            <div class="d-flex gap-1 mb-2">
                                <span class="badge rounded-pill" style="font-size: 0.55rem; background: #F0F5F0; color: #1A2A24;">{{ $item->category_label }}</span>
                                <span class="badge rounded-pill" style="font-size: 0.55rem; background: #F0F5F0; color: #1A2A24;">{{ $item->condition_label }}</span>
                            </div>

                            @if($item->status == 'rejected' && $item->rejection_reason)
                                <div class="p-2 rounded-2 mb-2" style="background: rgba(220,53,69,0.05);">
                                    <small class="d-block text-danger" style="font-size: 0.6rem;">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ Str::limit($item->rejection_reason, 50) }}
                                    </small>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($item->type == 'sale')
                                        <span class="fw-semibold" style="color: #22c55e; font-size: 0.8rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="fw-semibold text-success" style="font-size: 0.8rem;">Gratis</span>
                                    @endif
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm rounded-4 px-2 py-0" style="font-size: 0.65rem; border: 1px solid #EDF2F0; color: #1A2A24;">
                                        Detail
                                    </a>
                                    @if($item->status == 'pending')
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm rounded-4 px-2 py-0" style="font-size: 0.65rem; background: #22c55e; color: white; border: none;">
                                            Edit
                                        </a>
                                    @endif
                                </div>
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
        {{-- EMPTY STATE MINI --}}
        <div class="text-center py-4 rounded-4" style="background: #F8FBF8;">
            <div class="mb-2">
                <i class="bi bi-box-seam" style="color: #22c55e; opacity: 0.3; font-size: 2rem;"></i>
            </div>
            <h6 class="fw-semibold mb-1" style="color: #1A2A24; font-size: 1rem;">Belum Ada Barang</h6>
            <p class="small mb-3" style="max-width: 350px; margin: 0 auto; font-size: 0.8rem; color: #6c757d;">
                Kamu belum mengupload barang apapun. Yuk mulai berbagi dengan adik kelas!
            </p>
            <a href="{{ route('items.create') }}" class="btn btn-sm rounded-5 px-3 py-1"
               style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                <i class="bi bi-cloud-upload me-1"></i>Upload Barang Pertama
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .stat-tab {
        transition: all 0.2s;
        cursor: pointer;
        background: #F8FBF8 !important;
        border: 1px solid #EDF2F0 !important;
    }

    .stat-tab:hover {
        background: rgba(34, 197, 94, 0.05) !important;
        border-color: #22c55e !important;
    }

    .stat-tab.active {
        background: rgba(34, 197, 94, 0.1) !important;
        border-color: #22c55e !important;
    }

    .item-card {
        transition: all 0.2s;
        height: 100%;
        background: white;
        border: 1px solid #EDF2F0;
        border-radius: 16px;
    }

    .item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(34, 197, 94, 0.05);
        border-color: rgba(34, 197, 94, 0.2) !important;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .stat-tab,
    [data-bs-theme="dark"] .item-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] [style*="color: #1A2A24"] {
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] [style*="color: #6c757d"] {
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] [style*="background: #F0F5F0"] {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #E0E0E0 !important;
    }

    /* PAGINATION */
    .pagination {
        gap: 0.2rem;
    }

    .page-link {
        border-radius: 30px !important;
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        border: 1px solid #EDF2F0;
        color: #1A2A24;
        background: white;
    }

    .page-item.active .page-link {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
    }

    [data-bs-theme="dark"] .page-link {
        background: #1A1A2C;
        border-color: rgba(255, 255, 255, 0.1);
        color: #E0E0E0;
    }

    [data-bs-theme="dark"] .page-item.active .page-link {
        background: #22c55e;
        color: white;
    }
</style>
@endpush
