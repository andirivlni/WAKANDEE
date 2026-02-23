@extends('layouts.admin')

@section('title', 'Moderasi Barang - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Moderasi Barang</h1>
            <p class="text-secondary mb-0">Verifikasi dan kelola barang yang diupload oleh pengguna</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-4 py-3">
                <i class="bi bi-clock me-2"></i>{{ $counts['pending'] ?? 0 }} Menunggu
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="moderation-stat-card p-4 rounded-4 {{ request('status') == 'pending' ? 'active' : '' }}" onclick="window.location='{{ route('admin.moderation.index', ['status' => 'pending']) }}'">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(255,193,7,0.1);">
                        <i class="bi bi-clock fs-3" style="color: #ffc107;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ number_format($counts['pending'] ?? 0) }}</h3>
                        <p class="text-secondary mb-0">Menunggu</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="moderation-stat-card p-4 rounded-4 {{ request('status') == 'approved' ? 'active' : '' }}" onclick="window.location='{{ route('admin.moderation.index', ['status' => 'approved']) }}'">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(25,135,84,0.1);">
                        <i class="bi bi-check-circle fs-3" style="color: #198754;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ number_format($counts['approved'] ?? 0) }}</h3>
                        <p class="text-secondary mb-0">Disetujui</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="moderation-stat-card p-4 rounded-4 {{ request('status') == 'rejected' ? 'active' : '' }}" onclick="window.location='{{ route('admin.moderation.index', ['status' => 'rejected']) }}'">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(220,53,69,0.1);">
                        <i class="bi bi-x-circle fs-3" style="color: #dc3545;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ number_format($counts['rejected'] ?? 0) }}</h3>
                        <p class="text-secondary mb-0">Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="admin-card p-4 rounded-4 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <form action="{{ route('admin.moderation.index') }}" method="GET" id="searchForm">
                    <div class="search-box position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text"
                               name="search"
                               class="form-control form-control-lg rounded-pill border-0 shadow-none"
                               style="padding-left: 45px; background: rgba(102,126,234,0.02);"
                               placeholder="Cari berdasarkan nama barang, deskripsi, atau pengupload..."
                               value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <!-- Category Filter -->
                    <select name="category" form="filterForm" class="form-select rounded-pill px-4 py-2" style="width: auto;">
                        <option value="">Semua Kategori</option>
                        <option value="buku" {{ request('category') == 'buku' ? 'selected' : '' }}>📚 Buku</option>
                        <option value="seragam" {{ request('category') == 'seragam' ? 'selected' : '' }}>👕 Seragam</option>
                        <option value="alat_praktikum" {{ request('category') == 'alat_praktikum' ? 'selected' : '' }}>🔬 Alat Praktikum</option>
                        <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                    </select>

                    <!-- Type Filter -->
                    <select name="type" form="filterForm" class="form-select rounded-pill px-4 py-2" style="width: auto;">
                        <option value="">Semua Tipe</option>
                        <option value="gift" {{ request('type') == 'gift' ? 'selected' : '' }}>🎁 Gratis</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>💰 Dijual</option>
                    </select>

                    <a href="{{ route('admin.moderation.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    @if($items->count() > 0)
        <div class="admin-card p-4 rounded-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="small text-secondary">
                        <tr>
                            <th>Barang</th>
                            <th>Uploader</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $images = $item->images ?? [];
$thumb = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                                        @endphp
                                        <div class="position-relative">
                                            <img src="{{ $thumb }}" alt="{{ $item->name }}" width="56" height="56" style="object-fit: cover; border-radius: 12px;">
                                            @if($item->status == 'pending')
                                                <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill p-1">
                                                    <i class="bi bi-exclamation"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ Str::limit($item->name, 40) }}</h6>
                                            <small class="text-secondary d-block">
                                                <i class="bi bi-tag me-1"></i>{{ $item->condition_label }}
                                            </small>
                                            @if($item->legacy_message)
                                                <small class="text-secondary d-block">
                                                    <i class="bi bi-quote me-1"></i>{{ Str::limit($item->legacy_message, 30) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->user->profile_photo)
                                            <img src="{{ Storage::url($item->user->profile_photo) }}" alt="{{ $item->user->name }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle" style="width: 36px; height: 36px; background: rgba(102,126,234,0.1); color: #667eea;">
                                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-semibold d-block small">{{ $item->user->name }}</span>
                                            <small class="text-secondary">{{ $item->user->school ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        {{ $item->category_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->type == 'gift')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                            <i class="bi bi-gift me-1"></i>Gratis
                                        </span>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                            <i class="bi bi-tag me-1"></i>Dijual
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->type == 'sale')
                                        <span class="fw-semibold" style="color: #667eea;">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="fw-semibold text-success">Gratis</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-secondary d-block">
                                        <i class="bi bi-calendar me-1"></i>{{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                    <small class="text-secondary">
                                        <i class="bi bi-clock me-1"></i>{{ $item->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge bg-warning rounded-pill px-3 py-2">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>Disetujui
                                        </span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.moderation.show', $item->id) }}"
                                       class="btn btn-sm btn-{{ $item->status == 'pending' ? 'primary' : 'outline-secondary' }} rounded-pill px-4">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="admin-card p-5 rounded-4 text-center">
            <div class="empty-state">
                @if(request('status') == 'pending')
                    <i class="bi bi-check-circle text-success fs-1 mb-3"></i>
                    <h5 class="fw-bold mb-3">Semua Barang Sudah Dimoderasi! 🎉</h5>
                    <p class="text-secondary mb-4">Tidak ada barang yang menunggu persetujuan saat ini.</p>
                @elseif(request('status') == 'approved')
                    <i class="bi bi-box fs-1 text-secondary opacity-25 mb-3"></i>
                    <h5 class="fw-bold mb-3">Belum Ada Barang Disetujui</h5>
                    <p class="text-secondary mb-4">Barang yang disetujui akan muncul di sini.</p>
                @elseif(request('status') == 'rejected')
                    <i class="bi bi-box fs-1 text-secondary opacity-25 mb-3"></i>
                    <h5 class="fw-bold mb-3">Belum Ada Barang Ditolak</h5>
                    <p class="text-secondary mb-4">Barang yang ditolak akan muncul di sini.</p>
                @else
                    <i class="bi bi-box fs-1 text-secondary opacity-25 mb-3"></i>
                    <h5 class="fw-bold mb-3">Tidak Ada Barang</h5>
                    <p class="text-secondary mb-4">Belum ada barang yang diupload oleh pengguna.</p>
                @endif

                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary rounded-pill px-5 py-3">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Hidden Form for Filters -->
<form id="filterForm" action="{{ route('admin.moderation.index') }}" method="GET">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
</form>

@push('scripts')
<script>
    // Auto submit search with debounce
    let searchTimeout;
    document.querySelector('input[name="search"]')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });

    // Auto submit category filter
    document.querySelector('select[name="category"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Auto submit type filter
    document.querySelector('select[name="type"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
@endpush

@push('styles')
<style>
    .moderation-stat-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: all 0.3s;
        cursor: pointer;
    }

    .moderation-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 24px rgba(102,126,234,0.08);
    }

    .moderation-stat-card.active {
        border: 2px solid #667eea;
        background: rgba(102,126,234,0.02);
    }

    [data-bs-theme="dark"] .moderation-stat-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    .empty-state {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@endsection
