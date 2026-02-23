@extends('layouts.admin')

@section('title', 'Detail User - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-bold mb-1">Detail User</h1>
                <p class="text-secondary mb-0">Informasi lengkap dan aktivitas pengguna</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            @if($user->role == 'user')
                <button type="button"
                        class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} btn-lg rounded-pill px-5 py-3"
                        onclick="toggleUserStatus({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'false' : 'true' }})">
                    <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }} me-2"></i>
                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Akun
                </button>

                <button type="button"
                        class="btn btn-danger btn-lg rounded-pill px-5 py-3"
                        onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                    <i class="bi bi-trash me-2"></i>Hapus Akun
                </button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - User Profile -->
        <div class="col-xl-4">
            <!-- Profile Card -->
            <div class="admin-card p-4 rounded-4 mb-4">
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        @if($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}"
                                 alt="{{ $user->name }}"
                                 class="rounded-circle"
                                 width="120" height="120"
                                 style="object-fit: cover; border: 4px solid rgba(102,126,234,0.2);">
                        @else
                            <div class="avatar-circle mx-auto"
                                 style="width: 120px; height: 120px; font-size: 3rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        @if($user->is_active)
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border-4 border-white rounded-circle" style="width: 24px; height: 24px;"></span>
                        @else
                            <span class="position-absolute bottom-0 end-0 p-2 bg-secondary border-4 border-white rounded-circle" style="width: 24px; height: 24px;"></span>
                        @endif
                    </div>

                    <h4 class="fw-bold mt-3 mb-1">{{ $user->name }}</h4>
                    <p class="text-secondary mb-2">{{ $user->email }}</p>

                    @if($user->role == 'admin')
                        <span class="badge bg-primary rounded-pill px-4 py-2">
                            <i class="bi bi-shield me-1"></i> Administrator
                        </span>
                    @else
                        <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                            <i class="bi bi-person me-1"></i> Member
                        </span>
                    @endif
                </div>

                <hr class="opacity-25">

                <!-- Contact Info -->
                <div class="vstack gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="info-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(102,126,234,0.1);">
                            <i class="bi bi-building" style="color: #667eea;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block">Sekolah</small>
                            <span class="fw-semibold">{{ $user->school ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="info-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(102,126,234,0.1);">
                            <i class="bi bi-book" style="color: #667eea;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block">Kelas</small>
                            <span class="fw-semibold">{{ $user->grade ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="info-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(25,135,84,0.1);">
                            <i class="bi bi-whatsapp" style="color: #198754;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block">WhatsApp</small>
                            @if($user->phone)
                                <span class="fw-semibold">{{ $user->phone }}</span>
                                <a href="https://wa.me/{{ $user->phone }}" target="_blank" class="btn btn-sm btn-success rounded-pill ms-2 px-3">
                                    <i class="bi bi-chat"></i>
                                </a>
                            @else
                                <span class="text-secondary">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="info-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(108,117,125,0.1);">
                            <i class="bi bi-calendar" style="color: #6c757d;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block">Bergabung</small>
                            <span class="fw-semibold">{{ $user->created_at->format('d F Y') }}</span>
                            <small class="text-secondary d-block">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="admin-card p-4 rounded-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-pie-chart me-2" style="color: #667eea;"></i>
                    Ringkasan Aktivitas
                </h5>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(102,126,234,0.05);">
                            <h4 class="fw-bold mb-1" style="color: #667eea;">{{ number_format($stats['total_items'] ?? 0) }}</h4>
                            <small class="text-secondary">Total Barang</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(25,135,84,0.05);">
                            <h4 class="fw-bold mb-1" style="color: #198754;">{{ number_format($stats['approved_items'] ?? 0) }}</h4>
                            <small class="text-secondary">Disetujui</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(255,193,7,0.05);">
                            <h4 class="fw-bold mb-1" style="color: #ffc107;">{{ number_format($stats['pending_items'] ?? 0) }}</h4>
                            <small class="text-secondary">Pending</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(220,53,69,0.05);">
                            <h4 class="fw-bold mb-1" style="color: #dc3545;">{{ number_format($stats['total_items'] - $stats['approved_items'] - $stats['pending_items'] ?? 0) }}</h4>
                            <small class="text-secondary">Ditolak</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(13,202,240,0.05);">
                            <h4 class="fw-bold mb-1" style="color: #0dcaf0;">{{ number_format($stats['total_bought'] ?? 0) }}</h4>
                            <small class="text-secondary">Pembelian</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(111,66,193,0.05);">
                            <h4 class="fw-bold mb-1" style="color: #6f42c1;">{{ number_format($stats['total_sold'] ?? 0) }}</h4>
                            <small class="text-secondary">Penjualan</small>
                        </div>
                    </div>
                </div>

                <hr class="opacity-25 my-3">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary">Total Transaksi</span>
                    <span class="fw-bold h5 mb-0" style="color: #667eea;">
                        Rp {{ number_format(($stats['total_earned'] ?? 0) + ($stats['total_spent'] ?? 0), 0, ',', '.') }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="text-secondary small">Pendapatan (Jual)</span>
                    <span class="fw-semibold text-success">Rp {{ number_format($stats['total_earned'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="text-secondary small">Pengeluaran (Beli)</span>
                    <span class="fw-semibold text-danger">Rp {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column - User Activity -->
        <div class="col-xl-8">
            <!-- User's Items -->
            <div class="admin-card p-4 rounded-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-box me-2" style="color: #667eea;"></i>
                        Barang Upload
                    </h5>
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                        Total: {{ $items->count() }} barang
                    </span>
                </div>

                @if($items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="small text-secondary">
                                <tr>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $itemImages = json_decode($item->images, true) ?? [];
                                                    $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                                @endphp
                                                <img src="{{ $itemThumb }}" alt="{{ $item->name }}" width="40" height="40" style="object-fit: cover; border-radius: 8px;">
                                                <span class="small fw-semibold">{{ Str::limit($item->name, 30) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                                {{ $item->category_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->type == 'gift')
                                                <span class="badge bg-success bg-opacity-10 text-success">Gratis</span>
                                            @else
                                                <span class="badge bg-primary bg-opacity-10 text-primary">Dijual</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->type == 'sale')
                                                <span class="fw-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                            @else
                                                <span class="fw-semibold text-success">Gratis</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($item->status == 'sold')
                                                <span class="badge bg-secondary">Terjual</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-secondary">{{ $item->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.moderation.show', $item->id) }}"
                                               class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($items->count() > 5)
                        <div class="text-center mt-3">
                            <button class="btn btn-link text-decoration-none" style="color: #667eea;">
                                Lihat Semua Barang <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-box text-secondary fs-1 mb-3"></i>
                        <p class="text-secondary mb-0">User belum memiliki barang</p>
                    </div>
                @endif
            </div>

            <!-- User's Transactions as Buyer -->
            <div class="admin-card p-4 rounded-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-cart-check me-2" style="color: #667eea;"></i>
                        Riwayat Pembelian
                    </h5>
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                        Total: {{ $bought_transactions->count() }} transaksi
                    </span>
                </div>

                @if($bought_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="small text-secondary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th>Penjual</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bought_transactions as $trx)
                                    <tr>
                                        <td>
                                            <span class="fw-mono small">{{ Str::limit($trx->transaction_code, 12) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $trxItemImages = json_decode($trx->item->images, true) ?? [];
                                                    $trxItemThumb = !empty($trxItemImages) ? Storage::url($trxItemImages[0]) : asset('images/default-item.png');
                                                @endphp
                                                <img src="{{ $trxItemThumb }}" alt="{{ $trx->item->name }}" width="40" height="40" style="object-fit: cover; border-radius: 8px;">
                                                <span class="small">{{ Str::limit($trx->item->name, 20) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small">{{ $trx->seller->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $trx->payment_status_label;
                                            @endphp
                                            <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-3 py-2">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-secondary">{{ $trx->created_at->format('d/m/Y') }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-cart text-secondary fs-1 mb-3"></i>
                        <p class="text-secondary mb-0">User belum melakukan pembelian</p>
                    </div>
                @endif
            </div>

            <!-- User's Transactions as Seller -->
            <div class="admin-card p-4 rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-truck me-2" style="color: #667eea;"></i>
                        Riwayat Penjualan
                    </h5>
                    <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                        Total: {{ $sold_transactions->count() }} transaksi
                    </span>
                </div>

                @if($sold_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="small text-secondary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th>Pembeli</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sold_transactions as $trx)
                                    <tr>
                                        <td>
                                            <span class="fw-mono small">{{ Str::limit($trx->transaction_code, 12) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $trxItemImages = json_decode($trx->item->images, true) ?? [];
                                                    $trxItemThumb = !empty($trxItemImages) ? Storage::url($trxItemImages[0]) : asset('images/default-item.png');
                                                @endphp
                                                <img src="{{ $trxItemThumb }}" alt="{{ $trx->item->name }}" width="40" height="40" style="object-fit: cover; border-radius: 8px;">
                                                <span class="small">{{ Str::limit($trx->item->name, 20) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small">{{ $trx->buyer->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $trx->payment_status_label;
                                            @endphp
                                            <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-3 py-2">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-secondary">{{ $trx->created_at->format('d/m/Y') }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-truck text-secondary fs-1 mb-3"></i>
                        <p class="text-secondary mb-0">User belum melakukan penjualan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle user status
    function toggleUserStatus(userId, userName, activate) {
        const action = activate ? 'mengaktifkan' : 'menonaktifkan';

        Swal.fire({
            title: `${activate ? 'Aktifkan' : 'Nonaktifkan'} User?`,
            text: `Kamu yakin ingin ${action} akun ${userName}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: activate ? '#198754' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Ya, ${activate ? 'Aktifkan' : 'Nonaktifkan'}`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${userId}/toggle-status`, {
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
                            `Akun ${userName} telah ${action}.`,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    }

    // Delete user
    function deleteUser(userId, userName) {
        Swal.fire({
            title: 'Hapus User?',
            html: `<p>Kamu yakin ingin menghapus akun <strong>${userName}</strong>?</p>
                   <p class="small text-danger">Semua data terkait (barang, transaksi) akan ikut terhapus!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Dihapus!',
                            `Akun ${userName} berhasil dihapus.`,
                            'success'
                        ).then(() => {
                            window.location.href = '{{ route("admin.users.index") }}';
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
    .info-icon {
        transition: all 0.3s;
    }

    .admin-card:hover .info-icon {
        transform: scale(1.1);
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    [data-bs-theme="dark"] .admin-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }
</style>
@endpush
@endsection
