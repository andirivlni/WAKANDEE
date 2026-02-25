@extends('layouts.admin')

@section('title', 'Moderasi Barang - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1" style="color: #1A2A24;">Moderasi Barang</h5>
            <p class="small text-secondary mb-0" style="font-size: 0.8rem;">Verifikasi dan kelola barang yang diupload oleh pengguna</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2" style="font-size: 0.8rem;">
                <i class="bi bi-clock me-1"></i>{{ $counts['pending'] ?? 0 }} Menunggu
            </span>
        </div>
    </div>

    {{-- STATS CARDS MINI --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="moderation-stat-card p-3 rounded-3 {{ request('status') == 'pending' ? 'active' : '' }}"
                 onclick="window.location='{{ route('admin.moderation.index', ['status' => 'pending']) }}'"
                 style="cursor: pointer; background: white; border: 1px solid #EDF2F0;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 44px; height: 44px; background: #fef3c7;">
                        <i class="bi bi-clock fs-5" style="color: #ffc107;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="font-size: 1.3rem;">{{ number_format($counts['pending'] ?? 0) }}</h4>
                        <p class="text-secondary mb-0" style="font-size: 0.75rem;">Menunggu</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="moderation-stat-card p-3 rounded-3 {{ request('status') == 'approved' ? 'active' : '' }}"
                 onclick="window.location='{{ route('admin.moderation.index', ['status' => 'approved']) }}'"
                 style="cursor: pointer; background: white; border: 1px solid #EDF2F0;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 44px; height: 44px; background: #dcfce7;">
                        <i class="bi bi-check-circle fs-5" style="color: #198754;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="font-size: 1.3rem;">{{ number_format($counts['approved'] ?? 0) }}</h4>
                        <p class="text-secondary mb-0" style="font-size: 0.75rem;">Disetujui</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="moderation-stat-card p-3 rounded-3 {{ request('status') == 'rejected' ? 'active' : '' }}"
                 onclick="window.location='{{ route('admin.moderation.index', ['status' => 'rejected']) }}'"
                 style="cursor: pointer; background: white; border: 1px solid #EDF2F0;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 44px; height: 44px; background: #fee2e2;">
                        <i class="bi bi-x-circle fs-5" style="color: #dc3545;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="font-size: 1.3rem;">{{ number_format($counts['rejected'] ?? 0) }}</h4>
                        <p class="text-secondary mb-0" style="font-size: 0.75rem;">Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="admin-card p-3 rounded-3 mb-4" style="background: white; border: 1px solid #EDF2F0;">
        <div class="row g-2 align-items-center">
            <div class="col-lg-6">
                <form action="{{ route('admin.moderation.index') }}" method="GET" id="searchForm">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.85rem;"></i>
                        <input type="text" name="search" class="form-control rounded-4 border-0"
                               style="padding-left: 35px; padding-top: 0.5rem; padding-bottom: 0.5rem; background: #F8FBF8; font-size: 0.85rem;"
                               placeholder="Cari barang, deskripsi, atau pengupload..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    {{-- Category Filter --}}
                    <select name="category" form="filterForm" class="form-select rounded-4 px-3 py-1"
                            style="width: auto; font-size: 0.8rem; background: #F8FBF8; border: 1px solid #EDF2F0;">
                        <option value="">Semua Kategori</option>
                        <option value="buku" {{ request('category') == 'buku' ? 'selected' : '' }}>📚 Buku</option>
                        <option value="seragam" {{ request('category') == 'seragam' ? 'selected' : '' }}>👕 Seragam</option>
                        <option value="alat_praktikum" {{ request('category') == 'alat_praktikum' ? 'selected' : '' }}>🔬 Alat Praktikum</option>
                        <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                    </select>

                    {{-- Type Filter --}}
                    <select name="type" form="filterForm" class="form-select rounded-4 px-3 py-1"
                            style="width: auto; font-size: 0.8rem; background: #F8FBF8; border: 1px solid #EDF2F0;">
                        <option value="">Semua Tipe</option>
                        <option value="gift" {{ request('type') == 'gift' ? 'selected' : '' }}>🎁 Gratis</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>💰 Dijual</option>
                    </select>

                    <a href="{{ route('admin.moderation.index') }}" class="btn btn-sm rounded-4 px-3 py-1 d-flex align-items-center"
                       style="background: white; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.8rem;">
                        <i class="bi bi-arrow-repeat me-1"></i>Reset
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    @if($items->count() > 0)
        <div class="admin-card p-3 rounded-3" style="background: white; border: 1px solid #EDF2F0;">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.8rem;">
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
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $images = $item->images ?? [];
                                            $thumb = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                                        @endphp
                                        <div class="position-relative flex-shrink-0">
                                            <img src="{{ $thumb }}" alt="{{ $item->name }}" width="40" height="40" style="object-fit: cover; border-radius: 8px;">
                                            @if($item->status == 'pending')
                                                <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-circle p-1" style="width: 8px; height: 8px;">
                                                    <span class="visually-hidden">pending</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ Str::limit($item->name, 25) }}</p>
                                            <small class="text-secondary d-block" style="font-size: 0.65rem;">
                                                <i class="bi bi-tag me-1"></i>{{ $item->condition_label }}
                                            </small>
                                            @if($item->legacy_message)
                                                <small class="text-secondary d-block" style="font-size: 0.6rem;">
                                                    <i class="bi bi-quote me-1"></i>{{ Str::limit($item->legacy_message, 20) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->user->profile_photo)
                                            <img src="{{ Storage::url($item->user->profile_photo) }}" alt="" class="rounded-circle" width="28" height="28" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 28px; height: 28px; background: #dcfce7; color: #22c55e; font-size: 0.7rem;">
                                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-semibold d-block" style="font-size: 0.7rem;">{{ $item->user->name }}</span>
                                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $item->user->school ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-2 py-1" style="font-size: 0.6rem; background: #F0F5F0; color: #1A2A24;">
                                        {{ $item->category_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->type == 'gift')
                                        <span class="badge rounded-pill px-2 py-1" style="font-size: 0.6rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                                            <i class="bi bi-gift me-1" style="font-size: 0.5rem;"></i>Gratis
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2 py-1" style="font-size: 0.6rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                                            <i class="bi bi-tag me-1" style="font-size: 0.5rem;"></i>Dijual
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->type == 'sale')
                                        <span class="fw-semibold" style="color: #22c55e; font-size: 0.7rem;">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="fw-semibold text-success" style="font-size: 0.7rem;">Gratis</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-secondary d-block" style="font-size: 0.6rem;">
                                        <i class="bi bi-calendar me-1"></i>{{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                    <small class="text-secondary" style="font-size: 0.55rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $item->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge bg-warning rounded-pill px-2 py-1" style="font-size: 0.6rem;">
                                            <i class="bi bi-clock me-1" style="font-size: 0.5rem;"></i>Pending
                                        </span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.6rem;">
                                            <i class="bi bi-check-circle me-1" style="font-size: 0.5rem;"></i>Disetujui
                                        </span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.6rem;">
                                            <i class="bi bi-x-circle me-1" style="font-size: 0.5rem;"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.moderation.show', $item->id) }}"
                                       class="btn btn-sm rounded-4 px-2 py-0"
                                       style="font-size: 0.65rem; background: {{ $item->status == 'pending' ? '#22c55e' : 'white' }}; color: {{ $item->status == 'pending' ? 'white' : '#1A2A24' }}; border: 1px solid #EDF2F0;">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    @else
        {{-- EMPTY STATE MINI --}}
        <div class="admin-card p-4 rounded-3 text-center" style="background: white; border: 1px solid #EDF2F0;">
            <div class="empty-state">
                @if(request('status') == 'pending')
                    <i class="bi bi-check-circle text-success fs-2 mb-2"></i>
                    <h6 class="fw-semibold mb-2" style="color: #1A2A24;">Semua Barang Sudah Dimoderasi! 🎉</h6>
                    <p class="small text-secondary mb-3" style="font-size: 0.8rem;">Tidak ada barang yang menunggu persetujuan saat ini.</p>
                @elseif(request('status') == 'approved')
                    <i class="bi bi-box fs-2 text-secondary opacity-25 mb-2"></i>
                    <h6 class="fw-semibold mb-2" style="color: #1A2A24;">Belum Ada Barang Disetujui</h6>
                    <p class="small text-secondary mb-3" style="font-size: 0.8rem;">Barang yang disetujui akan muncul di sini.</p>
                @elseif(request('status') == 'rejected')
                    <i class="bi bi-box fs-2 text-secondary opacity-25 mb-2"></i>
                    <h6 class="fw-semibold mb-2" style="color: #1A2A24;">Belum Ada Barang Ditolak</h6>
                    <p class="small text-secondary mb-3" style="font-size: 0.8rem;">Barang yang ditolak akan muncul di sini.</p>
                @else
                    <i class="bi bi-box fs-2 text-secondary opacity-25 mb-2"></i>
                    <h6 class="fw-semibold mb-2" style="color: #1A2A24;">Tidak Ada Barang</h6>
                    <p class="small text-secondary mb-3" style="font-size: 0.8rem;">Belum ada barang yang diupload oleh pengguna.</p>
                @endif

                {{-- TOMBOL SUDAH DIKECILKAN --}}
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm rounded-4 px-3 py-1"
                   style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

{{-- Hidden Form for Filters --}}
<form id="filterForm" action="{{ route('admin.moderation.index') }}" method="GET">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
</form>
@endsection

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
        transition: all 0.2s;
        cursor: pointer;
        background: white;
        border: 1px solid #EDF2F0 !important;
    }

    .moderation-stat-card:hover {
        transform: translateY(-2px);
        border-color: #22c55e !important;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.05);
    }

    .moderation-stat-card.active {
        border: 2px solid #22c55e !important;
        background: #F8FBF8;
    }

    .admin-card {
        background: white;
        border: 1px solid #EDF2F0;
        transition: all 0.2s;
    }

    .admin-card:hover {
        border-color: rgba(34, 197, 94, 0.2);
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        color: #1A2A24;
        border-bottom: 1px solid #EDF2F0;
        padding: 0.5rem;
        font-size: 0.7rem;
    }

    .table td {
        padding: 0.5rem;
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
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .moderation-stat-card,
    [data-bs-theme="dark"] .admin-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] .moderation-stat-card.active {
        background: rgba(34, 197, 94, 0.1) !important;
        border-color: #22c55e !important;
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

    [data-bs-theme="dark"] .badge.bg-light {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
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
