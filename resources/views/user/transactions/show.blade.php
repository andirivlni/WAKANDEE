@extends('layouts.app')

@section('title', 'Detail Transaksi - WAKANDE')

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-rounded">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>

        @if($transaction->payment_status == 'pending' && $transaction->buyer_id == Auth::id())
            <a href="{{ route('transactions.payment', $transaction->id) }}" class="btn btn-primary btn-rounded px-4">
                <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
            </a>
        @endif
    </div>

    <!-- Status Banner -->
    <div class="status-banner p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="status-icon">
                        @php
                            $status = $transaction->payment_status_label;
                        @endphp
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 64px; height: 64px; background: rgba({{ $status['color'] == 'success' ? '25,135,84' : ($status['color'] == 'warning' ? '255,193,7' : ($status['color'] == 'info' ? '13,202,240' : '220,53,69')) }}, 0.1);">
                            <i class="bi bi-{{ $transaction->payment_status == 'completed' ? 'check-circle' : ($transaction->payment_status == 'pending' ? 'clock' : ($transaction->payment_status == 'paid' ? 'check' : 'x-circle')) }} fs-2"
                               style="color: {{ $status['color'] == 'success' ? '#198754' : ($status['color'] == 'warning' ? '#ffc107' : ($status['color'] == 'info' ? '#0dcaf0' : '#dc3545')) }};"></i>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-4 py-2 mb-2">
                            {{ $status['label'] }}
                        </span>
                        <h5 class="fw-bold mb-1">Transaksi {{ $transaction->transaction_code }}</h5>
                        <p class="text-secondary mb-0">{{ $transaction->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background: rgba(0,0,0,0.02);">
                            <small class="text-secondary d-block">Metode Pembayaran</small>
                            <span class="fw-semibold">{{ $transaction->payment_method_label }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background: rgba(0,0,0,0.02);">
                            <small class="text-secondary d-block">Metode Pengiriman</small>
                            <span class="fw-semibold">{{ $transaction->delivery_method_label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Item Details -->
        <div class="col-lg-8">
            <!-- Item Card -->
            <div class="detail-card p-4 rounded-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-box me-2" style="color: #667eea;"></i>Detail Barang
                </h6>

                <div class="d-flex gap-4">
                    <!-- Item Image -->
                    <div class="shrink-0">
                        @php
                            $itemImages = is_array($transaction->item->images) ? $transaction->item->images : (is_string($transaction->item->images) ? json_decode($transaction->item->images, true) : []) ?? [];
                            $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                        @endphp
                        <img src="{{ $itemThumb }}" alt="{{ $transaction->item->name }}"
                             width="120" height="120" style="object-fit: cover; border-radius: 16px;">
                    </div>

                    <!-- Item Info -->
                    <div class="grow">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-2">
                                    {{ $transaction->item->category_label }}
                                </span>
                                <h5 class="fw-bold mb-2">{{ $transaction->item->name }}</h5>
                                <p class="text-secondary small mb-3">
                                    {{ Str::limit($transaction->item->description, 150) }}
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <small class="text-secondary d-block">Kondisi</small>
                                <span class="fw-semibold">{{ $transaction->item->condition_label }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-secondary d-block">Tipe</small>
                                <span class="fw-semibold">{{ $transaction->item->type == 'gift' ? 'Gratis' : 'Dijual' }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-secondary d-block">Harga Barang</small>
                                <span class="fw-semibold" style="color: #667eea;">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Timeline -->
            <div class="detail-card p-4 rounded-4 mb-4">
                <h6 class="fw-bold mb-4">
                    <i class="bi bi-clock-history me-2" style="color: #667eea;"></i>Riwayat Transaksi
                </h6>

                <div class="timeline">
                    <!-- Create Transaction -->
                    <div class="timeline-item d-flex gap-3 mb-4">
                        <div class="timeline-icon">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; background: rgba(102,126,234,0.1);">
                                <i class="bi bi-cart text-primary"></i>
                            </div>
                        </div>
                        <div class="grow">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-semibold mb-1">Transaksi Dibuat</h6>
                                <small class="text-secondary">{{ $transaction->created_at->format('H:i') }}</small>
                            </div>
                            <p class="text-secondary small mb-0">{{ $transaction->created_at->format('d F Y') }}</p>
                            <p class="small text-secondary mt-2 mb-0">
                                Transaksi berhasil dibuat oleh {{ $transaction->buyer->name }}
                            </p>
                        </div>
                    </div>

                    <!-- Payment -->
                    @if($transaction->paid_at)
                        <div class="timeline-item d-flex gap-3 mb-4">
                            <div class="timeline-icon">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; background: rgba(25,135,84,0.1);">
                                    <i class="bi bi-credit-card text-success"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-semibold mb-1">Pembayaran Dikonfirmasi</h6>
                                    <small class="text-secondary">{{ $transaction->paid_at->format('H:i') }}</small>
                                </div>
                                <p class="text-secondary small mb-0">{{ $transaction->paid_at->format('d F Y') }}</p>
                                <p class="small text-success mt-2 mb-0">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Pembayaran via {{ $transaction->payment_method_label }} telah dikonfirmasi
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Delivery -->
                    @if($transaction->delivery_method == 'dropoff' && $transaction->payment_status != 'pending' && $transaction->payment_status != 'cancelled')
                        <div class="timeline-item d-flex gap-3 mb-4">
                            <div class="timeline-icon">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; background: rgba(13,202,240,0.1);">
                                    <i class="bi bi-geo-alt text-info"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h6 class="fw-semibold mb-1">Drop-off Point</h6>
                                <p class="text-secondary small mb-1">{{ $transaction->dropoff_point ?? 'Belum dipilih' }}</p>
                                @if($transaction->payment_status == 'paid')
                                    <p class="small text-info mt-2 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Menunggu konfirmasi penerimaan dari pembeli
                                    </p>
                                @elseif($transaction->payment_status == 'completed')
                                    <p class="small text-success mt-2 mb-0">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Barang telah diterima
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Completed -->
                    @if($transaction->completed_at)
                        <div class="timeline-item d-flex gap-3">
                            <div class="timeline-icon">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; background: rgba(25,135,84,0.1);">
                                    <i class="bi bi-check2-all text-success"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-semibold mb-1">Transaksi Selesai</h6>
                                    <small class="text-secondary">{{ $transaction->completed_at->format('H:i') }}</small>
                                </div>
                                <p class="text-secondary small mb-0">{{ $transaction->completed_at->format('d F Y') }}</p>
                                <p class="small text-success mt-2 mb-0">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Barang telah diterima dan transaksi selesai
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Cancelled -->
                    @if($transaction->payment_status == 'cancelled')
                        <div class="timeline-item d-flex gap-3">
                            <div class="timeline-icon">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; background: rgba(220,53,69,0.1);">
                                    <i class="bi bi-x-circle text-danger"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h6 class="fw-semibold mb-1">Transaksi Dibatalkan</h6>
                                <p class="text-secondary small mb-0">{{ $transaction->updated_at->format('d F Y H:i') }}</p>
                                <p class="small text-danger mt-2 mb-0">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    Transaksi dibatalkan
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Legacy Message -->
            @if($transaction->item->legacy_message)
                <div class="legacy-card p-4 rounded-4">
                    <div class="d-flex gap-3">
                        <i class="bi bi-quote fs-1" style="color: #667eea; opacity: 0.3;"></i>
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-2">
                                <i class="bi bi-chat-quote me-1"></i>Legacy Message
                            </span>
                            <p class="fst-italic mb-3" style="font-size: 1.1rem;">
                                "{{ $transaction->item->legacy_message }}"
                            </p>
                            <p class="small text-secondary mb-0">
                                — {{ $transaction->seller->name }}, {{ $transaction->seller->school ?? 'Sekolah' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Buyer/Seller Info & Payment -->
        <div class="col-lg-4">
            <!-- Buyer Info -->
            <div class="detail-card p-4 rounded-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-person me-2" style="color: #667eea;"></i>
                    {{ $transaction->buyer_id == Auth::id() ? 'Informasi Penjual' : 'Informasi Pembeli' }}
                </h6>

                <div class="d-flex align-items-center gap-3 mb-3">
                    @php
                        $user = $transaction->buyer_id == Auth::id() ? $transaction->seller : $transaction->buyer;
                    @endphp

                    @if($user->profile_photo)
                        <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}"
                             class="rounded-circle" width="64" height="64" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle" style="width: 64px; height: 64px; font-size: 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                        <p class="text-secondary small mb-1">{{ $user->school ?? 'Sekolah tidak tersedia' }}</p>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <i class="bi bi-calendar me-1"></i>Bergabung {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                @if($user->phone && $transaction->payment_status != 'cancelled' && $transaction->payment_status != 'completed')
                    <a href="https://wa.me/{{ $user->phone }}" target="_blank" class="btn btn-success rounded-pill w-100 py-2 mt-2">
                        <i class="bi bi-whatsapp me-2"></i>Hubungi via WhatsApp
                    </a>
                @endif
            </div>

            <!-- Payment Summary -->
            <div class="detail-card p-4 rounded-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-credit-card me-2" style="color: #667eea;"></i>Ringkasan Pembayaran
                </h6>

                                <div class="vstack gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Harga Barang</span>
                        <span class="fw-semibold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    </div>

                    @if($transaction->admin_fee > 0)
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Biaya Admin</span>
                            <span class="fw-semibold">Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <hr class="opacity-25">

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold h5 mb-0" style="color: #667eea;">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($transaction->payment_method == 'qris' && $transaction->qris_code)
                        <div class="mt-3 p-3 rounded-3 text-center" style="background: rgba(102,126,234,0.05);">
                            <small class="text-secondary d-block mb-2">Kode QRIS</small>
                            <span class="fw-mono">{{ $transaction->qris_code }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="detail-card p-4 rounded-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-truck me-2" style="color: #667eea;"></i>Informasi Pengiriman
                </h6>

                <div class="vstack gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Metode</span>
                        <span class="fw-semibold">{{ $transaction->delivery_method_label }}</span>
                    </div>

                    @if($transaction->delivery_method == 'dropoff')
                        <div>
                            <span class="text-secondary d-block mb-1">Titik Drop-off</span>
                            <span class="fw-semibold">{{ $transaction->dropoff_point ?? 'Belum dipilih' }}</span>
                        </div>
                    @endif

                    @if($transaction->notes)
                        <div>
                            <span class="text-secondary d-block mb-1">Catatan</span>
                            <p class="small mb-0">{{ $transaction->notes }}</p>
                        </div>
                    @endif

                    @if($transaction->payment_status == 'paid' && $transaction->buyer_id == Auth::id())
                        <button class="btn btn-success rounded-pill py-3 mt-2" onclick="confirmDelivery({{ $transaction->id }})">
                            <i class="bi bi-check2-circle me-2"></i>Konfirmasi Penerimaan
                        </button>
                    @endif

                    @if($transaction->payment_status == 'pending' && $transaction->buyer_id == Auth::id())
                        <button class="btn btn-outline-danger rounded-pill py-3 mt-2" onclick="cancelTransaction({{ $transaction->id }})">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelivery(transactionId) {
        Swal.fire({
            title: 'Konfirmasi Penerimaan',
            text: 'Pastikan kamu sudah menerima barang dalam kondisi baik',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Saya Terima',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/transactions/confirm/${transactionId}`, {
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
                            'Berhasil!',
                            'Penerimaan barang telah dikonfirmasi',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    }

    function cancelTransaction(transactionId) {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            text: 'Transaksi yang dibatalkan tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/transactions/cancel/${transactionId}`, {
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
                            'Transaksi berhasil dibatalkan',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .detail-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .legacy-card {
        background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
        border-left: 6px solid #667eea;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 40px;
        bottom: -20px;
        width: 2px;
        background: rgba(0,0,0,0.05);
    }

    [data-bs-theme="dark"] .detail-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .timeline-item:not(:last-child)::before {
        background: rgba(255,255,255,0.1);
    }
</style>
@endpush
@endsection
