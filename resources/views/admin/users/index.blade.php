@extends('layouts.admin')

@section('title', 'Manajemen User - WAKANDE')

@section('content')
<div class="container-fluid px-3 px-lg-4">
    <!-- Header -->
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Manajemen User</h1>
            <p class="text-secondary mb-0">Kelola dan monitoring semua pengguna WAKANDE</p>
        </div>
        <button class="btn btn-success btn-sm rounded-pill px-3 py-2" onclick="openCreateAdminModal()">
            <i class="bi bi-person-plus me-2"></i>Tambah Admin
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-3 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7;">
                        <i class="bi bi-people fs-4" style="color: #22c55e;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['total_users'] ?? 0) }}</h4>
                        <small class="text-secondary">Total Users</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-3 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7;">
                        <i class="bi bi-check-circle fs-4" style="color: #198754;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['active_users'] ?? 0) }}</h4>
                        <small class="text-secondary">Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-3 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #f3f4f6;">
                        <i class="bi bi-x-circle fs-4" style="color: #6c757d;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['inactive_users'] ?? 0) }}</h4>
                        <small class="text-secondary">Nonaktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="user-stat-card p-3 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7;">
                        <i class="bi bi-shield fs-4" style="color: #4ade80;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['total_admins'] ?? 0) }}</h4>
                        <small class="text-secondary">Admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="admin-card p-3 rounded-3 mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-lg-5">
                <form action="{{ route('admin.users.index') }}" method="GET" id="searchForm">
                    <div class="search-box position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.9rem;"></i>
                        <input type="text"
                               name="search"
                               class="form-control form-control-sm rounded-pill border-0 shadow-none"
                               style="padding-left: 35px; background: #f8fafc; font-size: 0.9rem;"
                               placeholder="Cari nama, email, atau sekolah..."
                               value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <!-- Role Filter -->
                    <select name="role" form="filterForm" class="form-select form-select-sm rounded-pill px-3 py-2" style="width: auto; font-size: 0.9rem;">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>👤 User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" form="filterForm" class="form-select form-select-sm rounded-pill px-3 py-2" style="width: auto; font-size: 0.9rem;">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>❌ Nonaktif</option>
                    </select>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2" style="font-size: 0.9rem;">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    @if($users->count() > 0)
        <div class="admin-card p-3 rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.9rem;">
                    <thead class="small text-secondary">
                        <tr>
                            <th>User</th>
                            <th>Kontak</th>
                            <th>Sekolah & Kelas</th>
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
                                                <img src="{{ Storage::url($user->profile_photo) }}"
                                                     alt="{{ $user->name }}"
                                                     class="rounded-circle"
                                                     width="40" height="40"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="avatar-circle"
                                                     style="width: 40px; height: 40px; background: #22c55e; font-size: 1rem;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            @if($user->is_active)
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border-2 border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                                            @else
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-secondary border-2 border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">{{ $user->name }}</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">{{ $user->email }}</small>
                                            @if($user->role == 'admin')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0 ms-1" style="font-size: 0.65rem;">
                                                    <i class="bi bi-shield"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->phone)
                                        <span class="small d-block" style="font-size: 0.8rem;">
                                            <i class="bi bi-whatsapp text-success me-1" style="font-size: 0.75rem;"></i>{{ $user->phone }}
                                        </span>
                                    @else
                                        <span class="small text-secondary" style="font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold d-block" style="font-size: 0.8rem;">{{ $user->school ?? '-' }}</span>
                                    <small class="text-secondary" style="font-size: 0.7rem;">Kelas {{ $user->grade ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-shield me-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-person me-1"></i>User
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-x-circle me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-secondary d-block" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar me-1"></i>{{ $user->created_at->format('d/m/Y') }}
                                    </span>
                                    <small class="text-secondary" style="font-size: 0.65rem;">
                                        {{ $user->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-0">
                                        <span class="small" style="font-size: 0.7rem;">
                                            <i class="bi bi-box me-1"></i>{{ $user->items_count ?? 0 }} barang
                                        </span>
                                        <span class="small" style="font-size: 0.7rem;">
                                            <i class="bi bi-cart-check me-1"></i>{{ $user->bought_transactions_count ?? 0 }} beli
                                        </span>
                                        <span class="small" style="font-size: 0.7rem;">
                                            <i class="bi bi-truck me-1"></i>{{ $user->sold_transactions_count ?? 0 }} jual
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="btn btn-sm btn-outline-success rounded-circle p-1"
                                           style="width: 32px; height: 32px;"
                                           data-bs-toggle="tooltip"
                                           title="Detail">
                                            <i class="bi bi-eye" style="font-size: 0.9rem;"></i>
                                        </a>

                                        @if($user->role == 'user')
                                            <button type="button"
                                                    class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-circle p-1"
                                                    style="width: 32px; height: 32px;"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                    onclick="toggleUserStatus({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'false' : 'true' }})">
                                                <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }}" style="font-size: 0.9rem;"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-circle p-1"
                                                    style="width: 32px; height: 32px;"
                                                    data-bs-toggle="tooltip"
                                                    title="Hapus"
                                                    onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="bi bi-trash" style="font-size: 0.9rem;"></i>
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
            <div class="d-flex justify-content-center mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="admin-card p-4 rounded-3 text-center">
            <div class="empty-state">
                <i class="bi bi-people fs-1 text-secondary opacity-25 mb-3"></i>
                <h5 class="fw-bold mb-3">Tidak Ada User</h5>
                <p class="text-secondary mb-4">Belum ada pengguna yang terdaftar di WAKANDE.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-success rounded-pill px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Create Admin Modal -->
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header border-0 pt-3 px-3">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-person-plus me-2" style="color: #22c55e;"></i>
                    Tambah Admin Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store-admin') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body px-3 pb-3">
                    <div class="mb-2">
                        <label for="name" class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control form-control-sm rounded-3"
                               id="name"
                               name="name"
                               placeholder="Masukkan nama admin"
                               required>
                    </div>

                    <div class="mb-2">
                        <label for="email" class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control form-control-sm rounded-3"
                               id="email"
                               name="email"
                               placeholder="admin@contoh.com"
                               required>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control form-control-sm rounded-start-3"
                                   id="password"
                                   name="password"
                                   placeholder="Minimal 8 karakter"
                                   required>
                            <button class="btn btn-outline-secondary btn-sm rounded-end-3"
                                    type="button"
                                    onclick="togglePassword('password', 'passwordIcon')">
                                <i class="bi bi-eye-slash" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control form-control-sm rounded-start-3"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Masukkan password yang sama"
                                   required>
                            <button class="btn btn-outline-secondary btn-sm rounded-end-3"
                                    type="button"
                                    onclick="togglePassword('password_confirmation', 'confirmIcon')">
                                <i class="bi bi-eye-slash" id="confirmIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info rounded-3 border-0 mt-2 p-2" style="background: #f0fdf4;">
                        <i class="bi bi-info-circle me-2" style="color: #22c55e; font-size: 0.8rem;"></i>
                        <small class="text-secondary" style="font-size: 0.75rem;">Admin akan memiliki akses penuh ke panel moderasi dan manajemen user.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-3 pb-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3" style="background: #22c55e; border: none;">
                        <i class="bi bi-person-plus me-2"></i>Tambah Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Form for Filters -->
<form id="filterForm" action="{{ route('admin.users.index') }}" method="GET">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
</form>

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
                            location.reload();
                        });
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
    .user-stat-card {
        background: white;
        border: 1px solid rgba(34, 197, 94, 0.1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: all 0.3s;
    }

    .user-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    .table > :not(caption) > * > * {
        padding: 0.75rem 0.5rem !important;
        background: transparent;
        border-bottom-color: rgba(34, 197, 94, 0.05);
    }

    .admin-card {
        background: white;
        border: 1px solid rgba(34, 197, 94, 0.1);
        border-radius: 12px;
    }

    /* Dark mode */
    [data-bs-theme="dark"] .user-stat-card,
    [data-bs-theme="dark"] .admin-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .table > :not(caption) > * > * {
        border-bottom-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .modal-content {
        background: #1a1a2c;
    }

    .empty-state {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsif */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .table td, .table th {
            padding: 0.5rem 0.25rem !important;
            font-size: 0.8rem;
        }
    }
</style>
@endpush
@endsection
