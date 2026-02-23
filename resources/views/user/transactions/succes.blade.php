@extends('layouts.app')

@section('title', 'Transaksi Berhasil - WAKANDE')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Success Animation -->
            <div class="text-center mb-4">
                <div class="success-animation mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>

                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-4 py-2 mb-3">
                    <i class="bi bi-check-circle me-2"></i>TRANSAKSI BERHASIL
                </span>

                <h2 class="fw-bold mb-3">Yeay! Transaksi Berhasil 🎉</h2>
                <p class="text-secondary mb-4">
                    Terima kasih telah bertransaksi di WAKANDE.
                    Kamu sudah berkontribusi dalam ekonomi sirkular pendidikan!
                </p>
            </div>

            <!-- Transaction Card -->
            <div class="success-card p-4 p-md-5 rounded-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1);">
                        <i class="bi bi-receipt fs-4" style="color: #667eea;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Detail Transaksi</h6>
                        <p class="small text-secondary mb-0">{{ $transaction->transaction_code }}</p>
                    </div>
                </div>

                <div class="vstack gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Barang</span>
                        <span class="fw-semibold">{{ $transaction->item->name }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Penjual</span>
                        <span class="fw-semibold">{{ $transaction->seller->name }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Pembayaran</span>
                        <span class="fw-bold h5 mb-0" style="color: #667eea;">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <hr class="opacity-25">

                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Metode Pembayaran</span>
                        <span class="fw-semibold">{{ $transaction->payment_method_label }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Metode Pengiriman</span>
                        <span class="fw-semibold">{{ $transaction->delivery_method_label }}</span>
                    </div>

                    @if($transaction->dropoff_point)
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Titik Drop-off</span>
                            <span class="fw-semibold">{{ $transaction->dropoff_point }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps-card p-4 p-md-5 rounded-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-compass me-2" style="color: #667eea;"></i>
                    Langkah Selanjutnya
                </h6>

                <div class="vstack gap-3">
                    @if($transaction->payment_method == 'qris' && $transaction->payment_status == 'pending')
                        <div class="d-flex gap-3">
                            <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10" style="width: 32px; height: 32px;">
                                <span class="fw-bold text-warning">1</span>
                            </div>
                            <div class="grow">
                                <p class="fw-semibold mb-1">Selesaikan Pembayaran</p>
                                <p class="small text-secondary mb-0">Lakukan pembayaran melalui QRIS dan upload bukti pembayaran</p>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-3">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 32px; height: 32px;">
                            <span class="fw-bold text-primary">{{ $transaction->payment_method == 'qris' && $transaction->payment_status == 'pending' ? '2' : '1' }}</span>
                        </div>
                        <div class="grow">
                            <p class="fw-semibold mb-1">Koordinasi dengan Penjual</p>
                            <p class="small text-secondary mb-0">Hubungi penjual untuk atur jadwal serah terima barang</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 32px; height: 32px;">
                            <span class="fw-bold text-primary">{{ $transaction->payment_method == 'qris' && $transaction->payment_status == 'pending' ? '3' : '2' }}</span>
                        </div>
                        <div class="grow">
                            <p class="fw-semibold mb-1">Serah Terima Barang</p>
                            <p class="small text-secondary mb-0">Ambil barang di titik yang sudah disepakati</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 32px; height: 32px;">
                            <span class="fw-bold text-primary">{{ $transaction->payment_method == 'qris' && $transaction->payment_status == 'pending' ? '4' : '3' }}</span>
                        </div>
                        <div class="grow">
                            <p class="fw-semibold mb-1">Konfirmasi Penerimaan</p>
                            <p class="small text-secondary mb-0">Jangan lupa konfirmasi di aplikasi setelah barang diterima</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-primary btn-rounded px-5 py-3">
                    <i class="bi bi-eye me-2"></i>Lihat Detail Transaksi
                </a>

                @if($transaction->seller->phone)
                    <a href="https://wa.me/{{ $transaction->seller->phone }}" target="_blank" class="btn btn-success btn-rounded px-5 py-3">
                        <i class="bi bi-whatsapp me-2"></i>Hubungi Penjual
                    </a>
                @endif

                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
                </a>
            </div>

            <!-- Share -->
            <div class="text-center mt-5">
                <p class="small text-secondary mb-2">Bagikan pengalamanmu!</p>
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-outline-secondary rounded-circle p-3" onclick="shareTransaction()">
                        <i class="bi bi-share"></i>
                    </button>
                    <button class="btn btn-outline-secondary rounded-circle p-3" onclick="window.print()">
                        <i class="bi bi-printer"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function shareTransaction() {
        if (navigator.share) {
            navigator.share({
                title: 'Transaksi WAKANDE',
                text: 'Aku baru saja {{ $transaction->item->type == 'gift' ? 'mendapatkan barang gratis' : 'membeli' }} {{ $transaction->item->name }} di WAKANDE!',
                url: window.location.href,
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link transaksi berhasil disalin!');
        }
    }
</script>
@endpush

@push('styles')
<style>
    .success-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .next-steps-card {
        background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
        border: 1px solid rgba(102,126,234,0.1);
    }

    .success-animation {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [data-bs-theme="dark"] .success-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }
</style>
@endpush
@endsection
