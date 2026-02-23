@extends('layouts.admin')

@section('title', 'Monitoring Transaksi - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Monitoring Transaksi</h1>
            <p class="text-secondary mb-0">Pantau dan kelola semua transaksi di WAKANDE</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success rounded-pill px-4 py-3" onclick="exportTransactions()">
                <i class="bi bi-download me-2"></i>Export CSV
            </button>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-4 py-3">
                <i class="bi bi-cash-stack me-2"></i>Total Pendapatan: Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-4 rounded-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #dcfce7;">
                        <i class="bi bi-arrow-left-right fs-3" style="color: #22c55e;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ number_format($stats['total_transactions'] ?? 0) }}</h3>
                        <p class="text-secondary mb-0">Total Transaksi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-4 rounded-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #dcfce7;">
                        <i class="bi bi-check-circle fs-3" style="color: #198754;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ number_format($stats['completed_transactions'] ?? 0) }}</h3>
                        <p class="text-secondary mb-0">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-4 rounded-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #fef3c7;">
                        <i class="bi bi-clock fs-3" style="color: #ffc107;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ number_format($stats['pending_transactions'] ?? 0) }}</h3>
                        <p class="text-secondary mb-0">Pending</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-4 rounded-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(13,202,240,0.1);">
                        <i class="bi bi-cash fs-3" style="color: #0dcaf0;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-secondary mb-0">Volume Transaksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Transactions Chart -->
    <div class="admin-card p-4 rounded-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-graph-up me-2" style="color: #22c55e;"></i>
                    Grafik Transaksi 30 Hari Terakhir
                </h5>
                <p class="text-secondary small mb-0">Jumlah transaksi dan pendapatan per hari</p>
            </div>
            <div class="d-flex gap-3">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i> Transaksi
                </span>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                    <i class="bi bi-cash-stack me-1"></i> Pendapatan
                </span>
            </div>
        </div>
        <div style="position: relative; height: 350px; width: 100%;">
            <canvas id="transactionChart"></canvas>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="admin-card p-4 rounded-3 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-4">
                <form action="{{ route('admin.transactions.index') }}" method="GET" id="searchForm">
                    <div class="search-box position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text"
                               name="search"
                               class="form-control form-control-lg rounded-pill border-0 shadow-none"
                               style="padding-left: 45px; background: #f8fafc;"
                               placeholder="Cari kode transaksi, pembeli, atau penjual..."
                               value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <!-- Status Filter -->
                    <select name="payment_status" form="filterForm" class="form-select rounded-pill px-4 py-2" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                        <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>🎉 Completed</option>
                        <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>

                    <!-- Payment Method Filter -->
                    <select name="payment_method" form="filterForm" class="form-select rounded-pill px-4 py-2" style="width: auto;">
                        <option value="">Semua Metode</option>
                        <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>💵 COD</option>
                    </select>

                    <!-- Date Range -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-pill px-4 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calendar me-2"></i>
                            {{ request('date_from') && request('date_to') ? request('date_from') . ' - ' . request('date_to') : 'Filter Tanggal' }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-end rounded-3 p-3" style="min-width: 300px;">
                            <form id="dateFilterForm" onsubmit="applyDateFilter(event)">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Dari Tanggal</label>
                                    <input type="date" name="date_from" class="form-control rounded-3" value="{{ request('date_from') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Sampai Tanggal</label>
                                    <input type="date" name="date_to" class="form-control rounded-3" value="{{ request('date_to') }}">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success rounded-pill px-4 grow">Terapkan</button>
                                    <a href="{{ route('admin.transactions.index', array_merge(request()->except(['date_from', 'date_to']))) }}" class="btn btn-outline-secondary rounded-pill px-4">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    @if($transactions->count() > 0)
        <div class="admin-card p-4 rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="small text-secondary">
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Pembeli</th>
                            <th>Penjual</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                            <tr>
                                <td>
                                    <span class="fw-mono fw-semibold small">{{ $trx->transaction_code }}</span>
                                    @if($trx->payment_status == 'pending' && $trx->created_at->diffInHours(now()) > 24)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill ms-2 px-2 py-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-exclamation-triangle"></i> Expired
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small d-block fw-semibold">{{ $trx->created_at->format('d/m/Y') }}</span>
                                    <small class="text-secondary">{{ $trx->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $itemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []) ?? [];
                                            $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                        @endphp
                                        <img src="{{ $itemThumb }}" alt="{{ $trx->item->name }}" width="48" height="48" style="object-fit: cover; border-radius: 12px;">
                                        <div>
                                            <span class="fw-semibold d-block small">{{ Str::limit($trx->item->name, 30) }}</span>
                                            <small class="text-secondary">{{ $trx->item->category_label }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($trx->buyer->profile_photo)
                                            <img src="{{ Storage::url($trx->buyer->profile_photo) }}" alt="{{ $trx->buyer->name }}" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle" style="width: 32px; height: 32px; background: #dcfce7; color: #22c55e;">
                                                {{ strtoupper(substr($trx->buyer->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="small">{{ Str::limit($trx->buyer->name, 15) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($trx->seller->profile_photo)
                                            <img src="{{ Storage::url($trx->seller->profile_photo) }}" alt="{{ $trx->seller->name }}" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle" style="width: 32px; height: 32px; background: #dcfce7; color: #4ade80;">
                                                {{ strtoupper(substr($trx->seller->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="small">{{ Str::limit($trx->seller->name, 15) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: #22c55e;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                                    @if($trx->admin_fee > 0)
                                        <small class="text-secondary d-block">+ admin {{ number_format($trx->admin_fee, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        <i class="bi bi-{{ $trx->payment_method == 'qris' ? 'qr-code-scan' : 'cash' }} me-1"></i>
                                        {{ $trx->payment_method_label }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $status = $trx->payment_status_label;
                                    @endphp
                                    <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-3 py-2">
                                        <i class="bi bi-{{ $trx->payment_status == 'completed' ? 'check-circle' : ($trx->payment_status == 'pending' ? 'clock' : ($trx->payment_status == 'paid' ? 'check' : 'x-circle')) }} me-1"></i>
                                        {{ $status['label'] }}
                                    </span>
                                    @if($trx->payment_status == 'paid')
                                        <small class="text-info d-block mt-1">Menunggu konfirmasi</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success rounded-circle p-2"
                                                style="width: 36px; height: 36px;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $trx->id }}"
                                                title="Detail Transaksi">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        @if($trx->payment_status == 'paid')
                                            <button type="button"
                                                    class="btn btn-sm btn-success rounded-circle p-2"
                                                    style="width: 36px; height: 36px;"
                                                    onclick="completeTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')"
                                                    title="Selesaikan Transaksi">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @endif

                                        @if($trx->payment_status == 'pending' && $trx->created_at->diffInHours(now()) > 24)
                                            <button type="button"
                                                    class="btn btn-sm btn-danger rounded-circle p-2"
                                                    style="width: 36px; height: 36px;"
                                                    onclick="cancelTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')"
                                                    title="Batalkan Transaksi">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="small text-secondary">
                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </div>
                <div>
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="admin-card p-5 rounded-3 text-center">
            <div class="empty-state">
                <i class="bi bi-arrow-left-right fs-1 text-secondary opacity-25 mb-3"></i>
                <h5 class="fw-bold mb-3">Belum Ada Transaksi</h5>
                <p class="text-secondary mb-4">Belum ada transaksi yang terjadi di WAKANDE.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-success rounded-pill px-5 py-3">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Transaction Detail Modals -->
@foreach($transactions as $trx)
    <div class="modal fade" id="detailModal{{ $trx->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 border-0">
                <div class="modal-header border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7;">
                            <i class="bi bi-receipt fs-4" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold">Detail Transaksi</h5>
                            <p class="small text-secondary mb-0">{{ $trx->transaction_code }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="row g-4">
                        <!-- Item Info -->
                        <div class="col-md-6">
                            <div class="info-section p-3 rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-box me-2" style="color: #22c55e;"></i>
                                    Informasi Barang
                                </h6>
                                <div class="d-flex gap-3">
                                    @php
                                        $itemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []) ?? [];
                                        $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                    @endphp
                                    <img src="{{ $itemThumb }}" alt="{{ $trx->item->name }}" width="80" height="80" style="object-fit: cover; border-radius: 12px;">
                                    <div>
                                        <p class="fw-semibold mb-1">{{ $trx->item->name }}</p>
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-2">
                                            {{ $trx->item->category_label }}
                                        </span>
                                        <p class="small text-secondary mb-0">
                                            <i class="bi bi-tag me-1"></i>{{ $trx->item->condition_label }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="col-md-6">
                            <div class="info-section p-3 rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-credit-card me-2" style="color: #22c55e;"></i>
                                    Ringkasan Pembayaran
                                </h6>
                                <div class="vstack gap-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Harga Barang</span>
                                        <span class="fw-semibold">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                                    </div>
                                    @if($trx->admin_fee > 0)
                                        <div class="d-flex justify-content-between">
                                            <span class="text-secondary small">Biaya Admin</span>
                                            <span class="fw-semibold">Rp {{ number_format($trx->admin_fee, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total</span>
                                        <span class="fw-bold h5 mb-0" style="color: #22c55e;">
                                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                            <i class="bi bi-{{ $trx->payment_method == 'qris' ? 'qr-code-scan' : 'cash' }} me-1"></i>
                                            {{ $trx->payment_method_label }}
                                        </span>
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 ms-2">
                                            <i class="bi bi-{{ $trx->delivery_method == 'dropoff' ? 'building' : 'truck' }} me-1"></i>
                                            {{ $trx->delivery_method_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buyer Info -->
                        <div class="col-md-6">
                            <div class="info-section p-3 rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-person me-2" style="color: #22c55e;"></i>
                                    Informasi Pembeli
                                </h6>
                                <div class="d-flex align-items-center gap-3">
                                    @if($trx->buyer->profile_photo)
                                        <img src="{{ Storage::url($trx->buyer->profile_photo) }}" alt="{{ $trx->buyer->name }}" class="rounded-circle" width="56" height="56" style="object-fit: cover;">
                                    @else
                                        <div class="avatar-circle" style="width: 56px; height: 56px; background: #22c55e;">
                                            {{ strtoupper(substr($trx->buyer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="fw-semibold mb-1">{{ $trx->buyer->name }}</p>
                                        <p class="small text-secondary mb-1">{{ $trx->buyer->email }}</p>
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                            <i class="bi bi-building me-1"></i>{{ $trx->buyer->school ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Info -->
                        <div class="col-md-6">
                            <div class="info-section p-3 rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-person me-2" style="color: #22c55e;"></i>
                                    Informasi Penjual
                                </h6>
                                <div class="d-flex align-items-center gap-3">
                                    @if($trx->seller->profile_photo)
                                        <img src="{{ Storage::url($trx->seller->profile_photo) }}" alt="{{ $trx->seller->name }}" class="rounded-circle" width="56" height="56" style="object-fit: cover;">
                                    @else
                                        <div class="avatar-circle" style="width: 56px; height: 56px; background: #22c55e;">
                                            {{ strtoupper(substr($trx->seller->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="fw-semibold mb-1">{{ $trx->seller->name }}</p>
                                        <p class="small text-secondary mb-1">{{ $trx->seller->email }}</p>
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                            <i class="bi bi-building me-1"></i>{{ $trx->seller->school ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div class="col-12">
                            <div class="info-section p-3 rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-truck me-2" style="color: #22c55e;"></i>
                                    Informasi Pengiriman
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="small text-secondary mb-1">Metode Pengiriman</p>
                                        <p class="fw-semibold">{{ $trx->delivery_method_label }}</p>
                                    </div>
                                    @if($trx->delivery_method == 'dropoff')
                                        <div class="col-md-6">
                                            <p class="small text-secondary mb-1">Titik Drop-off</p>
                                            <p class="fw-semibold">{{ $trx->dropoff_point ?? '-' }}</p>
                                        </div>
                                    @endif
                                    @if($trx->notes)
                                        <div class="col-12 mt-2">
                                            <p class="small text-secondary mb-1">Catatan</p>
                                            <p class="small bg-light p-2 rounded-3">{{ $trx->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="col-12">
                            <div class="info-section p-3 rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-clock-history me-2" style="color: #22c55e;"></i>
                                    Timeline Transaksi
                                </h6>
                                <div class="timeline">
                                    <div class="timeline-item d-flex gap-3 mb-3">
                                        <div class="timeline-icon">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7;">
                                                <i class="bi bi-cart text-success"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="fw-semibold small mb-1">Transaksi Dibuat</p>
                                            <small class="text-secondary">{{ $trx->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>

                                    @if($trx->paid_at)
                                        <div class="timeline-item d-flex gap-3 mb-3">
                                            <div class="timeline-icon">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7;">
                                                    <i class="bi bi-credit-card text-success"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="fw-semibold small mb-1">Pembayaran Dikonfirmasi</p>
                                                <small class="text-secondary">{{ $trx->paid_at->format('d F Y H:i') }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    @if($trx->completed_at)
                                        <div class="timeline-item d-flex gap-3">
                                            <div class="timeline-icon">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7;">
                                                    <i class="bi bi-check2-all text-success"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="fw-semibold small mb-1">Transaksi Selesai</p>
                                                <small class="text-secondary">{{ $trx->completed_at->format('d F Y H:i') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                                    <div class="d-flex justify-content-end gap-2 w-100">
                        @if($trx->payment_status == 'pending' && $trx->created_at->diffInHours(now()) > 24)
                            <button type="button" class="btn btn-danger rounded-pill px-4" onclick="cancelTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')">
                                <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                            </button>
                        @endif
                        @if($trx->payment_status == 'paid')
                            <button type="button" class="btn btn-success rounded-pill px-4" onclick="completeTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')">
                                <i class="bi bi-check-circle me-2"></i>Selesaikan Transaksi
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Hidden Form for Filters -->
<form id="filterForm" action="{{ route('admin.transactions.index') }}" method="GET">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Transaction Chart
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const transactionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($daily_transactions->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d M');
            })) !!},
            datasets: [
                {
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($daily_transactions->pluck('total')) !!},
                    backgroundColor: 'rgba(34, 197, 94,0.2)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($daily_transactions->pluck('revenue')) !!},
                    backgroundColor: 'rgba(25,135,84,0.2)',
                    borderColor: '#198754',
                    borderWidth: 1,
                    borderRadius: 6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'transparent'
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Auto submit search with debounce
    let searchTimeout;
    document.querySelector('input[name="search"]')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });

    // Auto submit filters
    document.querySelector('select[name="payment_status"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.querySelector('select[name="payment_method"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Apply date filter
    function applyDateFilter(event) {
        event.preventDefault();
        const form = event.target;
        const dateFrom = form.querySelector('[name="date_from"]').value;
        const dateTo = form.querySelector('[name="date_to"]').value;

        const url = new URL(window.location.href);
        if (dateFrom) url.searchParams.set('date_from', dateFrom);
        if (dateTo) url.searchParams.set('date_to', dateTo);

        window.location.href = url.toString();
    }

    // Complete transaction
    function completeTransaction(id, code) {
        Swal.fire({
            title: 'Selesaikan Transaksi?',
            html: `<p>Kamu yakin ingin menyelesaikan transaksi <strong>${code}</strong>?</p>
                   <p class="small text-success">Status akan berubah menjadi Completed dan admin fee akan masuk ke pendapatan.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/transactions/${id}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Selesai!',
                            'Transaksi berhasil diselesaikan.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    }

    // Cancel transaction
    function cancelTransaction(id, code) {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            html: `<p>Kamu yakin ingin membatalkan transaksi <strong>${code}</strong>?</p>
                   <p class="small text-danger">Transaksi yang dibatalkan tidak bisa dikembalikan.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/transactions/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Dibatalkan!',
                            'Transaksi berhasil dibatalkan.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    }

    // Export transactions
    function exportTransactions() {
        const url = new URL('{{ route("admin.transactions.export") }}', window.location.origin);

        // Add current filters to export
        const params = new URLSearchParams(window.location.search);
        params.forEach((value, key) => {
            url.searchParams.append(key, value);
        });

        window.location.href = url.toString();

        Swal.fire({
            icon: 'success',
            title: 'Export Dimulai',
            text: 'File CSV akan segera didownload.',
            showConfirmButton: false,
            timer: 1500
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@push('styles')
<style>
    .transaction-stat-card {
        background: white;
        border: 1px solid transparent;
        box-shadow: 0 4px 12px transparent;
        transition: all 0.3s;
    }

    .transaction-stat-card:hover {
        transform: translateY(-4px);
        
    }

    .info-section {
        background: #f8fafc;
        border: 1px solid #f0fdf4;
        height: 100%;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 32px;
        bottom: -16px;
        width: 2px;
        background: transparent;
    }

    [data-bs-theme="dark"] .transaction-stat-card,
    [data-bs-theme="dark"] .admin-card {
        background: #1a1a2c;
        border-color: transparent;
    }

    [data-bs-theme="dark"] .info-section {
        background: transparent;
        border-color: transparent;
    }

    [data-bs-theme="dark"] .timeline-item:not(:last-child)::before {
        background: #dcfce7;
    }

    .modal-content {
        background: white;
    }

    [data-bs-theme="dark"] .modal-content {
        background: #1a1a2c;
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

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
        background: #dcfce7;
        color: #22c55e;
    }

    .page-item.active .page-link {
        background: #22c55e;
        color: white;
    }

    [data-bs-theme="dark"] .page-link {
        background: transparent;
        color: white;
    }
</style>
@endpush
@endsection
