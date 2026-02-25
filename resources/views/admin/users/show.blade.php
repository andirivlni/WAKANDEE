@extends('layouts.admin')

@section('title', 'Detail User - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center"
               style="width: 32px; height: 32px; background: #F8FBF8; border: 1px solid #EDF2F0;">
                <i class="bi bi-arrow-left" style="font-size: 0.9rem;"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-0" style="color: #1A2A24;">Detail User</h5>
                <p class="small text-secondary mb-0" style="font-size: 0.7rem;">Informasi lengkap dan aktivitas pengguna</p>
            </div>
        </div>

        @if($user->role == 'user')
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
                    style="background: {{ $user->is_active ? '#ffc107' : '#22c55e' }}; color: white; border: none; font-size: 0.75rem;"
                    onclick="toggleUserStatus({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'false' : 'true' }})">
                <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }} me-1"></i>
                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>

            <button type="button" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
                    style="background: #dc3545; color: white; border: none; font-size: 0.75rem;"
                    onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                <i class="bi bi-trash me-1"></i>Hapus
            </button>
        </div>
        @endif
    </div>

    <div class="row g-3">
        {{-- LEFT COLUMN - USER PROFILE --}}
        <div class="col-xl-4">
            {{-- PROFILE CARD MINI --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <div class="text-center mb-3">
                    <div class="position-relative d-inline-block">
                        @if($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="" class="rounded-circle"
                                 width="80" height="80" style="object-fit: cover; border: 3px solid rgba(34,197,94,0.2);">
                        @else
                            <div class="avatar-circle mx-auto d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-size: 2rem; background: #22c55e; color: white; border-radius: 50%;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        @if($user->is_active)
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border-2 border-white rounded-circle" style="width: 16px; height: 16px;"></span>
                        @else
                            <span class="position-absolute bottom-0 end-0 p-1 bg-secondary border-2 border-white rounded-circle" style="width: 16px; height: 16px;"></span>
                        @endif
                    </div>

                    <h6 class="fw-bold mt-2 mb-0" style="font-size: 1rem;">{{ $user->name }}</h6>
                    <p class="small text-secondary mb-1" style="font-size: 0.7rem;">{{ $user->email }}</p>

                    @if($user->role == 'admin')
                        <span class="badge rounded-pill px-3 py-1" style="font-size: 0.65rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                            <i class="bi bi-shield me-1" style="font-size: 0.6rem;"></i> Administrator
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-1" style="font-size: 0.65rem; background: #F0F5F0; color: #1A2A24;">
                            <i class="bi bi-person me-1" style="font-size: 0.6rem;"></i> Member
                        </span>
                    @endif
                </div>

                <hr class="opacity-25 my-2">

                {{-- Contact Info --}}
                <div class="vstack gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="info-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #F8FBF8;">
                            <i class="bi bi-building" style="color: #22c55e; font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Sekolah</small>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $user->school ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="info-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #F8FBF8;">
                            <i class="bi bi-book" style="color: #22c55e; font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Kelas</small>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $user->grade ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="info-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #F8FBF8;">
                            <i class="bi bi-whatsapp" style="color: #22c55e; font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">WhatsApp</small>
                            @if($user->phone)
                                <span class="fw-semibold" style="font-size: 0.7rem;">{{ $user->phone }}</span>
                                <a href="https://wa.me/{{ $user->phone }}" target="_blank" class="btn btn-sm rounded-4 px-2 py-0 ms-1"
                                   style="background: #25D366; color: white; font-size: 0.6rem;">
                                    <i class="bi bi-chat"></i>
                                </a>
                            @else
                                <span class="text-secondary" style="font-size: 0.7rem;">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="info-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #F8FBF8;">
                            <i class="bi bi-calendar" style="color: #6c757d; font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Bergabung</small>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $user->created_at->format('d M Y') }}</span>
                            <small class="text-secondary d-block" style="font-size: 0.55rem;">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATS SUMMARY MINI --}}
            <div class="admin-card p-3 rounded-3" style="background: white; border: 1px solid #EDF2F0;">
                <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">
                    <i class="bi bi-pie-chart me-1" style="color: #22c55e;"></i>
                    Ringkasan Aktivitas
                </h6>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background: #F8FBF8;">
                            <p class="fw-bold mb-0" style="color: #22c55e; font-size: 1rem;">{{ number_format($stats['total_items'] ?? 0) }}</p>
                            <small class="text-secondary" style="font-size: 0.55rem;">Total Barang</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background: #F8FBF8;">
                            <p class="fw-bold mb-0" style="color: #198754; font-size: 1rem;">{{ number_format($stats['approved_items'] ?? 0) }}</p>
                            <small class="text-secondary" style="font-size: 0.55rem;">Disetujui</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background: #F8FBF8;">
                            <p class="fw-bold mb-0" style="color: #ffc107; font-size: 1rem;">{{ number_format($stats['pending_items'] ?? 0) }}</p>
                            <small class="text-secondary" style="font-size: 0.55rem;">Pending</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background: #F8FBF8;">
                            <p class="fw-bold mb-0" style="color: #dc3545; font-size: 1rem;">{{ number_format(($stats['total_items'] ?? 0) - ($stats['approved_items'] ?? 0) - ($stats['pending_items'] ?? 0)) }}</p>
                            <small class="text-secondary" style="font-size: 0.55rem;">Ditolak</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background: #F8FBF8;">
                            <p class="fw-bold mb-0" style="color: #0dcaf0; font-size: 1rem;">{{ number_format($stats['total_bought'] ?? 0) }}</p>
                            <small class="text-secondary" style="font-size: 0.55rem;">Pembelian</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background: #F8FBF8;">
                            <p class="fw-bold mb-0" style="color: #6f42c1; font-size: 1rem;">{{ number_format($stats['total_sold'] ?? 0) }}</p>
                            <small class="text-secondary" style="font-size: 0.55rem;">Penjualan</small>
                        </div>
                    </div>
                </div>

                <hr class="opacity-25 my-2">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary" style="font-size: 0.65rem;">Total Transaksi</span>
                    <span class="fw-bold" style="color: #22c55e; font-size: 0.9rem;">
                        Rp {{ number_format(($stats['total_earned'] ?? 0) + ($stats['total_spent'] ?? 0), 0, ',', '.') }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="text-secondary" style="font-size: 0.6rem;">Pendapatan (Jual)</span>
                    <span class="fw-semibold text-success" style="font-size: 0.7rem;">Rp {{ number_format($stats['total_earned'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="text-secondary" style="font-size: 0.6rem;">Pengeluaran (Beli)</span>
                    <span class="fw-semibold text-danger" style="font-size: 0.7rem;">Rp {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN - USER ACTIVITY --}}
        <div class="col-xl-8">
            {{-- USER'S ITEMS MINI --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.8rem;">
                        <i class="bi bi-box me-1" style="color: #22c55e;"></i>
                        Barang Upload
                    </h6>
                    <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: #F0F5F0; color: #1A2A24;">
                        Total: {{ $items->count() }}
                    </span>
                </div>

                @if($items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.7rem;">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items->take(5) as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $itemImages = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []) ?? [];
                                                    $itemThumb = !empty($itemImages) ? Storage::url($itemImages[0]) : asset('images/default-item.png');
                                                @endphp
                                                <img src="{{ $itemThumb }}" alt="" width="28" height="28" style="object-fit: cover; border-radius: 6px;">
                                                <span class="fw-semibold" style="font-size: 0.7rem;">{{ Str::limit($item->name, 20) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: #F0F5F0; color: #1A2A24;">
                                                {{ $item->category_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning rounded-pill px-2 py-0" style="font-size: 0.6rem;">Pending</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge bg-success rounded-pill px-2 py-0" style="font-size: 0.6rem;">Disetujui</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-danger rounded-pill px-2 py-0" style="font-size: 0.6rem;">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $item->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.moderation.show', $item->id) }}" class="btn btn-sm rounded-4 px-2 py-0"
                                               style="font-size: 0.6rem; background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24;">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($items->count() > 5)
                        <div class="text-center mt-2">
                            <button class="btn btn-sm btn-link text-decoration-none" style="color: #22c55e; font-size: 0.65rem;">
                                Lihat Semua Barang <i class="bi bi-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-2">
                        <i class="bi bi-box text-secondary" style="font-size: 1.5rem;"></i>
                        <p class="small text-secondary mt-1 mb-0">User belum memiliki barang</p>
                    </div>
                @endif
            </div>

            {{-- PURCHASE HISTORY MINI --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.8rem;">
                        <i class="bi bi-cart-check me-1" style="color: #22c55e;"></i>
                        Riwayat Pembelian
                    </h6>
                    <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: #F0F5F0; color: #1A2A24;">
                        Total: {{ $bought_transactions->count() }}
                    </span>
                </div>

                @if($bought_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.7rem;">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bought_transactions->take(5) as $trx)
                                    <tr>
                                        <td><span class="fw-mono" style="font-size: 0.6rem;">{{ Str::limit($trx->transaction_code, 8) }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $trxItemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []) ?? [];
                                                    $trxItemThumb = !empty($trxItemImages) ? Storage::url($trxItemImages[0]) : asset('images/default-item.png');
                                                @endphp
                                                <img src="{{ $trxItemThumb }}" alt="" width="28" height="28" style="object-fit: cover; border-radius: 6px;">
                                                <span style="font-size: 0.7rem;">{{ Str::limit($trx->item->name, 15) }}</span>
                                            </div>
                                        </td>
                                        <td><span class="fw-semibold" style="font-size: 0.7rem;">Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</span></td>
                                        <td>
                                            @php $status = $trx->payment_status_label; @endphp
                                            <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: rgba({{ $status['color'] == 'success' ? '25,135,84' : '255,193,7' }}, 0.1); color: {{ $status['color'] == 'success' ? '#198754' : '#ffc107' }};">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td><small class="text-secondary" style="font-size: 0.6rem;">{{ $trx->created_at->format('d/m/Y') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-2">
                        <i class="bi bi-cart text-secondary" style="font-size: 1.5rem;"></i>
                        <p class="small text-secondary mt-1 mb-0">User belum melakukan pembelian</p>
                    </div>
                @endif
            </div>

            {{-- SELL HISTORY MINI --}}
            <div class="admin-card p-3 rounded-3" style="background: white; border: 1px solid #EDF2F0;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.8rem;">
                        <i class="bi bi-truck me-1" style="color: #22c55e;"></i>
                        Riwayat Penjualan
                    </h6>
                    <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: #F0F5F0; color: #1A2A24;">
                        Total: {{ $sold_transactions->count() }}
                    </span>
                </div>

                @if($sold_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.7rem;">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sold_transactions->take(5) as $trx)
                                    <tr>
                                        <td><span class="fw-mono" style="font-size: 0.6rem;">{{ Str::limit($trx->transaction_code, 8) }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $trxItemImages = is_array($trx->item->images) ? $trx->item->images : (is_string($trx->item->images) ? json_decode($trx->item->images, true) : []) ?? [];
                                                    $trxItemThumb = !empty($trxItemImages) ? Storage::url($trxItemImages[0]) : asset('images/default-item.png');
                                                @endphp
                                                <img src="{{ $trxItemThumb }}" alt="" width="28" height="28" style="object-fit: cover; border-radius: 6px;">
                                                <span style="font-size: 0.7rem;">{{ Str::limit($trx->item->name, 15) }}</span>
                                            </div>
                                        </td>
                                        <td><span class="fw-semibold" style="font-size: 0.7rem;">Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</span></td>
                                        <td>
                                            @php $status = $trx->payment_status_label; @endphp
                                            <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: rgba({{ $status['color'] == 'success' ? '25,135,84' : '255,193,7' }}, 0.1); color: {{ $status['color'] == 'success' ? '#198754' : '#ffc107' }};">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td><small class="text-secondary" style="font-size: 0.6rem;">{{ $trx->created_at->format('d/m/Y') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-2">
                        <i class="bi bi-truck text-secondary" style="font-size: 1.5rem;"></i>
                        <p class="small text-secondary mt-1 mb-0">User belum melakukan penjualan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

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
            cancelButtonText: 'Batal',
            background: 'var(--bs-body-bg)',
            color: 'var(--bs-body-color)',
            customClass: { popup: 'rounded-4 p-3', title: 'small fw-bold' }
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `Akun ${userName} telah ${action}.`,
                            showConfirmButton: false,
                            timer: 1500,
                            background: 'var(--bs-body-bg)',
                            color: 'var(--bs-body-color)'
                        }).then(() => location.reload());
                    }
                });
            }
        });
    }

    // Delete user
    function deleteUser(userId, userName) {
        Swal.fire({
            title: 'Hapus User?',
            html: `<p class="small mb-1">Kamu yakin ingin menghapus akun <strong>${userName}</strong>?</p>
                   <p class="small text-danger mb-0">Semua data terkait akan ikut terhapus!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            background: 'var(--bs-body-bg)',
            color: 'var(--bs-body-color)',
            customClass: { popup: 'rounded-4 p-3', title: 'small fw-bold' }
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: `Akun ${userName} berhasil dihapus.`,
                            showConfirmButton: false,
                            timer: 1500,
                            background: 'var(--bs-body-bg)',
                            color: 'var(--bs-body-color)'
                        }).then(() => {
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
    .admin-card {
        transition: all 0.2s;
        background: white;
        border: 1px solid #EDF2F0 !important;
    }

    .admin-card:hover {
        border-color: rgba(34, 197, 94, 0.2) !important;
    }

    .info-icon {
        transition: all 0.2s;
    }

    .admin-card:hover .info-icon {
        transform: scale(1.05);
        background: rgba(34, 197, 94, 0.1) !important;
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    .table th {
        font-weight: 600;
        color: #1A2A24;
        border-bottom: 1px solid #EDF2F0;
        padding: 0.3rem !important;
        font-size: 0.65rem;
    }

    .table td {
        padding: 0.3rem !important;
        border-bottom: 1px solid #F0F5F0;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .admin-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] .table th {
        color: #E0E0E0;
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .table td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }

    [data-bs-theme="dark"] .text-secondary {
        color: #9CA3AF !important;
    }
</style>
@endpush
