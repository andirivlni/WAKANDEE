@extends('layouts.app')

@section('title', 'Barang Saya - WAKANDE')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Barang Saya</h1>
            <p class="text-secondary mb-0">Kelola semua barang yang telah kamu upload</p>
        </div>
        <a href="{{ route('items.create') }}" class="btn btn-primary btn-rounded px-4 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <i class="bi bi-plus-circle me-2"></i>Upload Barang
        </a>
    </div>

    <!-- Stats Tabs -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-tab p-3 rounded-4 text-center {{ !request('status') ? 'active' : '' }}" onclick="window.location='{{ route('items.index') }}'">
                <h4 class="fw-bold mb-1">{{ $items->total() }}</h4>
                <small class="text-secondary">Semua Barang</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-tab p-3 rounded-4 text-center {{ request('status') == 'pending' ? 'active' : '' }}" onclick="window.location='{{ route('items.index', ['status' => 'pending']) }}'">
                <h4 class="fw-bold mb-1" style="color: #ffc107;">{{ $items->where('status', 'pending')->count() }}</h4>
                <small class="text-secondary">Menunggu</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-tab p-3 rounded-4 text-center {{ request('status') == 'approved' ? 'active' : '' }}" onclick="window.location='{{ route('items.index', ['status' => 'approved']) }}'">
                <h4 class="fw-bold mb-1" style="color: #198754;">{{ $items->where('status', 'approved')->count() }}</h4>
                <small class="text-secondary">Disetujui</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-tab p-3 rounded-4 text-center {{ request('status') == 'rejected' ? 'active' : '' }}" onclick="window.location='{{ route('items.index', ['status' => 'rejected']) }}'">
                <h4 class="fw-bold mb-1" style="color: #dc3545;">{{ $items->where('status', 'rejected')->count() }}</h4>
                <small class="text-secondary">Ditolak</small>
            </div>
        </div>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="row g-4">
            @foreach($items as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="item-card h-100">
                        <!-- Image -->
                        <div class="position-relative" style="padding-top: 75%; overflow: hidden; border-radius: 16px 16px 0 0;">
                            @php
                                $images = json_decode($item->images, true) ?? [];
                                $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                            @endphp
                            <img src="{{ $firstImage }}" alt="{{ $item->name }}" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;">

                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>Menunggu
                                    </span>
                                @elseif($item->status == 'approved')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Disetujui
                                    </span>
                                @elseif($item->status == 'rejected')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>Ditolak
                                    </span>
                                @elseif($item->status == 'sold')
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        <i class="bi bi-tag me-1"></i>Terjual
                                    </span>
                                @endif
                            </div>

                            <!-- Type Badge -->
                            <div class="position-absolute top-0 start-0 m-3">
                                @if($item->type == 'gift')
                                    <span class="badge bg-success bg-opacity-90 rounded-pill px-3 py-2">
                                        <i class="bi bi-gift me-1"></i>Gratis
                                    </span>
                                @else
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="bi bi-tag me-1"></i>Dijual
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">{{ Str::limit($item->name, 40) }}</h5>
                                <span class="small text-secondary">
                                    <i class="bi bi-eye me-1"></i>{{ $item->views_count }}
                                </span>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    {{ $item->category_label }}
                                </span>
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    {{ $item->condition_label }}
                                </span>
                            </div>

                            @if($item->status == 'rejected' && $item->rejection_reason)
                                <div class="alert alert-danger rounded-3 p-3 mb-3" style="background: rgba(220,53,69,0.05); border: none;">
                                    <small class="d-block text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <strong>Alasan ditolak:</strong> {{ $item->rejection_reason }}
                                    </small>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($item->type == 'sale')
                                        <span class="fw-bold" style="color: #667eea;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="fw-bold text-success">Gratis</span>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                    @if($item->status == 'pending')
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $items->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="mb-4">
                <i class="bi bi-box-seam fs-1 text-secondary opacity-25"></i>
            </div>
            <h5 class="fw-bold mb-3">Belum Ada Barang</h5>
            <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">
                Kamu belum mengupload barang apapun. Yuk mulai berbagi dengan adik kelas!
            </p>
            <a href="{{ route('items.create') }}" class="btn btn-primary btn-rounded px-5 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <i class="bi bi-cloud-upload me-2"></i>Upload Barang Pertama
            </a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .stat-tab {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        cursor: pointer;
        transition: all 0.3s;
    }

    .stat-tab:hover {
        background: rgba(102,126,234,0.02);
        border-color: rgba(102,126,234,0.2);
    }

    .stat-tab.active {
        background: rgba(102,126,234,0.05);
        border: 1px solid rgba(102,126,234,0.3);
    }

    .item-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 24px rgba(102,126,234,0.08);
    }

    [data-bs-theme="dark"] .stat-tab,
    [data-bs-theme="dark"] .item-card {
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
