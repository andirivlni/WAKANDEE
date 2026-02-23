@extends('layouts.app')

@section('title', 'Instruksi Pembayaran - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="text-center mb-5">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-4 py-2 mb-3">
                    <i class="bi bi-bank me-2"></i>TRANSFER BANK
                </span>
                <h1 class="h2 fw-bold mb-3">Instruksi Pembayaran</h1>
                <p class="text-secondary">
                    Selesaikan pembayaran dengan transfer ke rekening WAKANDE
                </p>
            </div>

            <!-- Transaction Info -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3">Detail Transaksi</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Kode Transaksi</span>
                    <span class="fw-semibold">{{ $transaction->transaction_code }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Barang</span>
                    <span class="fw-semibold">{{ $transaction->item->name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Total Pembayaran</span>
                    <span class="fw-bold text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Bank Transfer Instructions -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3">Transfer ke Rekening:</h5>

                <div class="alert alert-info rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bank2 fs-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Bank BCA</h6>
                            <p class="mb-0">No. Rekening: <strong>1234567890</strong></p>
                            <p class="mb-0">a.n. <strong>WAKANDE</strong></p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bank2 fs-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Bank Mandiri</h6>
                            <p class="mb-0">No. Rekening: <strong>1234567890</strong></p>
                            <p class="mb-0">a.n. <strong>WAKANDE</strong></p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bank2 fs-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Bank BRI</h6>
                            <p class="mb-0">No. Rekening: <strong>1234567890</strong></p>
                            <p class="mb-0">a.n. <strong>WAKANDE</strong></p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning rounded-3 mt-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Penting:</strong> Pastikan jumlah yang ditransfer <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong> sesuai dengan total pembayaran.
                </div>
            </div>

            <!-- Upload Proof -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3">Upload Bukti Transfer</h5>

                <form action="{{ route('transactions.process-payment', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control @error('payment_proof') is-invalid @enderror"
                               id="payment_proof" name="payment_proof" accept="image/*" required>
                        <small class="text-secondary">Format: JPG, PNG. Maksimal 2MB</small>
                        @error('payment_proof')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirm" required>
                            <label class="form-check-label small text-secondary" for="confirm">
                                Saya telah melakukan transfer sesuai dengan jumlah yang tertera
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 rounded-pill">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran
                    </button>
                </form>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-link text-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Detail Transaksi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert-info {
        background: rgba(13, 202, 240, 0.05);
        border: 1px solid rgba(13, 202, 240, 0.2);
        color: var(--bs-body-color);
    }
    .alert-warning {
        background: rgba(255, 193, 7, 0.05);
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
</style>
@endpush
