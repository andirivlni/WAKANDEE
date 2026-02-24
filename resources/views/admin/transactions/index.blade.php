@extends('layouts.admin')

@section('title', 'Monitoring Transaksi - WAKANDE')

@section('content')
<div class="container-fluid px-2 px-lg-3">
    <!-- Header -->
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 fw-bold mb-0">Monitoring Transaksi</h1>
            <small class="text-secondary">Pantau dan kelola semua transaksi di WAKANDE</small>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-outline-success btn-sm rounded-pill px-2 py-1" onclick="exportTransactions()">
                <i class="bi bi-download me-1"></i>Export CSV
            </button>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                <i class="bi bi-cash-stack me-1"></i>Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #dcfce7;">
                        <i class="bi bi-arrow-left-right fs-6" style="color: #22c55e;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1rem;">{{ number_format($stats['total_transactions'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.7rem;">Total Transaksi</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #dcfce7;">
                        <i class="bi bi-check-circle fs-6" style="color: #198754;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1rem;">{{ number_format($stats['completed_transactions'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.7rem;">Selesai</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #fef3c7;">
                        <i class="bi bi-clock fs-6" style="color: #ffc107;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1rem;">{{ number_format($stats['pending_transactions'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.7rem;">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="transaction-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(13,202,240,0.1);">
                        <i class="bi bi-cash fs-6" style="color: #0dcaf0;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1rem;">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</h5>
                        <small class="text-secondary" style="font-size: 0.7rem;">Volume</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Transactions Chart -->
    <div class="admin-card p-2 rounded-3 mb-3">
        <div class="d-flex flex-wrap gap-1 justify-content-between align-items-center mb-2">
            <div>
                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">
                    <i class="bi bi-graph-up me-1" style="color: #22c55e;"></i>
                    Grafik 30 Hari
                </h6>
                <small class="text-secondary" style="font-size: 0.65rem;">Transaksi & pendapatan per hari</small>
            </div>
            <div class="d-flex gap-1">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0" style="font-size: 0.6rem;">
                    <i class="bi bi-check-circle me-1" style="font-size: 0.5rem;"></i> Transaksi
                </span>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0" style="font-size: 0.6rem;">
                    <i class="bi bi-cash-stack me-1" style="font-size: 0.5rem;"></i> Pendapatan
                </span>
            </div>
        </div>
        <div style="position: relative; height: 200px; width: 100%;">
            <canvas id="transactionChart"></canvas>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="admin-card p-2 rounded-3 mb-3">
        <div class="row g-1 align-items-center">
            <div class="col-lg-4">
                <form action="{{ route('admin.transactions.index') }}" method="GET" id="searchForm">
                    <div class="search-box position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-secondary" style="font-size: 0.7rem;"></i>
                        <input type="text"
                               name="search"
                               class="form-control form-control-sm rounded-pill border-0 shadow-none"
                               style="padding-left: 25px; background: #f8fafc; font-size: 0.8rem; height: 30px;"
                               placeholder="Cari kode, pembeli, penjual..."
                               value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-1 justify-content-lg-end">
                    <!-- Status Filter -->
                    <select name="payment_status" form="filterForm" class="form-select form-select-sm rounded-pill px-2 py-1" style="width: auto; font-size: 0.8rem; height: 30px;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                        <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>🎉 Completed</option>
                        <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>

                    <!-- Payment Method Filter -->
                    <select name="payment_method" form="filterForm" class="form-select form-select-sm rounded-pill px-2 py-1" style="width: auto; font-size: 0.8rem; height: 30px;">
                        <option value="">Semua Metode</option>
                        <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>💵 COD</option>
                    </select>

                    <!-- Date Range -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-2 py-1 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size: 0.8rem; height: 30px;">
                            <i class="bi bi-calendar me-1"></i>
                            {{ request('date_from') && request('date_to') ? 'Filtered' : 'Tanggal' }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-end rounded-3 p-2" style="min-width: 250px;">
                            <form id="dateFilterForm" onsubmit="applyDateFilter(event)">
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold" style="font-size: 0.7rem;">Dari Tanggal</label>
                                    <input type="date" name="date_from" class="form-control form-control-sm rounded-3" style="font-size: 0.7rem; height: 28px;" value="{{ request('date_from') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold" style="font-size: 0.7rem;">Sampai Tanggal</label>
                                    <input type="date" name="date_to" class="form-control form-control-sm rounded-3" style="font-size: 0.7rem; height: 28px;" value="{{ request('date_to') }}">
                                </div>
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-2 py-0 w-100" style="font-size: 0.7rem;">Terapkan</button>
                                    <a href="{{ route('admin.transactions.index', array_merge(request()->except(['date_from', 'date_to']))) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-2 py-0 w-100" style="font-size: 0.7rem;">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-2 py-1" style="font-size: 0.8rem; height: 30px;">
                        <i class="bi bi-arrow-repeat me-1"></i>Reset
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    @if($transactions->count() > 0)
        <div class="admin-card p-2 rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.75rem;">
                    <thead class="text-secondary" style="font-size: 0.7rem;">
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
                                    <span class="fw-mono fw-semibold" style="font-size: 0.65rem;">{{ $trx->transaction_code }}</span>
                                    @if($trx->payment_status == 'pending' && $trx->created_at->diffInHours(now()) > 24)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill ms-1 px-1 py-0" style="font-size: 0.5rem;">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block" style="font-size: 0.65rem;">{{ $trx->created_at->format('d/m/Y') }}</span>
                                    <small style="font-size: 0.55rem;">{{ $trx->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @php
                                            $itemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []) ?? [];
                                            $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                        @endphp
                                        <img src="{{ $itemThumb }}" alt="{{ $trx->item->name }}" width="28" height="28" style="object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <span class="fw-semibold d-block" style="font-size: 0.65rem;">{{ Str::limit($trx->item->name, 15) }}</span>
                                            <small class="text-secondary" style="font-size: 0.55rem;">{{ $trx->item->category_label }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @if($trx->buyer->profile_photo)
                                            <img src="{{ Storage::url($trx->buyer->profile_photo) }}" alt="" class="rounded-circle" width="22" height="22" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle" style="width: 22px; height: 22px; background: #dcfce7; color: #22c55e; font-size: 0.6rem;">
                                                {{ strtoupper(substr($trx->buyer->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span style="font-size: 0.6rem;">{{ Str::limit($trx->buyer->name, 10) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @if($trx->seller->profile_photo)
                                            <img src="{{ Storage::url($trx->seller->profile_photo) }}" alt="" class="rounded-circle" width="22" height="22" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle" style="width: 22px; height: 22px; background: #dcfce7; color: #4ade80; font-size: 0.6rem;">
                                                {{ strtoupper(substr($trx->seller->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span style="font-size: 0.6rem;">{{ Str::limit($trx->seller->name, 10) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: #22c55e; font-size: 0.7rem;">Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                                    @if($trx->admin_fee > 0)
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">+{{ number_format($trx->admin_fee, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-1 py-0" style="font-size: 0.55rem;">
                                        <i class="bi bi-{{ $trx->payment_method == 'qris' ? 'qr-code-scan' : 'cash' }} me-1" style="font-size: 0.5rem;"></i>
                                        {{ $trx->payment_method == 'qris' ? 'QRIS' : 'COD' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $status = $trx->payment_status_label;
                                    @endphp
                                    <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-1 py-0" style="font-size: 0.55rem;">
                                        <i class="bi bi-{{ $trx->payment_status == 'completed' ? 'check-circle' : ($trx->payment_status == 'pending' ? 'clock' : ($trx->payment_status == 'paid' ? 'check' : 'x-circle')) }} me-1" style="font-size: 0.5rem;"></i>
                                        {{ $status['label'] }}
                                    </span>
                                    @if($trx->payment_status == 'paid')
                                        <small class="text-info d-block mt-0" style="font-size: 0.5rem;">Menunggu</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success rounded-circle p-0"
                                                style="width: 24px; height: 24px;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $trx->id }}"
                                                title="Detail">
                                            <i class="bi bi-eye" style="font-size: 0.7rem;"></i>
                                        </button>

                                        @if($trx->payment_status == 'paid')
                                            <button type="button"
                                                    class="btn btn-sm btn-success rounded-circle p-0"
                                                    style="width: 24px; height: 24px;"
                                                    onclick="completeTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')"
                                                    title="Selesaikan">
                                                <i class="bi bi-check-lg" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @endif

                                        @if($trx->payment_status == 'pending' && $trx->created_at->diffInHours(now()) > 24)
                                            <button type="button"
                                                    class="btn btn-sm btn-danger rounded-circle p-0"
                                                    style="width: 24px; height: 24px;"
                                                    onclick="cancelTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')"
                                                    title="Batalkan">
                                                <i class="bi bi-x-lg" style="font-size: 0.7rem;"></i>
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
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mt-2">
                <div class="small text-secondary" style="font-size: 0.6rem;">
                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </div>
                <div style="font-size: 0.7rem;">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="admin-card p-3 rounded-3 text-center">
            <div class="empty-state">
                <i class="bi bi-arrow-left-right fs-4 text-secondary opacity-25 mb-2"></i>
                <h6 class="fw-bold mb-1">Belum Ada Transaksi</h6>
                <p class="text-secondary mb-2" style="font-size: 0.8rem;">Belum ada transaksi yang terjadi di WAKANDE.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-success btn-sm rounded-pill px-3 py-1">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
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
                <div class="modal-header border-0 pt-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="modal-icon rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7;">
                            <i class="bi bi-receipt" style="color: #22c55e; font-size: 1rem;"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold mb-0">Detail Transaksi</h6>
                            <small class="text-secondary">{{ $trx->transaction_code }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-3 py-2">
                    <div class="row g-2">
                        <!-- Item Info -->
                        <div class="col-md-6">
                            <div class="info-section p-2 rounded-3">
                                <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-box me-1" style="color: #22c55e;"></i> Barang
                                </h6>
                                <div class="d-flex gap-2">
                                    @php
                                        $itemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []) ?? [];
                                        $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                    @endphp
                                    <img src="{{ $itemThumb }}" alt="" width="48" height="48" style="object-fit: cover; border-radius: 6px;">
                                    <div>
                                        <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $trx->item->name }}</p>
                                        <small class="text-secondary" style="font-size: 0.65rem;">{{ $trx->item->category_label }} • {{ $trx->item->condition_label }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="col-md-6">
                            <div class="info-section p-2 rounded-3">
                                <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-credit-card me-1" style="color: #22c55e;"></i> Pembayaran
                                </h6>
                                <div class="vstack gap-0">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-secondary">Harga</small>
                                        <small class="fw-semibold">Rp{{ number_format($trx->amount, 0, ',', '.') }}</small>
                                    </div>
                                    @if($trx->admin_fee > 0)
                                    <div class="d-flex justify-content-between">
                                        <small class="text-secondary">Admin</small>
                                        <small class="fw-semibold">Rp{{ number_format($trx->admin_fee, 0, ',', '.') }}</small>
                                    </div>
                                    @endif
                                    <hr class="my-1">
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-bold">Total</small>
                                        <small class="fw-bold" style="color: #22c55e;">Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buyer & Seller Info -->
                        <div class="col-md-6">
                            <div class="info-section p-2 rounded-3">
                                <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-person me-1" style="color: #22c55e;"></i> Pembeli
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    @if($trx->buyer->profile_photo)
                                        <img src="{{ Storage::url($trx->buyer->profile_photo) }}" alt="" class="rounded-circle" width="36" height="36">
                                    @else
                                        <div class="avatar-circle" style="width: 36px; height: 36px; background: #22c55e; font-size: 0.9rem;">
                                            {{ strtoupper(substr($trx->buyer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $trx->buyer->name }}</p>
                                        <small class="text-secondary" style="font-size: 0.65rem;">{{ $trx->buyer->email }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-section p-2 rounded-3">
                                <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-person me-1" style="color: #22c55e;"></i> Penjual
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    @if($trx->seller->profile_photo)
                                        <img src="{{ Storage::url($trx->seller->profile_photo) }}" alt="" class="rounded-circle" width="36" height="36">
                                    @else
                                        <div class="avatar-circle" style="width: 36px; height: 36px; background: #22c55e; font-size: 0.9rem;">
                                            {{ strtoupper(substr($trx->seller->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $trx->seller->name }}</p>
                                        <small class="text-secondary" style="font-size: 0.65rem;">{{ $trx->seller->email }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="col-12">
                            <div class="info-section p-2 rounded-3">
                                <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-clock-history me-1" style="color: #22c55e;"></i> Timeline
                                </h6>
                                <div class="timeline">
                                    <div class="timeline-item d-flex gap-2 mb-1">
                                        <div class="timeline-icon">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; background: #dcfce7;">
                                                <i class="bi bi-cart text-success" style="font-size: 0.6rem;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Transaksi Dibuat</small>
                                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $trx->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @if($trx->paid_at)
                                    <div class="timeline-item d-flex gap-2 mb-1">
                                        <div class="timeline-icon">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; background: #dcfce7;">
                                                <i class="bi bi-credit-card text-success" style="font-size: 0.6rem;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Pembayaran</small>
                                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $trx->paid_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @endif
                                    @if($trx->completed_at)
                                    <div class="timeline-item d-flex gap-2">
                                        <div class="timeline-icon">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; background: #dcfce7;">
                                                <i class="bi bi-check2-all text-success" style="font-size: 0.6rem;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Selesai</small>
                                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $trx->completed_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-3 pb-2">
                    <div class="d-flex justify-content-end gap-1 w-100">
                        @if($trx->payment_status == 'pending' && $trx->created_at->diffInHours(now()) > 24)
                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-2 py-0" onclick="cancelTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')" style="font-size: 0.7rem;">Batalkan</button>
                        @endif
                        @if($trx->payment_status == 'paid')
                            <button type="button" class="btn btn-success btn-sm rounded-pill px-2 py-0" onclick="completeTransaction({{ $trx->id }}, '{{ $trx->transaction_code }}')" style="font-size: 0.7rem;">Selesaikan</button>
                        @endif
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2 py-0" data-bs-dismiss="modal" style="font-size: 0.7rem;">Tutup</button>
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
                    borderRadius: 4,
                    yAxisID: 'y',
                    barPercentage: 0.6
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($daily_transactions->pluck('revenue')) !!},
                    backgroundColor: 'rgba(25,135,84,0.2)',
                    borderColor: '#198754',
                    borderWidth: 1,
                    borderRadius: 4,
                    yAxisID: 'y1',
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    titleFont: { size: 9 },
                    bodyFont: { size: 8 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.02)' },
                    ticks: { font: { size: 7 } }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { display: false },
                    ticks: {
                        font: { size: 7 },
                        callback: value => 'Rp' + value.toLocaleString('id-ID')
                    }
                },
                x: { ticks: { font: { size: 7 }, maxRotation: 0 } }
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
    document.querySelector('select[name="payment_status"]')?.addEventListener('change', () => document.getElementById('filterForm').submit());
    document.querySelector('select[name="payment_method"]')?.addEventListener('change', () => document.getElementById('filterForm').submit());

    function applyDateFilter(e) {
        e.preventDefault();
        const f = e.target;
        const u = new URL(window.location.href);
        if (f.date_from.value) u.searchParams.set('date_from', f.date_from.value);
        if (f.date_to.value) u.searchParams.set('date_to', f.date_to.value);
        window.location.href = u.toString();
    }

// Complete transaction
function completeTransaction(id, code) {
    Swal.fire({
        title: 'Selesaikan Transaksi?',
        text: `Transaksi ${code} akan diselesaikan`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Selesaikan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Gunakan token CSRF dari meta tag
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/admin/transactions/${id}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selesai!',
                        text: 'Transaksi berhasil diselesaikan.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan koneksi'
                });
            });
        }
    });
}

// Cancel transaction
function cancelTransaction(id, code) {
    Swal.fire({
        title: 'Batalkan Transaksi?',
        text: `Transaksi ${code} akan dibatalkan`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/admin/transactions/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Dibatalkan!',
                        text: 'Transaksi berhasil dibatalkan.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan koneksi'
                });
            });
        }
    });
}

    function exportTransactions() {
        window.location.href = '{{ route("admin.transactions.export") }}' + window.location.search;
        Swal.fire({ icon: 'success', title: 'Export Dimulai', showConfirm: false, timer: 1000 });
    }
</script>
@endpush

@push('styles')
<style>
    .transaction-stat-card { background: white; border: 1px solid rgba(34,197,94,0.1); transition: 0.2s; }
    .transaction-stat-card:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(34,197,94,0.1); }
    .admin-card { background: white; border: 1px solid rgba(34,197,94,0.1); }
    .info-section { background: #f8fafc; border: 1px solid #f0fdf4; }
    .timeline-item:not(:last-child)::before {
        content: ''; position: absolute; left: 10px; top: 20px; bottom: -8px; width: 1px; background: #dcfce7;
    }
    .avatar-circle { display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; }
    .table th, .table td { padding: 0.5rem !important; }
    .pagination { gap: 0.15rem; }
    .page-link { border-radius: 4px !important; padding: 0.2rem 0.4rem !important; font-size: 0.7rem; }
    [data-bs-theme="dark"] .transaction-stat-card,
    [data-bs-theme="dark"] .admin-card { background: #1a1a2c; border-color: rgba(255,255,255,0.05); }
    [data-bs-theme="dark"] .info-section { background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05); }
</style>
@endpush
@endsection
