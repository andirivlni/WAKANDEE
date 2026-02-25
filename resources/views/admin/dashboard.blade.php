@extends('layouts.admin')

@section('title', 'Dashboard Admin - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1" style="color: #1A2A24;">Dashboard Admin</h5>
            <p class="small text-secondary mb-0" style="font-size: 0.8rem;">
                Selamat datang kembali, {{ Auth::user()->name }}! 👋
            </p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-light text-dark rounded-pill px-3 py-2" style="font-size: 0.8rem;">
                <i class="bi bi-calendar me-1"></i>{{ now()->format('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- STATS CARDS MINI --}}
    <div class="row g-3 mb-4">
        {{-- Total Users --}}
        <div class="col-xl-3 col-md-6">
            <div class="admin-stat-card p-3 rounded-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-people fs-5" style="color: #22c55e;"></i>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.7rem;">+{{ rand(5, 15) }} hari ini</span>
                </div>
                <h4 class="fw-bold mb-0" style="font-size: 1.5rem;">{{ number_format($stats['total_users'] ?? 0) }}</h4>
                <p class="text-secondary small mb-2" style="font-size: 0.75rem;">Total Pengguna</p>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 75%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small class="text-secondary" style="font-size: 0.65rem;">User: {{ number_format($stats['total_users'] ?? 0) }}</small>
                    <small class="text-secondary" style="font-size: 0.65rem;">Admin: {{ number_format($stats['total_admins'] ?? 0) }}</small>
                </div>
            </div>
        </div>

        {{-- Total Items --}}
        <div class="col-xl-3 col-md-6">
            <div class="admin-stat-card p-3 rounded-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px; background: #fef3c7;">
                        <i class="bi bi-box-seam fs-5" style="color: #ffc107;"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                        {{ number_format($stats['pending_items'] ?? 0) }} pending
                    </span>
                </div>
                <h4 class="fw-bold mb-0" style="font-size: 1.5rem;">{{ number_format($stats['total_items'] ?? 0) }}</h4>
                <p class="text-secondary small mb-2" style="font-size: 0.75rem;">Total Barang</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-success" style="font-size: 0.65rem;">
                        <i class="bi bi-check-circle me-1"></i>{{ number_format($stats['approved_items'] ?? 0) }} Disetujui
                    </small>
                    <small class="text-danger" style="font-size: 0.65rem;">
                        <i class="bi bi-x-circle me-1"></i>{{ number_format($stats['rejected_items'] ?? 0) }} Ditolak
                    </small>
                </div>
            </div>
        </div>

        {{-- Total Transactions --}}
        <div class="col-xl-3 col-md-6">
            <div class="admin-stat-card p-3 rounded-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-credit-card fs-5" style="color: #198754;"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                        +{{ rand(3, 10) }} hari ini
                    </span>
                </div>
                <h4 class="fw-bold mb-0" style="font-size: 1.5rem;">{{ number_format($stats['total_transactions'] ?? 0) }}</h4>
                <p class="text-secondary small mb-2" style="font-size: 0.75rem;">Total Transaksi</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-success" style="font-size: 0.65rem;">Selesai: {{ number_format($stats['completed_transactions'] ?? 0) }}</small>
                    <small class="text-warning" style="font-size: 0.65rem;">
                        Pending: {{ number_format(($stats['total_transactions'] ?? 0) - ($stats['completed_transactions'] ?? 0)) }}
                    </small>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-xl-3 col-md-6">
            <div class="admin-stat-card p-3 rounded-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon rounded-2 d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-cash-stack fs-5" style="color: #4ade80;"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">Pendapatan</span>
                </div>
                <h4 class="fw-bold mb-0" style="font-size: 1.5rem;">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
                <p class="text-secondary small mb-2" style="font-size: 0.75rem;">Total Pendapatan Admin</p>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 85%;"></div>
                </div>
                <small class="text-secondary d-block mt-1" style="font-size: 0.65rem;">+12% dari bulan lalu</small>
            </div>
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="row g-3 mb-4">
        {{-- Transaction Chart --}}
        <div class="col-xl-8">
            <div class="admin-card p-3 rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">
                        <i class="bi bi-graph-up me-2" style="color: #22c55e;"></i>
                        Grafik Transaksi 7 Hari Terakhir
                    </h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">Selesai</span>
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1" style="font-size: 0.65rem;">Pending</span>
                    </div>
                </div>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Category Chart --}}
        <div class="col-xl-4">
            <div class="admin-card p-3 rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">
                        <i class="bi bi-pie-chart me-2" style="color: #22c55e;"></i>
                        Kategori Barang
                    </h6>
                    <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                        Total: {{ $stats['total_items'] ?? 0 }}
                    </span>
                </div>
                <div class="chart-container" style="height: 180px;">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-3">
                    @php
                        $categories = \App\Models\Item::selectRaw('category, count(*) as total')
                            ->groupBy('category')
                            ->pluck('total', 'category')
                            ->toArray();
                    @endphp
                    <div class="vstack gap-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small" style="font-size: 0.7rem;">
                                <span class="badge bg-success rounded-circle p-1 me-1" style="width: 8px; height: 8px;">&nbsp;</span>
                                Buku
                            </span>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $categories['buku'] ?? 0 }} barang</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small" style="font-size: 0.7rem;">
                                <span class="badge bg-success rounded-circle p-1 me-1" style="width: 8px; height: 8px;">&nbsp;</span>
                                Seragam
                            </span>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $categories['seragam'] ?? 0 }} barang</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small" style="font-size: 0.7rem;">
                                <span class="badge bg-warning rounded-circle p-1 me-1" style="width: 8px; height: 8px;">&nbsp;</span>
                                Alat Praktikum
                            </span>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $categories['alat_praktikum'] ?? 0 }} barang</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small" style="font-size: 0.7rem;">
                                <span class="badge bg-info rounded-circle p-1 me-1" style="width: 8px; height: 8px;">&nbsp;</span>
                                Lainnya
                            </span>
                            <span class="fw-semibold" style="font-size: 0.7rem;">{{ $categories['lainnya'] ?? 0 }} barang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLES SECTION --}}
    <div class="row g-3">
        {{-- Moderasi Pending --}}
        <div class="col-xl-6">
            <div class="admin-card p-3 rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">Moderasi Pending</h6>
                    <a href="{{ route('admin.moderation.index') }}" class="btn btn-sm rounded-4 px-3 py-1"
                       style="background: white; border: 1px solid #EDF2F0; color: #22c55e; font-size: 0.7rem;">
                        Lihat Semua
                    </a>
                </div>

                @if (isset($recent_moderations) && $recent_moderations->count() > 0)
                    <div class="vstack gap-2">
                        @foreach ($recent_moderations->take(5) as $log)
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: #F8FBF8;">
                                @php
                                    $images = $log->item->images ?? [];
                                    $thumb = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                                @endphp
                                <img src="{{ $thumb }}" alt="{{ $log->item->name }}" width="32" height="32" style="object-fit: cover; border-radius: 6px;">
                                <div class="flex-grow-1">
                                    <p class="fw-semibold mb-0" style="font-size: 0.75rem;">{{ Str::limit($log->item->name, 30) }}</p>
                                    <small class="text-secondary" style="font-size: 0.6rem;">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge rounded-pill px-2 py-1"
                                      style="background: {{ $log->action == 'approved' ? 'rgba(25,135,84,0.1)' : 'rgba(220,53,69,0.1)' }};
                                             color: {{ $log->action == 'approved' ? '#198754' : '#dc3545' }}; font-size: 0.6rem;">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-secondary mb-0" style="font-size: 0.8rem;">Tidak ada barang pending</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="col-xl-6">
            <div class="admin-card p-3 rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">Transaksi Terbaru</h6>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm rounded-4 px-3 py-1"
                       style="background: white; border: 1px solid #EDF2F0; color: #22c55e; font-size: 0.7rem;">
                        Lihat Semua
                    </a>
                </div>

                @if (isset($recent_transactions) && $recent_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.75rem;">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recent_transactions->take(5) as $trx)
                                    <tr>
                                        <td class="fw-mono">{{ Str::limit($trx->transaction_code, 8, '') }}</td>
                                        <td class="fw-bold">Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @php $status = $trx->payment_status_label; @endphp
                                            <span class="badge rounded-pill px-2 py-1"
                                                  style="background: rgba({{ $status['color'] == 'success' ? '25,135,84' : '255,193,7' }}, 0.1);
                                                         color: {{ $status['color'] == 'success' ? '#198754' : '#ffc107' }}; font-size: 0.6rem;">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-secondary mb-0" style="font-size: 0.8rem;">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Config Common
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Transaction Chart
    const ctx = document.getElementById('transactionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(
                $transactions_chart->pluck('date')->map(function ($date) {
                    return \Carbon\Carbon::parse($date)->format('d M');
                }),
            ) !!},
            datasets: [{
                label: 'Selesai',
                data: {!! json_encode($transactions_chart->pluck('completed')) !!},
                borderColor: '#198754',
                backgroundColor: '#f0fdf4',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 2
            }, {
                label: 'Pending',
                data: {!! json_encode(
                    $transactions_chart->pluck('total')->map(function ($total, $index) use ($transactions_chart) {
                        return intval($total) - intval($transactions_chart[$index]->completed);
                    }),
                ) !!},
                borderColor: '#ffc107',
                borderWidth: 1.5,
                borderDash: [4, 4],
                tension: 0.4,
                pointRadius: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { titleFont: { size: 10 }, bodyFont: { size: 9 } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)' },
                    ticks: { font: { size: 8 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 8 } }
                }
            }
        }
    });

    // Category Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: ['Buku', 'Seragam', 'Alat', 'Lainnya'],
            datasets: [{
                data: [
                    {{ $categories['buku'] ?? 0 }},
                    {{ $categories['seragam'] ?? 0 }},
                    {{ $categories['alat_praktikum'] ?? 0 }},
                    {{ $categories['lainnya'] ?? 0 }}
                ],
                backgroundColor: ['#22c55e', '#198754', '#ffc107', '#0dcaf0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: { titleFont: { size: 10 }, bodyFont: { size: 9 } }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
    .admin-stat-card {
        background: white;
        border: 1px solid #EDF2F0;
        border-radius: 12px;
        transition: all 0.2s;
    }

    .admin-stat-card:hover {
        border-color: rgba(34, 197, 94, 0.2);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.05);
    }

    .admin-card {
        background: white;
        border: 1px solid #EDF2F0;
        border-radius: 12px;
        height: 100%;
        transition: all 0.2s;
    }

    .admin-card:hover {
        border-color: rgba(34, 197, 94, 0.2);
    }

    .chart-container {
        position: relative;
        width: 100%;
    }

    .progress {
        background-color: #F0F5F0;
        border-radius: 100px;
    }

    .table {
        min-width: 350px;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        color: #1A2A24;
        border-bottom: 1px solid #EDF2F0;
        padding: 0.5rem;
    }

    .table td {
        padding: 0.5rem;
        border-bottom: 1px solid #F0F5F0;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .admin-stat-card,
    [data-bs-theme="dark"] .admin-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] .progress {
        background-color: rgba(255, 255, 255, 0.1);
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
</style>
@endpush
