@extends('layouts.app')

@section('title', 'Transaksi Saya - WAKANDE')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Transaksi Saya</h1>
            <p class="text-secondary mb-0">Kelola semua transaksi pembelian dan penjualanmu</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-rounded px-4 py-2">
            <i class="bi bi-plus-circle me-2"></i>Beli Barang
        </a>
    </div>

    <!-- Tabs -->
    <div class="transaction-tabs mb-4">
        <div class="d-flex border-bottom">
            <button class="transaction-tab px-4 py-3 {{ !request('type') ? 'active' : '' }}" onclick="window.location='{{ route('transactions.index') }}'">
                <i class="bi bi-arrow-left-right me-2"></i>Semua
            </button>
            <button class="transaction-tab px-4 py-3 {{ request('type') == 'bought' ? 'active' : '' }}" onclick="window.location='{{ route('transactions.index', ['type' => 'bought']) }}'">
                <i class="bi bi-cart-check me-2"></i>Pembelian
            </button>
            <button class="transaction-tab px-4 py-3 {{ request('type') == 'sold' ? 'active' : '' }}" onclick="window.location='{{ route('transactions.index', ['type' => 'sold']) }}'">
                <i class="bi bi-truck me-2"></i>Penjualan
            </button>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <span class="text-secondary small me-2">Filter status:</span>
        <button class="status-filter px-4 py-2 rounded-pill {{ !request('status') ? 'active' : '' }}" onclick="filterStatus('')">
            Semua
        </button>
        <button class="status-filter px-4 py-2 rounded-pill {{ request('status') == 'pending' ? 'active' : '' }}" onclick="filterStatus('pending')">
            <i class="bi bi-clock me-1"></i>Pending
        </button>
        <button class="status-filter px-4 py-2 rounded-pill {{ request('status') == 'paid' ? 'active' : '' }}" onclick="filterStatus('paid')">
            <i class="bi bi-check-circle me-1"></i>Dibayar
        </button>
        <button class="status-filter px-4 py-2 rounded-pill {{ request('status') == 'completed' ? 'active' : '' }}" onclick="filterStatus('completed')">
            <i class="bi bi-check2-all me-1"></i>Selesai
        </button>
        <button class="status-filter px-4 py-2 rounded-pill {{ request('status') == 'cancelled' ? 'active' : '' }}" onclick="filterStatus('cancelled')">
            <i class="bi bi-x-circle me-1"></i>Dibatalkan
        </button>
    </div>

    @if($transactions->count() > 0)
        <!-- Transactions List -->
        <div class="vstack gap-3">
            @foreach($transactions as $trx)
                <div class="transaction-card p-4 rounded-4" onclick="window.location='{{ route('transactions.show', $trx->id) }}'" style="cursor: pointer;">
                    <div class="row align-items-center g-3">
                        <!-- Item Info -->
                        <div class="col-lg-5">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Item Image -->
                                <div class="shrink-0">
                                    @php
                                        $itemImages = json_decode($trx->item->images, true) ?? [];
                                        $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                    @endphp
                                    <img src="{{ $itemThumb }}" alt="{{ $trx->item->name }}"
                                         width="80" height="80" style="object-fit: cover; border-radius: 16px;">
                                </div>
                                <div>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-2">
                                        {{ $trx->item->category_label }}
                                    </span>
                                    <h6 class="fw-bold mb-1">{{ Str::limit($trx->item->name, 40) }}</h6>
                                    <small class="text-secondary">
                                        <i class="bi bi-tag me-1"></i>Kode: {{ $trx->transaction_code }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Type -->
                        <div class="col-lg-2">
                            <div class="d-flex align-items-center gap-2">
                                @if($trx->buyer_id == Auth::id())
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                                        <i class="bi bi-cart-check me-1"></i>Pembelian
                                    </span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                        <i class="bi bi-truck me-1"></i>Penjualan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-lg-2">
                            <div class="text-lg-center">
                                <span class="fw-bold d-block" style="color: #667eea;">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </span>
                                <small class="text-secondary">
                                    @if($trx->admin_fee > 0)
                                        + admin {{ number_format($trx->admin_fee, 0, ',', '.') }}
                                    @endif
                                </small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-2">
                            @php
                                $status = $trx->payment_status_label;
                            @endphp
                            <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-4 py-2 w-100">
                                <i class="bi bi-{{ $trx->payment_status == 'completed' ? 'check-circle' : ($trx->payment_status == 'pending' ? 'clock' : ($trx->payment_status == 'paid' ? 'check' : 'x-circle')) }} me-1"></i>
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <!-- Date & Action -->
                        <div class="col-lg-1">
                            <div class="text-end">
                                <small class="text-secondary d-block mb-1">
                                    {{ $trx->created_at->diffForHumans() }}
                                </small>
                                <i class="bi bi-chevron-right" style="color: #667eea;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $transactions->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="mb-4">
                <i class="bi bi-arrow-left-right fs-1 text-secondary opacity-25"></i>
            </div>
            <h5 class="fw-bold mb-3">Belum Ada Transaksi</h5>
            <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">
                @if(request('type') == 'bought')
                    Kamu belum melakukan pembelian apapun. Yuk jelajahi katalog!
                @elseif(request('type') == 'sold')
                    Kamu belum menjual barang apapun. Upload barang sekarang!
                @else
                    Belum ada transaksi. Mulai beli atau jual barang sekarang!
                @endif
            </p>
            <div class="d-flex gap-3 justify-content-center">
                @if(request('type') != 'sold')
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-rounded px-5 py-3">
                        <i class="bi bi-search me-2"></i>Jelajahi Katalog
                    </a>
                @endif
                @if(request('type') != 'bought')
                    <a href="{{ route('items.create') }}" class="btn btn-outline-primary btn-rounded px-5 py-3">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Barang
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function filterStatus(status) {
        const url = new URL(window.location.href);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }
</script>
@endpush

@push('styles')
<style>
    .transaction-tab {
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--bs-secondary);
        font-weight: 500;
        transition: all 0.3s;
    }

    .transaction-tab:hover {
        color: #667eea;
    }

    .transaction-tab.active {
        border-bottom-color: #667eea;
        color: #667eea;
    }

    .status-filter {
        background: transparent;
        border: 1px solid rgba(0,0,0,0.05);
        color: var(--bs-secondary);
        transition: all 0.3s;
    }

    .status-filter:hover {
        background: rgba(102,126,234,0.05);
        border-color: rgba(102,126,234,0.3);
        color: #667eea;
    }

    .status-filter.active {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }

    .transaction-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        transition: all 0.3s;
    }

    .transaction-card:hover {
        transform: translateX(4px);
        box-shadow: 0 8px 16px rgba(102,126,234,0.08);
    }

    [data-bs-theme="dark"] .transaction-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .status-filter {
        border-color: rgba(255,255,255,0.1);
    }
</style>
@endpush
@endsection
