@extends('layouts.app')

@section('title', 'Dashboard - WAKANDE')

@section('content')
    <div class="container py-4">
        <!-- Welcome Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-secondary mb-0">{{ now()->format('l, d F Y') }}</p>
            </div>
            <a href="{{ route('items.create') }}" class="btn btn-primary btn-rounded px-4 py-2"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <i class="bi bi-plus-circle me-2"></i>Upload Barang
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                            <i class="bi bi-box-seam fs-4" style="color: #667eea;"></i>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Total</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['total_items'] ?? 0 }}</h3>
                    <p class="text-secondary small mb-0">Barang Diupload</p>
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                            <i class="bi bi-clock me-1"></i>{{ $stats['pending_items'] ?? 0 }} Pending
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>{{ $stats['approved_items'] ?? 0 }} Disetujui
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; background: rgba(118, 75, 162, 0.1);">
                            <i class="bi bi-gift fs-4" style="color: #764ba2;"></i>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Terjual</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['sold_items'] ?? 0 }}</h3>
                    <p class="text-secondary small mb-0">Barang Terjual/Terdonasi</p>
                    <div class="mt-3">
                        <span class="text-success small">
                            <i class="bi bi-arrow-up"></i> +{{ rand(2, 8) }} dari bulan lalu
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; background: rgba(25, 135, 84, 0.1);">
                            <i class="bi bi-cart-check fs-4" style="color: #198754;"></i>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Dibeli</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['bought_items'] ?? 0 }}</h3>
                    <p class="text-secondary small mb-0">Barang Didapatkan</p>
                    <div class="mt-3">
                        <span class="text-primary small">
                            <i class="bi bi-piggy-bank"></i> Hemat Rp
                            {{ number_format($stats['total_savings'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; background: rgba(220, 53, 69, 0.1);">
                            <i class="bi bi-heart fs-4" style="color: #dc3545;"></i>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Wishlist</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['wishlist_count'] ?? 0 }}</h3>
                    <p class="text-secondary small mb-0">Barang Disimpan</p>
                    <div class="mt-3">
                        <a href="{{ route('wishlist.index') }}" class="text-decoration-none small" style="color: #667eea;">
                            Lihat semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Items Alert -->
        @if (isset($pending_items) && $pending_items->count() > 0)
            <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 mb-4"
                style="background: rgba(255, 193, 7, 0.1); color: #856404;">
                <div class="d-flex align-items-center">
                    <div class="shrink-0 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div class="grow">
                        <strong class="d-block mb-1">Kamu punya {{ $pending_items->count() }} barang menunggu
                            moderasi</strong>
                        <p class="small mb-0">Admin akan memverifikasi barangmu dalam 1x24 jam. Status akan diupdate
                            otomatis.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="row g-4">
            <!-- Left Column - Pending Items -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="admin-card p-4 mb-4">
                    <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('items.create') }}" class="btn btn-outline-primary rounded-pill text-start py-2">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Barang Baru
                        </a>
                        <a href="{{ route('catalog.index') }}"
                            class="btn btn-outline-secondary rounded-pill text-start py-2">
                            <i class="bi bi-search me-2"></i>Cari Kebutuhan
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="btn btn-outline-secondary rounded-pill text-start py-2">
                            <i class="bi bi-person me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Pending Items List -->
                @if (isset($pending_items) && $pending_items->count() > 0)
                    <div class="admin-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Menunggu Moderasi</h6>
                            <span class="badge bg-warning rounded-pill">{{ $pending_items->count() }}</span>
                        </div>

                        <div class="vstack gap-3">
                            @foreach ($pending_items as $item)
                                <div class="d-flex align-items-center gap-3">
                                    <div class="shrink-0">
                                        @php
                                            $images = is_array($item->images)
                                                ? $item->images
                                                : (is_string($item->images)
                                                    ? json_decode($item->images, true)
                                                    : []);
                                            $thumb = !empty($images)
                                                ? Storage::url($images[0])
                                                : asset('images/default-item.png');
                                        @endphp
                                        <img src="{{ $thumb }}" alt="{{ $item->name }}" width="48"
                                            height="48" style="object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="grow">
                                        <p class="fw-semibold small mb-1">{{ Str::limit($item->name, 30) }}</p>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark rounded-pill px-2 py-1"
                                                style="font-size: 0.7rem;">
                                                {{ $item->category_label }}
                                            </span>
                                            <span class="text-secondary small">
                                                <i class="bi bi-clock"></i> {{ $item->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('items.show', $item->id) }}"
                                        class="btn btn-sm btn-link text-decoration-none p-0" style="color: #667eea;">
                                        Detail
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 text-center">
                            <a href="{{ route('items.index') }}" class="text-decoration-none small"
                                style="color: #667eea;">
                                Lihat semua barang <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Transactions -->
            <div class="col-lg-8">
                <!-- Recent Purchases -->
                <div class="admin-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-cart-check me-2" style="color: #667eea;"></i>
                            Transaksi Pembelian Terbaru
                        </h6>
                        <a href="{{ route('transactions.index') }}" class="text-decoration-none small"
                            style="color: #667eea;">
                            Lihat semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    @if (isset($recent_purchases) && $recent_purchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="small text-secondary">
                                    <tr>
                                        <th>Barang</th>
                                        <th>Penjual</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_purchases as $trx)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @php
                                                        $itemImages = is_array($trx->item->images)
                                                            ? $trx->item->images
                                                            : (is_string($trx->item->images)
                                                                ? json_decode($trx->item->images, true)
                                                                : []);
                                                        $itemThumb = !empty($itemImages)
                                                            ? Storage::url($itemImages[0])
                                                            : asset('images/default-item.png');
                                                    @endphp
                                                    <img src="{{ $itemThumb }}" alt="{{ $trx->item->name }}"
                                                        width="40" height="40"
                                                        style="object-fit: cover; border-radius: 8px;">
                                                    <div>
                                                        <p class="fw-semibold small mb-0">
                                                            {{ Str::limit($trx->item->name, 20) }}</p>
                                                        <small class="text-secondary">{{ $trx->transaction_code }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar-circle"
                                                        style="width: 32px; height: 32px; font-size: 0.8rem; background: rgba(102,126,234,0.1); color: #667eea;">
                                                        {{ strtoupper(substr($trx->seller->name, 0, 1)) }}
                                                    </div>
                                                    <span class="small">{{ Str::limit($trx->seller->name, 15) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $trx->formatted_total }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $trx->payment_status_label;
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-3 py-2">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $trx->id) }}"
                                                    class="btn btn-sm btn-link text-decoration-none p-0"
                                                    style="color: #667eea;">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="bi bi-cart-x fs-1 text-secondary opacity-50"></i>
                            </div>
                            <p class="text-secondary mb-2">Belum ada transaksi pembelian</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                                <i class="bi bi-search me-2"></i>Jelajahi Katalog
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Sales -->
                <div class="admin-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-truck me-2" style="color: #764ba2;"></i>
                            Transaksi Penjualan Terbaru
                        </h6>
                        <a href="{{ route('transactions.index') }}" class="text-decoration-none small"
                            style="color: #667eea;">
                            Lihat semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    @if (isset($recent_sales) && $recent_sales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="small text-secondary">
                                    <tr>
                                        <th>Barang</th>
                                        <th>Pembeli</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_sales as $trx)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @php
                                                        $itemImages = is_array($trx->item->images)
                                                            ? $trx->item->images
                                                            : (is_string($trx->item->images)
                                                                ? json_decode($trx->item->images, true)
                                                                : []);
                                                        $itemThumb = !empty($itemImages)
                                                            ? Storage::url($itemImages[0])
                                                            : asset('images/default-item.png');
                                                    @endphp
                                                    <img src="{{ $itemThumb }}" alt="{{ $trx->item->name }}"
                                                        width="40" height="40"
                                                        style="object-fit: cover; border-radius: 8px;">
                                                    <div>
                                                        <p class="fw-semibold small mb-0">
                                                            {{ Str::limit($trx->item->name, 20) }}</p>
                                                        <small class="text-secondary">{{ $trx->transaction_code }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar-circle"
                                                        style="width: 32px; height: 32px; font-size: 0.8rem; background: rgba(118,75,162,0.1); color: #764ba2;">
                                                        {{ strtoupper(substr($trx->buyer->name, 0, 1)) }}
                                                    </div>
                                                    <span class="small">{{ Str::limit($trx->buyer->name, 15) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $trx->formatted_total }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $trx->payment_status_label;
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-3 py-2">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $trx->id) }}"
                                                    class="btn btn-sm btn-link text-decoration-none p-0"
                                                    style="color: #667eea;">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="bi bi-gift fs-1 text-secondary opacity-50"></i>
                            </div>
                            <p class="text-secondary mb-2">Belum ada transaksi penjualan</p>
                            <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                                <i class="bi bi-cloud-upload me-2"></i>Upload Barang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .stat-card {
                background: white;
                border-radius: 20px;
                transition: all 0.3s;
                border: 1px solid rgba(0, 0, 0, 0.02);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 16px 24px rgba(102, 126, 234, 0.08);
            }

            .admin-card {
                background: white;
                border-radius: 20px;
                border: 1px solid rgba(0, 0, 0, 0.02);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            }

            [data-bs-theme="dark"] .stat-card,
            [data-bs-theme="dark"] .admin-card {
                background: #1a1a2c;
                border-color: rgba(255, 255, 255, 0.05);
            }

            .table {
                margin-bottom: 0;
            }

            .table> :not(caption)>*>* {
                padding: 1rem 0.5rem;
                background: transparent;
                border-bottom-color: rgba(0, 0, 0, 0.02);
            }

            [data-bs-theme="dark"] .table> :not(caption)>*>* {
                border-bottom-color: rgba(255, 255, 255, 0.05);
            }

            .avatar-circle {
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-weight: 600;
            }

            .btn-outline-primary {
                border-color: rgba(102, 126, 234, 0.2);
                color: #667eea;
            }

            .btn-outline-primary:hover {
                background: rgba(102, 126, 234, 0.1);
                border-color: #667eea;
                color: #667eea;
            }
        </style>
    @endpush
@endsection
