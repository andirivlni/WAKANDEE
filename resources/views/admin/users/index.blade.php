@extends('layouts.admin')

@section('title', 'Manajemen User - WAKANDE')

@section('content')
<div class="container-fluid px-3 px-lg-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1" style="color: #1A2A24;">Manajemen User</h5>
            <p class="small text-secondary mb-0" style="font-size: 0.8rem;">Kelola dan monitoring semua pengguna WAKANDE</p>
        </div>
        <button class="btn btn-sm rounded-4 px-3 py-1" onclick="openCreateAdminModal()"
                style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
            <i class="bi bi-person-plus me-1"></i>Tambah Admin
        </button>
    </div>

    {{-- STATS CARDS MINI --}}
    <div class="row g-2 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-people fs-5" style="color: #22c55e;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1.2rem;">{{ number_format($stats['total_users'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.65rem;">Total Users</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-check-circle fs-5" style="color: #198754;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1.2rem;">{{ number_format($stats['active_users'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.65rem;">Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f3f4f6;">
                        <i class="bi bi-x-circle fs-5" style="color: #6c757d;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1.2rem;">{{ number_format($stats['inactive_users'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.65rem;">Nonaktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-2 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-shield fs-5" style="color: #4ade80;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 1.2rem;">{{ number_format($stats['total_admins'] ?? 0) }}</h5>
                        <small class="text-secondary" style="font-size: 0.65rem;">Admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="admin-card p-2 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
        <div class="row g-2 align-items-center">
            <div class="col-lg-5">
                <form action="{{ route('admin.users.index') }}" method="GET" id="searchForm">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.8rem;"></i>
                        <input type="text" name="search" class="form-control rounded-4 border-0"
                               style="padding-left: 32px; padding-top: 0.4rem; padding-bottom: 0.4rem; background: #F8FBF8; font-size: 0.8rem;"
                               placeholder="Cari nama, email, atau sekolah..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    {{-- Role Filter --}}
                    <select name="role" form="filterForm" class="form-select rounded-4 px-2 py-1"
                            style="width: auto; font-size: 0.75rem; background: #F8FBF8; border: 1px solid #EDF2F0;">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>👤 User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                    </select>

                    {{-- Status Filter --}}
                    <select name="status" form="filterForm" class="form-select rounded-4 px-2 py-1"
                            style="width: auto; font-size: 0.75rem; background: #F8FBF8; border: 1px solid #EDF2F0;">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>❌ Nonaktif</option>
                    </select>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm rounded-4 px-2 py-1 d-flex align-items-center"
                       style="background: white; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.75rem;">
                        <i class="bi bi-arrow-repeat me-1"></i>Reset
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- USERS TABLE --}}
    @if($users->count() > 0)
        <div class="admin-card p-2 rounded-3" style="background: white; border: 1px solid #EDF2F0;">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.75rem;">
                    <thead class="small text-secondary">
                        <tr>
                            <th>User</th>
                            <th>Kontak</th>
                            <th>Sekolah</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th>Statistik</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative">
                                            @if($user->profile_photo)
                                                <img src="{{ Storage::url($user->profile_photo) }}" alt="" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                            @else
                                                <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 32px; height: 32px; background: #22c55e; color: white; font-size: 0.8rem;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            @if($user->is_active)
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border-2 border-white rounded-circle" style="width: 8px; height: 8px;"></span>
                                            @else
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-secondary border-2 border-white rounded-circle" style="width: 8px; height: 8px;"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0" style="font-size: 0.75rem;">{{ $user->name }}</p>
                                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $user->email }}</small>
                                            @if($user->role == 'admin')
                                                <span class="badge rounded-pill px-1 py-0 ms-1" style="font-size: 0.55rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                                                    <i class="bi bi-shield"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->phone)
                                        <span class="small d-block" style="font-size: 0.7rem;">
                                            <i class="bi bi-whatsapp text-success me-1" style="font-size: 0.6rem;"></i>{{ $user->phone }}
                                        </span>
                                    @else
                                        <span class="small text-secondary" style="font-size: 0.7rem;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold d-block" style="font-size: 0.7rem;">{{ $user->school ?? '-' }}</span>
                                    <small class="text-secondary" style="font-size: 0.6rem;">Kelas {{ $user->grade ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                                            <i class="bi bi-shield me-1" style="font-size: 0.5rem;"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: #F0F5F0; color: #1A2A24;">
                                            <i class="bi bi-person me-1" style="font-size: 0.5rem;"></i>User
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: rgba(25,135,84,0.1); color: #198754;">
                                            <i class="bi bi-check-circle me-1" style="font-size: 0.5rem;"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: rgba(108,117,125,0.1); color: #6c757d;">
                                            <i class="bi bi-x-circle me-1" style="font-size: 0.5rem;"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-secondary d-block" style="font-size: 0.6rem;">
                                        <i class="bi bi-calendar me-1"></i>{{ $user->created_at->format('d/m/Y') }}
                                    </span>
                                    <small class="text-secondary" style="font-size: 0.55rem;">
                                        {{ $user->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-0">
                                        <span class="small" style="font-size: 0.6rem;">
                                            <i class="bi bi-box me-1"></i>{{ $user->items_count ?? 0 }}
                                        </span>
                                        <span class="small" style="font-size: 0.6rem;">
                                            <i class="bi bi-cart-check me-1"></i>{{ $user->bought_transactions_count ?? 0 }}
                                        </span>
                                        <span class="small" style="font-size: 0.6rem;">
                                            <i class="bi bi-truck me-1"></i>{{ $user->sold_transactions_count ?? 0 }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                                           style="width: 26px; height: 26px; background: #F8FBF8; border: 1px solid #EDF2F0; color: #1A2A24;"
                                           data-bs-toggle="tooltip" title="Detail">
                                            <i class="bi bi-eye" style="font-size: 0.7rem;"></i>
                                        </a>

                                        @if($user->role == 'user')
                                            <button type="button"
                                                    class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                    style="width: 26px; height: 26px; background: #F8FBF8; border: 1px solid #EDF2F0; color: {{ $user->is_active ? '#ffc107' : '#22c55e' }};"
                                                    data-bs-toggle="tooltip" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                    onclick="toggleUserStatus({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'false' : 'true' }})">
                                                <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }}" style="font-size: 0.7rem;"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                    style="width: 26px; height: 26px; background: #F8FBF8; border: 1px solid #EDF2F0; color: #dc3545;"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="bi bi-trash" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-2">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    @else
        {{-- EMPTY STATE MINI --}}
        <div class="admin-card p-3 rounded-3 text-center" style="background: white; border: 1px solid #EDF2F0;">
            <div class="empty-state">
                <i class="bi bi-people" style="color: #22c55e; opacity: 0.3; font-size: 2rem;"></i>
                <h6 class="fw-semibold mt-2 mb-1" style="color: #1A2A24;">Tidak Ada User</h6>
                <p class="small text-secondary mb-2" style="font-size: 0.75rem;">Belum ada pengguna yang terdaftar di WAKANDE.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm rounded-4 px-2 py-1"
                   style="background: #22c55e; color: white; border: none; font-size: 0.7rem;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

{{-- CREATE ADMIN MODAL MINI --}}
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pt-2 px-3">
                <h6 class="modal-title fw-semibold" style="font-size: 0.9rem;">
                    <i class="bi bi-person-plus me-1" style="color: #22c55e;"></i>
                    Tambah Admin Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
            </div>
            <form action="{{ route('admin.users.store-admin') }}" method="POST" novalidate>
                @csrf
                <div class="modal-body px-3 pb-2">
                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" required style="font-size: 0.75rem;">
                    </div>

                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3" required style="font-size: 0.75rem;">
                    </div>

                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control rounded-start-3" required style="font-size: 0.75rem;">
                            <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('password', 'passwordIcon')" style="padding: 0.2rem 0.6rem;">
                                <i class="bi bi-eye-slash" id="passwordIcon" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control rounded-start-3" required style="font-size: 0.75rem;">
                            <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('password_confirmation', 'confirmIcon')" style="padding: 0.2rem 0.6rem;">
                                <i class="bi bi-eye-slash" id="confirmIcon" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="alert p-1 rounded-2 mt-2" style="background: rgba(34,197,94,0.05);">
                        <small class="text-secondary" style="font-size: 0.6rem;">
                            <i class="bi bi-info-circle me-1" style="color: #22c55e;"></i>
                            Admin akan memiliki akses penuh ke panel moderasi dan manajemen user.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-3 pb-2">
                    <button type="button" class="btn btn-sm rounded-4 px-2 py-0" data-bs-dismiss="modal" style="background: white; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.7rem;">Batal</button>
                    <button type="submit" class="btn btn-sm rounded-4 px-2 py-0" style="background: #22c55e; color: white; border: none; font-size: 0.7rem;">
                        <i class="bi bi-person-plus me-1"></i>Tambah Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden Form for Filters --}}
<form id="filterForm" action="{{ route('admin.users.index') }}" method="GET">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
</form>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }

    // Open create admin modal
    function openCreateAdminModal() {
        new bootstrap.Modal(document.getElementById('createAdminModal')).show();
    }

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
                        }).then(() => location.reload());
                    }
                });
            }
        });
    }

    // Auto submit search with debounce
    let searchTimeout;
    document.querySelector('input[name="search"]')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });

    // Auto submit role filter
    document.querySelector('select[name="role"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Auto submit status filter
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

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
    .user-stat-card {
        background: white;
        border: 1px solid #EDF2F0;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .user-stat-card:hover {
        border-color: #22c55e;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.05);
    }

    .admin-card {
        background: white;
        border: 1px solid #EDF2F0;
        border-radius: 10px;
    }

    .table th {
        font-weight: 600;
        color: #1A2A24;
        border-bottom: 1px solid #EDF2F0;
        padding: 0.4rem !important;
        font-size: 0.7rem;
    }

    .table td {
        padding: 0.4rem !important;
        border-bottom: 1px solid #F0F5F0;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .user-stat-card,
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

    [data-bs-theme="dark"] .modal-content {
        background: #1A1A2C;
    }

    [data-bs-theme="dark"] .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .empty-state {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
