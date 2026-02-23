@extends('layouts.admin')

@section('title', 'Detail Moderasi - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.moderation.index', ['status' => $item->status]) }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Barang</h1>
                <p class="text-secondary mb-0">Moderasi ID: #{{ $item->id }}</p>
            </div>
        </div>

        @if($item->status == 'pending')
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-lg rounded-pill px-5 py-3" onclick="approveItem()">
                    <i class="bi bi-check-circle me-2"></i>Setujui
                </button>
                <button type="button" class="btn btn-danger btn-lg rounded-pill px-5 py-3" onclick="rejectItem()">
                    <i class="bi bi-x-circle me-2"></i>Tolak
                </button>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Left Column - Item Details -->
        <div class="col-xl-8">
            <!-- Item Images -->
            <div class="admin-card p-4 rounded-3 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-images me-2" style="color: #22c55e;"></i>
                    Foto Barang
                </h5>

                @php
                    // Fix: Ensure images is an array
                    $images = $item->images ?? [];
                    if (is_string($images)) {
                        $images = is_array($images) ? $images : (is_string($images) ? json_decode($images, true) : []) ?? [];
                    }
                    $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                @endphp

                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="main-image-container rounded-3 overflow-hidden" style="background: #f8f9fa; aspect-ratio: 1;">
                            <img src="{{ $firstImage }}" alt="{{ $item->name }}" id="mainImage" class="w-100 h-100" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-2">
                            @if(is_array($images) && count($images) > 0)
                                @foreach($images as $index => $image)
                                    @if(!empty($image))
                                    <div class="col-6 col-lg-12">
                                        <div class="thumbnail-container rounded-3 overflow-hidden {{ $index == 0 ? 'active' : '' }}"
                                             onclick="changeMainImage('{{ Storage::url($image) }}', this)"
                                             style="aspect-ratio: 1; cursor: pointer;">
                                            <img src="{{ Storage::url($image) }}" class="w-100 h-100" style="object-fit: cover;">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-12 text-center py-4">
                                    <i class="bi bi-image text-secondary fs-1"></i>
                                    <p class="text-secondary mt-2">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Information -->
            <div class="admin-card p-4 rounded-3 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2" style="color: #22c55e;"></i>
                    Informasi Barang
                </h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-group">
                            <small class="text-secondary d-block mb-1">Nama Barang</small>
                            <p class="fw-semibold mb-0">{{ $item->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group">
                            <small class="text-secondary d-block mb-1">Kategori</small>
                            <p class="fw-semibold mb-0">{{ $item->category_label }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group">
                            <small class="text-secondary d-block mb-1">Kondisi</small>
                            <p class="fw-semibold mb-0">{{ $item->condition_label }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <small class="text-secondary d-block mb-1">Tipe</small>
                            <p class="fw-semibold mb-0">
                                @if($item->type == 'gift')
                                    <span class="badge bg-success">Gratis</span>
                                @else
                                    <span class="badge bg-success">Dijual - Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <small class="text-secondary d-block mb-1">Tanggal Upload</small>
                            <p class="fw-semibold mb-0">{{ $item->created_at->format('d F Y H:i') }}</p>
                            <small class="text-secondary">{{ $item->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-group">
                            <small class="text-secondary d-block mb-1">Deskripsi Barang</small>
                            <div class="p-3 rounded-3" style="background: #f8fafc;">
                                <p class="mb-0" style="line-height: 1.6;">{{ nl2br(e($item->description)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legacy Message -->
            <div class="legacy-card p-4 rounded-3 mb-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-quote fs-1" style="color: #22c55e; opacity: 0.3;"></i>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2">
                            <i class="bi bi-chat-quote me-1"></i>Legacy Message
                        </span>
                        <p class="fw-light fst-italic mb-3" style="font-size: 1.2rem;">
                            "{{ $item->legacy_message }}"
                        </p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle" style="width: 40px; height: 40px; background: #22c55e;">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="fw-semibold mb-1">{{ $item->user->name }}</p>
                                <small class="text-secondary">{{ $item->user->school ?? 'Sekolah' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - User & Moderation Info -->
        <div class="col-xl-4">
            <!-- Uploader Info -->
            <div class="admin-card p-4 rounded-3 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-person me-2" style="color: #22c55e;"></i>
                    Informasi Uploader
                </h5>

                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($item->user->profile_photo)
                        <img src="{{ Storage::url($item->user->profile_photo) }}" alt="{{ $item->user->name }}" class="rounded-circle" width="64" height="64" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle" style="width: 64px; height: 64px; font-size: 1.5rem; background: #22c55e;">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h6 class="fw-bold mb-1">{{ $item->user->name }}</h6>
                        <p class="text-secondary small mb-1">{{ $item->user->email }}</p>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <i class="bi bi-building me-1"></i>{{ $item->user->school ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="vstack gap-2">
                    <div class="d-flex justify-content-between">
                        <small class="text-secondary">Total Barang</small>
                        <span class="fw-semibold">{{ $item->user->items()->count() }} barang</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-secondary">Bergabung</small>
                        <span class="fw-semibold">{{ $item->user->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-secondary">Status</small>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                </div>

                <hr class="opacity-25 my-3">

                <a href="{{ route('admin.users.show', $item->user_id) }}" class="btn btn-outline-success rounded-pill w-100 py-2">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Lihat Profil Uploader
                </a>
            </div>

            <!-- Moderation History -->
            <div class="admin-card p-4 rounded-3 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-clock-history me-2" style="color: #22c55e;"></i>
                    Riwayat Moderasi
                </h5>

                @if($moderation_history->count() > 0)
                    <div class="vstack gap-3">
                        @foreach($moderation_history as $log)
                            <div class="d-flex gap-3">
                                <div class="timeline-icon">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; background: rgba({{ $log->action == 'approved' ? '25,135,84' : '220,53,69' }}, 0.1);">
                                        <i class="bi bi-{{ $log->action == 'approved' ? 'check-circle' : 'x-circle' }} text-{{ $log->action == 'approved' ? 'success' : 'danger' }}"></i>
                                    </div>
                                </div>
                                <div class="grow">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-semibold mb-1">{{ ucfirst($log->action) }} oleh {{ $log->admin->name }}</h6>
                                        <small class="text-secondary">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($log->reason)
                                        <p class="small text-secondary mb-0 mt-2 p-2 rounded-3" style="background: transparent;">
                                            <i class="bi bi-chat me-1"></i>{{ $log->reason }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clock text-secondary fs-1 mb-3"></i>
                        <p class="text-secondary mb-0">Belum ada riwayat moderasi</p>
                    </div>
                @endif
            </div>

            <!-- Moderation Guidelines -->
            <div class="guidelines-card p-4 rounded-3">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-shield-check me-2" style="color: #22c55e;"></i>
                    Panduan Moderasi
                </h6>
                <ul class="small text-secondary mb-0" style="padding-left: 1rem;">
                    <li class="mb-2">Pastikan foto barang jelas dan sesuai</li>
                    <li class="mb-2">Deskripsi barang harus lengkap dan jujur</li>
                    <li class="mb-2">Harga barang wajar (jika dijual)</li>
                    <li class="mb-2">Legacy Message harus positif dan inspiratif</li>
                    <li class="mb-2">Tidak mengandung SARA atau konten negatif</li>
                    <li>Tidak ada duplikasi barang yang sama</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Setujui Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.moderation.approve', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <p class="text-secondary mb-3">Apakah kamu yakin ingin menyetujui barang ini?</p>
                    <div class="alert alert-success rounded-3 border-0" style="background: #f0fdf4;">
                        <div class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div>
                                <small class="text-success d-block fw-semibold mb-1">Setelah disetujui:</small>
                                <small class="text-secondary">Barang akan langsung tampil di katalog dan dapat diakses oleh semua pengguna.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea class="form-control rounded-3" id="note" name="note" rows="2" placeholder="Tambahkan catatan untuk pengupload..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-check-circle me-2"></i>Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Tolak Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.moderation.reject', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <div class="alert alert-danger rounded-3 border-0 mb-4" style="background: #fef2f2;">
                        <div class="d-flex gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-danger mt-1"></i>
                            <div>
                                <small class="text-danger d-block fw-semibold mb-1">Perhatikan:</small>
                                <small class="text-secondary">Alasan penolakan akan dikirim ke pengupload. Berikan alasan yang jelas dan konstruktif.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason_category" class="form-label fw-semibold">Kategori Alasan <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="reason_category" name="reason_category" required>
                            <option value="">Pilih kategori alasan</option>
                            <option value="foto_kurang_jelas">Foto kurang jelas/tidak sesuai</option>
                            <option value="deskripsi_kurang_lengkap">Deskripsi kurang lengkap</option>
                            <option value="harga_tidak_wajar">Harga tidak wajar</option>
                            <option value="kondisi_tidak_sesuai">Kondisi barang tidak sesuai</option>
                            <option value="kategori_salah">Kategori barang salah</option>
                            <option value="gambar_tidak_relevan">Gambar tidak relevan</option>
                            <option value="duplikat">Barang duplikat</option>
                            <option value="melanggar_aturan">Melanggar aturan platform</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3" id="reason" name="reason" rows="4"
                                  placeholder="Jelaskan alasan penolakan secara detail agar pengupload bisa memperbaiki..." required></textarea>
                        <small class="text-secondary d-block mt-2">
                            <i class="bi bi-info-circle"></i> Minimal 10 karakter
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-x-circle me-2"></i>Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Change main image
    window.changeMainImage = function(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail-container').forEach(el => {
            el.classList.remove('active');
        });
        element.classList.add('active');
    };

    // Approve item
    window.approveItem = function() {
        const modalEl = document.getElementById('approveModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            alert('Modal tidak ditemukan!');
        }
    };

    // Reject item
    window.rejectItem = function() {
        const modalEl = document.getElementById('rejectModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            alert('Modal tidak ditemukan!');
        }
    };

    // Character counter
    document.addEventListener('DOMContentLoaded', function() {
        const reasonField = document.getElementById('reason');
        if (reasonField) {
            reasonField.addEventListener('input', function() {
                if (this.value.length < 10) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Auto-fill reason based on category
        const categorySelect = document.getElementById('reason_category');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const reasons = {
                    'foto_kurang_jelas': 'Foto barang kurang jelas. Silakan upload ulang dengan foto yang lebih jelas.',
                    'deskripsi_kurang_lengkap': 'Deskripsi terlalu singkat. Lengkapi informasi seperti ukuran, tahun, kondisi.',
                    'harga_tidak_wajar': 'Harga tidak sesuai dengan kondisi pasar.',
                    'kondisi_tidak_sesuai': 'Kondisi barang tidak sesuai dengan deskripsi.',
                    'kategori_salah': 'Kategori barang tidak tepat.',
                    'gambar_tidak_relevan': 'Gambar tidak relevan dengan barang.',
                    'duplikat': 'Barang ini sudah pernah diupload.',
                    'melanggar_aturan': 'Melanggar ketentuan platform.',
                    'lainnya': 'Barang ditolak karena alasan lain.'
                };
                const reasonField = document.getElementById('reason');
                if (this.value && reasons[this.value]) {
                    reasonField.value = reasons[this.value];
                    reasonField.dispatchEvent(new Event('input'));
                } else {
                    reasonField.value = '';
                }
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .main-image-container {
        background: white;
        border: 1px solid transparent;
    }

    .thumbnail-container {
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .thumbnail-container.active {
        border-color: #22c55e;
    }

    .legacy-card {
        background: #f0fdf4;
        border-left: 6px solid #22c55e;
    }

    .guidelines-card {
        background: #f8fafc;
        border: 1px solid #dcfce7;
    }

    .info-group {
        padding: 1rem;
        background: rgba(0,0,0,0.01);
        border-radius: 12px;
    }

    [data-bs-theme="dark"] .main-image-container {
        background: #1a1a2c;
    }

    [data-bs-theme="dark"] .info-group {
        background: transparent;
    }

    [data-bs-theme="dark"] .guidelines-card {
        background: transparent;
    }
</style>
@endpush
@endsection
