@extends('layouts.app')

@section('title', 'Dashboard - WAKANDE')

@section('content')
<div class="container py-4">
    {{-- WELCOME HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 44px; height: 44px; background: rgba(34, 197, 94, 0.1);">
                <span class="fw-semibold" style="color: #22c55e;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color: #1A2A24;">Halo, {{ Auth::user()->name }}!</h5>
                <small class="text-secondary">{{ now()->format('l, d F Y') }}</small>
            </div>
        </div>
        <a href="{{ route('items.create') }}"
           class="btn btn-sm rounded-5 px-4 py-2"
           style="background: #22c55e; border: none; color: white;">
            <i class="bi bi-cloud-upload me-1"></i>Upload Barang
        </a>
    </div>

    {{-- 4 STATS CARDS - RAPI & SERAGAM --}}
    <div class="row g-3 mb-4">
        {{-- Total --}}
        <div class="col-6 col-md-3">
            <div class="stats-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-semibold text-secondary">Total</span>
                    <div class="stats-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(34, 197, 94, 0.1);">
                        <i class="bi bi-box-seam" style="color: #22c55e; font-size: 1rem;"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <span class="h4 fw-bold mb-0" style="color: #1A2A24;">{{ $stats['total_items'] ?? 0 }}</span>
                    <span class="small text-secondary">Barang</span>
                </div>
                <div class="d-flex gap-2">
                    <small class="text-warning bg-warning bg-opacity-10 px-2 py-1 rounded-3">
                        <i class="bi bi-clock me-1" style="font-size: 0.7rem;"></i>{{ $stats['pending_items'] ?? 0 }} Pending
                    </small>
                    <small class="text-success bg-success bg-opacity-10 px-2 py-1 rounded-3">
                        <i class="bi bi-check-circle me-1" style="font-size: 0.7rem;"></i>{{ $stats['approved_items'] ?? 0 }} Disetujui
                    </small>
                </div>
            </div>
        </div>

        {{-- Terjual --}}
        <div class="col-6 col-md-3">
            <div class="stats-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-semibold text-secondary">Terjual</span>
                    <div class="stats-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(74, 222, 128, 0.1);">
                        <i class="bi bi-gift" style="color: #4ade80; font-size: 1rem;"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <span class="h4 fw-bold mb-0" style="color: #1A2A24;">{{ $stats['sold_items'] ?? 0 }}</span>
                    <span class="small text-secondary">Barang</span>
                </div>
                <div class="d-flex align-items-center">
                    <small class="text-success bg-success bg-opacity-10 px-2 py-1 rounded-3">
                        <i class="bi bi-arrow-up me-1" style="font-size: 0.7rem;"></i>+{{ rand(2, 8) }}%
                    </small>
                </div>
            </div>
        </div>

        {{-- Dibeli --}}
        <div class="col-6 col-md-3">
            <div class="stats-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-semibold text-secondary">Dibeli</span>
                    <div class="stats-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(25, 135, 84, 0.1);">
                        <i class="bi bi-cart-check" style="color: #198754; font-size: 1rem;"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <span class="h4 fw-bold mb-0" style="color: #1A2A24;">{{ $stats['bought_items'] ?? 0 }}</span>
                    <span class="small text-secondary">Barang</span>
                </div>
                <div class="d-flex align-items-center">
                    <small class="text-success bg-success bg-opacity-10 px-2 py-1 rounded-3">
                        <i class="bi bi-piggy-bank me-1" style="font-size: 0.7rem;"></i>Rp {{ number_format($stats['total_savings'] ?? 0, 0, ',', '.') }}
                    </small>
                </div>
            </div>
        </div>

        {{-- Wishlist --}}
        <div class="col-6 col-md-3">
            <div class="stats-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-semibold text-secondary">Wishlist</span>
                    <div class="stats-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: rgba(220, 53, 69, 0.1);">
                        <i class="bi bi-heart-fill" style="color: #dc3545; font-size: 1rem;"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <span class="h4 fw-bold mb-0" style="color: #1A2A24;">{{ $stats['wishlist_count'] ?? 0 }}</span>
                    <span class="small text-secondary">Barang</span>
                </div>
                <div>
                    <a href="{{ route('wishlist.index') }}" class="small text-decoration-none d-flex align-items-center" style="color: #22c55e;">
                        Lihat semua <i class="bi bi-arrow-right ms-1" style="font-size: 0.8rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- PENDING ALERT (if any) --}}
    @if (isset($pending_items) && $pending_items->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show rounded-4 py-3 mb-4" style="background: rgba(255, 193, 7, 0.05); border: 1px solid rgba(255, 193, 7, 0.1);">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3" style="color: #FFB347;"></i>
                <small><strong>{{ $pending_items->count() }} barang</strong> menunggu moderasi</small>
                <button type="button" class="btn-close ms-auto" style="font-size: 0.8rem;" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- TWO COLUMN LAYOUT --}}
    <div class="row g-3">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-4">
            {{-- QUICK ACTIONS --}}
            <div class="dashboard-card p-3 mb-3">
                <h6 class="fw-semibold mb-3" style="font-size: 0.9rem;">
                    <i class="bi bi-lightning-charge me-2" style="color: #22c55e;"></i>Aksi Cepat
                </h6>
                <div class="vstack gap-2">
                    <a href="{{ route('items.create') }}" class="quick-link d-flex align-items-center justify-content-between p-2 rounded-3">
                        <small><i class="bi bi-cloud-upload me-2" style="color: #22c55e;"></i>Upload Barang</small>
                        <i class="bi bi-chevron-right" style="color: #22c55e; font-size: 0.8rem;"></i>
                    </a>
                    <a href="{{ route('catalog.index') }}" class="quick-link d-flex align-items-center justify-content-between p-2 rounded-3">
                        <small><i class="bi bi-search me-2" style="color: #22c55e;"></i>Cari Kebutuhan</small>
                        <i class="bi bi-chevron-right" style="color: #22c55e; font-size: 0.8rem;"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="quick-link d-flex align-items-center justify-content-between p-2 rounded-3">
                        <small><i class="bi bi-person me-2" style="color: #22c55e;"></i>Edit Profile</small>
                        <i class="bi bi-chevron-right" style="color: #22c55e; font-size: 0.8rem;"></i>
                    </a>
                </div>
            </div>

            {{-- PENDING ITEMS --}}
            @if (isset($pending_items) && $pending_items->count() > 0)
                <div class="dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">
                            <i class="bi bi-clock-history me-2" style="color: #FFB347;"></i>Menunggu Moderasi
                        </h6>
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $pending_items->count() }}</span>
                    </div>

                    <div class="vstack gap-2">
                        @foreach ($pending_items->take(3) as $item)
                            @php
                                $images = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []);
                                $thumb = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                            @endphp
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: #F8FBF8;">
                                <img src="{{ $thumb }}" alt="" width="36" height="36" style="object-fit: cover; border-radius: 8px;">
                                <div class="grow">
                                    <p class="fw-semibold small mb-0">{{ Str::limit($item->name, 20) }}</p>
                                    <small class="text-secondary" style="font-size: 0.65rem;">
                                        <i class="bi bi-clock"></i> {{ $item->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-link p-0" style="color: #22c55e; font-size: 0.7rem;">
                                    Detail
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-8">
            {{-- RECENT TRANSACTIONS --}}
            <div class="dashboard-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">
                        <i class="bi bi-arrow-left-right me-2" style="color: #22c55e;"></i>Transaksi Terbaru
                    </h6>
                    <a href="{{ route('transactions.index') }}" class="small text-decoration-none" style="color: #22c55e;">
                        Lihat semua <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                    </a>
                </div>

                @if (isset($recent_purchases) && $recent_purchases->count() > 0)
                    <div class="vstack gap-2">
                        @foreach ($recent_purchases->take(3) as $trx)
                            @php
                                $itemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []);
                                $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                $status = $trx->payment_status_label;
                            @endphp
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: #F8FBF8;">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $itemThumb }}" alt="" width="36" height="36" style="object-fit: cover; border-radius: 8px;">
                                    <div>
                                        <p class="fw-semibold small mb-0">{{ Str::limit($trx->item->name, 25) }}</p>
                                        <small class="text-secondary" style="font-size: 0.65rem;">{{ $trx->transaction_code }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="fw-semibold small">{{ $trx->formatted_total }}</span>
                                    <span class="badge rounded-pill px-2 py-1"
                                          style="background: rgba({{ $status['color'] == 'success' ? '40, 167, 69' : '255, 193, 7' }}, 0.1);
                                                 color: {{ $status['color'] == 'success' ? '#28a745' : '#ffc107' }}; font-size: 0.6rem;">
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-2" style="color: #22c55e; opacity: 0.2;"></i>
                        <p class="small text-secondary mt-2 mb-0">Belum ada transaksi</p>
                    </div>
                @endif
            </div>

            {{-- RECENT SALES --}}
            <div class="dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">
                        <i class="bi bi-truck me-2" style="color: #4ade80;"></i>Penjualan Terbaru
                    </h6>
                    <a href="{{ route('transactions.index') }}" class="small text-decoration-none" style="color: #22c55e;">
                        Lihat semua <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                    </a>
                </div>

                @if (isset($recent_sales) && $recent_sales->count() > 0)
                    <div class="vstack gap-2">
                        @foreach ($recent_sales->take(3) as $trx)
                            @php
                                $itemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []);
                                $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                $status = $trx->payment_status_label;
                            @endphp
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: #F8FBF8;">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $itemThumb }}" alt="" width="36" height="36" style="object-fit: cover; border-radius: 8px;">
                                    <div>
                                        <p class="fw-semibold small mb-0">{{ Str::limit($trx->item->name, 25) }}</p>
                                        <small class="text-secondary" style="font-size: 0.65rem;">{{ $trx->buyer->name }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="fw-semibold small">{{ $trx->formatted_total }}</span>
                                    <span class="badge rounded-pill px-2 py-1"
                                          style="background: rgba({{ $status['color'] == 'success' ? '40, 167, 69' : '255, 193, 7' }}, 0.1);
                                                 color: {{ $status['color'] == 'success' ? '#28a745' : '#ffc107' }}; font-size: 0.6rem;">
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-gift fs-2" style="color: #4ade80; opacity: 0.2;"></i>
                        <p class="small text-secondary mt-2 mb-0">Belum ada penjualan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* PROPORTIONAL DASHBOARD STYLES */
    .stats-card {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.02);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(34, 197, 94, 0.05);
        border-color: rgba(34, 197, 94, 0.1);
    }

    .stats-icon {
        transition: all 0.2s;
    }

    .stats-card:hover .stats-icon {
        transform: scale(1.05);
    }

    .dashboard-card {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.02);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .quick-link {
        background: #F8FBF8;
        transition: all 0.2s;
        color: #1A2A24;
        text-decoration: none;
    }

    .quick-link:hover {
        background: rgba(34, 197, 94, 0.05);
        transform: translateX(2px);
    }

    /* Small text adjustments */
    .small {
        font-size: 0.8rem;
    }

    .text-secondary {
        color: #4A5A54 !important;
    }

    /* Dark mode */
    [data-bs-theme="dark"] .stats-card,
    [data-bs-theme="dark"] .dashboard-card {
        background: #1A1A2C;
        border-color: rgba(255, 255, 255, 0.03);
    }

    [data-bs-theme="dark"] .quick-link,
    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }
</style>
@endpush
@endsection
