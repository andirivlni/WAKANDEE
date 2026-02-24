@extends('layouts.app')

@section('title', 'Riwayat Transaksi - WAKANDE')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Riwayat Transaksi</h2>
            <p class="text-secondary">Pantau semua aktivitas jual-beli dan donasimu di sini.</p>
        </div>
        <a href="/" class="btn btn-outline-success btn-rounded px-4">
            <i class="bi bi-plus-lg me-2"></i>Cari Barang
        </a>
    </div>

    @if($transactions->isEmpty())
        <div class="text-center py-5 detail-card rounded-4">
            <img src="{{ asset('images/empty-box.png') }}" alt="Empty" width="150" class="mb-3 opacity-50">
            <h5 class="text-secondary">Belum ada transaksi dilakukan</h5>
            <a href="/" class="btn btn-success mt-3 px-4">Mulai Berbagi</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($transactions as $transaction)
            <div class="col-12">
                <div class="detail-card p-3 rounded-4 transition-hover">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $itemImages = is_array($transaction->item->images) ? $transaction->item->images : json_decode($transaction->item->images, true);
                                    $thumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default.png');
                                @endphp
                                <img src="{{ $thumb }}" class="rounded-3" width="80" height="80" style="object-fit: cover;">
                                <div>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill mb-1" style="font-size: 0.7rem;">
                                        {{ strtoupper($transaction->transaction_code) }}
                                    </span>
                                    <h6 class="fw-bold mb-1">{{ $transaction->item->name }}</h6>
                                    <small class="text-secondary">{{ $transaction->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-md-center">
                            <div class="small text-secondary mb-1">Total Transaksi</div>
                            <div class="fw-bold text-success">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </div>
                            <span class="badge {{ $transaction->buyer_id == Auth::id() ? 'bg-info' : 'bg-warning' }} bg-opacity-10 {{ $transaction->buyer_id == Auth::id() ? 'text-info' : 'text-warning' }} rounded-pill" style="font-size: 0.7rem;">
                                {{ $transaction->buyer_id == Auth::id() ? 'Sebagai Pembeli' : 'Sebagai Penjual' }}
                            </span>
                        </div>

                        <div class="col-md-2 text-md-center">
                            @php $status = $transaction->payment_status_label; @endphp
                            <div class="small text-secondary mb-1">Status</div>
                            <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-3">
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <div class="col-md-2 text-md-end">
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-light btn-rounded w-100">
                                Detail <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

<style>
    .detail-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: transform 0.2s;
    }
    .transition-hover:hover {
        transform: translateY(-3px);
        border-color: #22c55e;
    }
    .btn-rounded { border-radius: 50px; }
</style>
@endsection
