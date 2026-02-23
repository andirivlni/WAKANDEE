@extends('layouts.admin')

@section('title', 'Dashboard Admin - WAKANDE')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Dashboard Admin</h1>
                <p class="text-secondary mb-0">Selamat datang kembali, {{ Auth::user()->name }}! 👋</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-dark rounded-pill px-4 py-3">
                    <i class="bi bi-calendar me-2"></i>{{ now()->format('l, d F Y') }}
                </span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card p-4 rounded-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px; background: #dcfce7;">
                            <i class="bi bi-people fs-3" style="color: #22c55e;"></i>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">+{{ rand(5, 15) }} hari ini</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                    <p class="text-secondary small mb-2">Total Pengguna</p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 75%;"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-secondary">User: {{ number_format($stats['total_users'] ?? 0) }}</small>
                        <small class="text-secondary">Admin: {{ number_format($stats['total_admins'] ?? 0) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card p-4 rounded-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px; background: #fef3c7;">
                            <i class="bi bi-box-seam fs-3" style="color: #ffc107;"></i>
                        </div>
                        <span
                            class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">{{ number_format($stats['pending_items'] ?? 0) }}
                            pending</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['total_items'] ?? 0) }}</h3>
                    <p class="text-secondary small mb-2">Total Barang</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">
                            <i class="bi bi-check-circle me-1"></i>{{ number_format($stats['approved_items'] ?? 0) }}
                            Disetujui
                        </small>
                        <small class="text-danger">
                            <i class="bi bi-x-circle me-1"></i>{{ number_format($stats['rejected_items'] ?? 0) }} Ditolak
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card p-4 rounded-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px; background: #dcfce7;">
                            <i class="bi bi-credit-card fs-3" style="color: #198754;"></i>
                        </div>
                        <span
                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">+{{ rand(3, 10) }}
                            hari ini</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['total_transactions'] ?? 0) }}</h3>
                    <p class="text-secondary small mb-2">Total Transaksi</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">Selesai:
                            {{ number_format($stats['completed_transactions'] ?? 0) }}</small>
                        <small class="text-warning">Pending:
                            {{ number_format(($stats['total_transactions'] ?? 0) - ($stats['completed_transactions'] ?? 0)) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card p-4 rounded-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px; background: #dcfce7;">
                            <i class="bi bi-cash-stack fs-3" style="color: #4ade80;"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Pendapatan</span>
                    </div>
                    <h3 class="fw-bold mb-1">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-secondary small mb-2">Total Pendapatan Admin</p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 85%;"></div>
                    </div>
                    <small class="text-secondary d-block mt-2">+12% dari bulan lalu</small>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="admin-card p-4 rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-graph-up me-2" style="color: #22c55e;"></i>
                            Grafik Transaksi 7 Hari Terakhir
                        </h5>
                        <div class="d-flex gap-2">
                            <span
                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">Selesai</span>
                            <span
                                class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 small">Pending</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="admin-card p-4 rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-pie-chart me-2" style="color: #22c55e;"></i>
                            Kategori Barang
                        </h5>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Total:
                            {{ $stats['total_items'] ?? 0 }}</span>
                    </div>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-4">
                        @php
                            $categories = \App\Models\Item::selectRaw('category, count(*) as total')
                                ->groupBy('category')
                                ->pluck('total', 'category')
                                ->toArray();
                        @endphp
                        <div class="vstack gap-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small"><span class="badge bg-success rounded-circle p-1 me-2">&nbsp;</span>
                                    Buku</span>
                                <span class="fw-semibold small">{{ $categories['buku'] ?? 0 }} barang</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small"><span class="badge bg-success rounded-circle p-1 me-2">&nbsp;</span>
                                    Seragam</span>
                                <span class="fw-semibold small">{{ $categories['seragam'] ?? 0 }} barang</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small"><span class="badge bg-warning rounded-circle p-1 me-2">&nbsp;</span>
                                    Alat Praktikum</span>
                                <span class="fw-semibold small">{{ $categories['alat_praktikum'] ?? 0 }} barang</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small"><span class="badge bg-info rounded-circle p-1 me-2">&nbsp;</span>
                                    Lainnya</span>
                                <span class="fw-semibold small">{{ $categories['lainnya'] ?? 0 }} barang</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6">
                <div class="admin-card p-4 rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Moderasi Pending</h5>
                        <a href="{{ route('admin.moderation.index') }}"
                            class="btn btn-sm btn-outline-success rounded-pill px-3">Lihat Semua</a>
                    </div>

                    @if (isset($recent_moderations) && $recent_moderations->count() > 0)
                        <div class="vstack gap-3">
                            @foreach ($recent_moderations->take(5) as $log)
                                <div class="d-flex align-items-center gap-3 p-2 rounded-3"
                                    style="background: #f8fafc;">
                                    @php
                                        $images = $log->item->images ?? [];
                                        $thumb = !empty($images)
                                            ? Storage::url($images[0])
                                            : asset('images/default-item.png');
                                    @endphp
                                    <img src="{{ $thumb }}" alt="{{ $log->item->name }}" width="40"
                                        height="40" style="object-fit: cover; border-radius: 8px;">
                                    <div class="grow">
                                        <h6 class="fw-semibold mb-0 small">{{ Str::limit($log->item->name, 35) }}</h6>
                                        <small class="text-secondary"
                                            style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span
                                        class="badge bg-{{ $log->action == 'approved' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $log->action == 'approved' ? 'success' : 'danger' }} rounded-pill px-2 py-1 small">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-secondary mb-0">Tidak ada barang pending</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-6">
                <div class="admin-card p-4 rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Transaksi Terbaru</h5>
                        <a href="{{ route('admin.transactions.index') }}"
                            class="btn btn-sm btn-outline-success rounded-pill px-3">Lihat Semua</a>
                    </div>

                    @if (isset($recent_transactions) && $recent_transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="small text-secondary">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_transactions->take(5) as $trx)
                                        <tr>
                                            <td class="small fw-mono">{{ Str::limit($trx->transaction_code, 8) }}</td>
                                            <td class="small fw-bold">
                                                Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @php $status = $trx->payment_status_label; @endphp
                                                <span
                                                    class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} rounded-pill px-2 py-1"
                                                    style="font-size: 0.7rem;">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-secondary mb-0">Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Config Common
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

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
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Pending',
                        data: {!! json_encode(
                            $transactions_chart->pluck('total')->map(function ($total, $index) use ($transactions_chart) {
                                return intval($total) - intval($transactions_chart[$index]->completed);
                            }),
                        ) !!},
                        borderColor: '#ffc107',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.03)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
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
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .admin-stat-card {
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                transition: all 0.3s;
            }

            .admin-card {
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                height: 100%;
            }

            /* KUNCI PERBAIKAN GRAFIK */
            .chart-container {
                position: relative;
                height: 300px;
                /* Tinggi maksimal grafik */
                width: 100%;
            }

            .progress {
                background-color: #f3f4f6;
                border-radius: 100px;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table {
                min-width: 400px;
            }

            [data-bs-theme="dark"] .admin-card,
            [data-bs-theme="dark"] .admin-stat-card {
                background: #1f2937;
                border-color: #374151;
            }
        </style>
    @endpush
@endsection
