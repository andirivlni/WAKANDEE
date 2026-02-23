@extends('layouts.app')

@section('title', 'Checkout - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 mb-3">
                    <i class="bi bi-cart-check me-2"></i>CHECKOUT
                </span>
                <h1 class="h2 fw-bold mb-3">Selesaikan Transaksimu</h1>
                <p class="text-secondary">
                    {{ $item->type == 'gift' ? 'Ambil barang gratis' : 'Beli barang' }} dari {{ $item->user->name }}
                </p>
            </div>

            <form action="{{ route('transactions.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <!-- Item Summary -->
                <div class="checkout-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="checkout-step d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">1</span>
                        </div>
                        <h5 class="fw-bold mb-0">Ringkasan Barang</h5>
                    </div>

                    <div class="d-flex gap-4">
                        <!-- Item Image -->
                        <div class="flex-shrink-0">
                            @php
                                $images = $item->images ?? [];
                                if (is_string($images)) {
                                    $images = is_array($images) ? $images : (is_string($images) ? json_decode($images, true) : []) ?? [];
                                }
                                $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                            @endphp
                            <img src="{{ $firstImage }}" alt="{{ $item->name }}"
                                 width="100" height="100" style="object-fit: cover; border-radius: 16px;">
                        </div>

                        <!-- Item Info -->
                        <div class="flex-grow-1">
                            <div>
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-2">
                                    {{ $item->category_label }}
                                </span>
                                <h5 class="fw-bold mb-2">{{ $item->name }}</h5>
                                <p class="text-secondary small mb-0">
                                    Kondisi: {{ $item->condition_label }} •
                                    {{ $item->views_count }}x dilihat
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Method -->
                <div class="checkout-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="checkout-step d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">2</span>
                        </div>
                        <h5 class="fw-bold mb-0">Metode Pengiriman</h5>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="delivery-option p-4 rounded-4 {{ old('delivery_method') == 'dropoff' ? 'active' : '' }}" onclick="selectDelivery('dropoff')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="dropoff" value="dropoff" {{ old('delivery_method', 'dropoff') == 'dropoff' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="dropoff">
                                        <span class="fw-semibold d-block mb-2">
                                            <i class="bi bi-building me-2"></i>Drop-off Point
                                        </span>
                                        <span class="small text-secondary">Ambil barang di titik yang sudah ditentukan di sekolah</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="delivery-option p-4 rounded-4 {{ old('delivery_method') == 'cod' ? 'active' : '' }}" onclick="selectDelivery('cod')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="cod" value="cod" {{ old('delivery_method') == 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cod">
                                        <span class="fw-semibold d-block mb-2">
                                            <i class="bi bi-truck me-2"></i>COD / Serah Terima
                                        </span>
                                        <span class="small text-secondary">Temui penjual langsung di lokasi yang disepakati</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropoff Point Selection -->
                    <div id="dropoffField" class="mt-4" style="{{ old('delivery_method', 'dropoff') == 'dropoff' ? '' : 'display: none;' }}">
                        <label for="dropoff_point" class="form-label fw-semibold">
                            Pilih Titik Drop-off <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg rounded-3" id="dropoff_point" name="dropoff_point">
                            <option value="">Pilih titik pengambilan</option>
                            <option value="Piket Sekolah" {{ old('dropoff_point') == 'Piket Sekolah' ? 'selected' : '' }}>🏫 Piket Sekolah</option>
                            <option value="Perpustakaan" {{ old('dropoff_point') == 'Perpustakaan' ? 'selected' : '' }}>📚 Perpustakaan</option>
                            <option value="Lobi Utama" {{ old('dropoff_point') == 'Lobi Utama' ? 'selected' : '' }}>🏛️ Lobi Utama</option>
                            <option value="Kantor OSIS" {{ old('dropoff_point') == 'Kantor OSIS' ? 'selected' : '' }}>📋 Kantor OSIS</option>
                        </select>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="checkout-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="checkout-step d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">3</span>
                        </div>
                        <h5 class="fw-bold mb-0">Metode Pembayaran</h5>
                    </div>

                    <div class="row g-3">
                        @if($item->type == 'sale')
                            <div class="col-md-6">
                                <div class="payment-option p-4 rounded-4 {{ old('payment_method') == 'bank_transfer' ? 'active' : '' }}" onclick="selectPayment('bank_transfer')">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="bank_transfer">
                                            <span class="fw-semibold d-block mb-2">
                                                <i class="bi bi-bank me-2"></i>Transfer Bank
                                            </span>
                                            <span class="small text-secondary">Transfer ke rekening WAKANDE</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div class="payment-option p-4 rounded-4 {{ old('payment_method') == 'cod' ? 'active' : '' }}" onclick="selectPayment('cod')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" {{ old('payment_method', $item->type == 'gift' ? 'cod' : '') == 'cod' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="payment_cod">
                                        <span class="fw-semibold d-block mb-2">
                                            <i class="bi bi-cash me-2"></i>COD / Tunai
                                        </span>
                                        <span class="small text-secondary">Bayar saat serah terima barang</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="checkout-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="checkout-step d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">4</span>
                        </div>
                        <h5 class="fw-bold mb-0">Catatan (Opsional)</h5>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold">
                            Tambahkan Catatan untuk Penjual
                        </label>
                        <textarea class="form-control rounded-4" id="notes" name="notes" rows="3"
                                  placeholder="Contoh: Bisa ambil jam istirahat, titip di piket, dll">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Summary -->
                <div class="checkout-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="checkout-step d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">5</span>
                        </div>
                        <h5 class="fw-bold mb-0">Ringkasan Pembayaran</h5>
                    </div>

                    <div class="vstack gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Harga Barang</span>
                            <span class="fw-semibold">Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</span>
                        </div>

                        @if($item->type == 'sale')
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Biaya Admin</span>
                                <span class="fw-semibold">Rp 1.000</span>
                            </div>
                        @endif

                        <hr class="opacity-25">

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold h4 mb-0" style="color: #667eea;">
                                Rp {{ number_format(($item->price ?? 0) + ($item->type == 'sale' ? 1000 : 0), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Terms & Submit -->
                <div class="checkout-card p-4 p-md-5">
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label small text-secondary" for="terms">
                            Saya setuju dengan <a href="#" class="text-decoration-none" style="color: #667eea;">Syarat & Ketentuan</a>
                            dan akan melakukan transaksi dengan jujur dan bertanggung jawab
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-rounded py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="bi bi-check2-circle me-2"></i>
                            {{ $item->type == 'gift' ? 'Ambil Barang Gratis' : 'Buat Pesanan' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectDelivery(method) {
        document.querySelectorAll('.delivery-option').forEach(el => {
            el.classList.remove('active');
        });

        if (method === 'dropoff') {
            document.getElementById('dropoff').checked = true;
            document.getElementById('dropoffField').style.display = 'block';
            document.querySelector('.delivery-option:has(#dropoff)').classList.add('active');
        } else {
            document.getElementById('cod').checked = true;
            document.getElementById('dropoffField').style.display = 'none';
            document.querySelector('.delivery-option:has(#cod)').classList.add('active');
        }
    }

    function selectPayment(method) {
        document.querySelectorAll('.payment-option').forEach(el => {
            el.classList.remove('active');
        });

        if (method === 'bank_transfer') {
            document.getElementById('bank_transfer').checked = true;
            document.querySelector('.payment-option:has(#bank_transfer)').classList.add('active');
        } else if (method === 'cod') {
            document.getElementById('payment_cod').checked = true;
            document.querySelector('.payment-option:has(#payment_cod)').classList.add('active');
        }
    }

    // Form validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endpush

@push('styles')
<style>
    .checkout-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        border-radius: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .delivery-option, .payment-option {
        border: 1px solid rgba(0,0,0,0.05);
        cursor: pointer;
        transition: all 0.3s;
    }

    .delivery-option:hover, .payment-option:hover {
        border-color: #667eea;
        background: rgba(102,126,234,0.02);
    }

    .delivery-option.active, .payment-option.active {
        border-color: #667eea;
        background: rgba(102,126,234,0.05);
    }

    [data-bs-theme="dark"] .checkout-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .delivery-option,
    [data-bs-theme="dark"] .payment-option {
        border-color: rgba(255,255,255,0.1);
    }
</style>
@endpush
@endsection
