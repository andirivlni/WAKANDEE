@extends('layouts.admin')

@section('title', 'Detail Moderasi - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    {{-- HEADER MINI --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.moderation.index', ['status' => $item->status]) }}"
               class="btn btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center"
               style="width: 32px; height: 32px; background: #F8FBF8; border: 1px solid #EDF2F0;">
                <i class="bi bi-arrow-left" style="font-size: 0.9rem;"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-0" style="color: #1A2A24;">Detail Barang</h5>
                <p class="small text-secondary mb-0" style="font-size: 0.7rem;">Moderasi ID: #{{ $item->id }}</p>
            </div>
        </div>

        @if($item->status == 'pending')
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm rounded-4 px-3 py-1"
                        style="background: #22c55e; color: white; border: none; font-size: 0.8rem;"
                        onclick="approveItem()">
                    <i class="bi bi-check-circle me-1"></i>Setujui
                </button>
                <button type="button" class="btn btn-sm rounded-4 px-3 py-1"
                        style="background: #dc3545; color: white; border: none; font-size: 0.8rem;"
                        onclick="rejectItem()">
                    <i class="bi bi-x-circle me-1"></i>Tolak
                </button>
            </div>
        @endif
    </div>

    <div class="row g-3">
        {{-- LEFT COLUMN - ITEM DETAILS --}}
        <div class="col-xl-8">
            {{-- ITEM IMAGES --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <h6 class="fw-semibold mb-2" style="font-size: 0.9rem;">
                    <i class="bi bi-images me-1" style="color: #22c55e;"></i>Foto Barang
                </h6>

                @php
                    $images = $item->images ?? [];
                    if (is_string($images)) {
                        $images = json_decode($images, true) ?? [];
                    }
                    $firstImage = !empty($images) ? Storage::url($images[0]) : asset('images/default-item.png');
                @endphp

                <div class="row g-2">
                    <div class="col-lg-8">
                        <div class="main-image-container rounded-3 overflow-hidden border" style="background: #F8FBF8; aspect-ratio: 1;">
                            <img src="{{ $firstImage }}" alt="{{ $item->name }}" id="mainImage" class="w-100 h-100" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-2">
                            @if(is_array($images) && count($images) > 0)
                                @foreach($images as $index => $image)
                                    @if(!empty($image))
                                    <div class="col-6 col-lg-12">
                                        <div class="thumbnail-container rounded-3 overflow-hidden border {{ $index == 0 ? 'active' : '' }}"
                                             onclick="changeMainImage('{{ Storage::url($image) }}', this)"
                                             style="aspect-ratio: 1; cursor: pointer;">
                                            <img src="{{ Storage::url($image) }}" class="w-100 h-100" style="object-fit: cover;">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-12 text-center py-3">
                                    <i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>
                                    <p class="small text-secondary mt-1">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEM INFORMATION --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <h6 class="fw-semibold mb-2" style="font-size: 0.9rem;">
                    <i class="bi bi-info-circle me-1" style="color: #22c55e;"></i>Informasi Barang
                </h6>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="info-group p-2 rounded-2" style="background: #F8FBF8;">
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Nama Barang</small>
                            <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $item->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group p-2 rounded-2" style="background: #F8FBF8;">
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Kategori</small>
                            <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $item->category_label }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group p-2 rounded-2" style="background: #F8FBF8;">
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Kondisi</small>
                            <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $item->condition_label }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group p-2 rounded-2" style="background: #F8FBF8;">
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Tipe</small>
                            <p class="fw-semibold mb-0">
                                @if($item->type == 'gift')
                                    <span class="badge rounded-pill px-2 py-0" style="font-size: 0.65rem; background: rgba(34,197,94,0.1); color: #22c55e;">Gratis</span>
                                @else
                                    <span class="badge rounded-pill px-2 py-0" style="font-size: 0.65rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                                        Dijual - Rp{{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group p-2 rounded-2" style="background: #F8FBF8;">
                            <small class="text-secondary d-block" style="font-size: 0.6rem;">Tanggal Upload</small>
                            <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $item->created_at->format('d M Y H:i') }}</p>
                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $item->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-group p-2 rounded-2" style="background: #F8FBF8;">
                            <small class="text-secondary d-block mb-1" style="font-size: 0.6rem;">Deskripsi Barang</small>
                            <div class="p-2 rounded-2" style="background: white;">
                                <p class="small mb-0" style="font-size: 0.75rem; line-height: 1.5;">{{ nl2br(e($item->description)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LEGACY MESSAGE --}}
            <div class="legacy-card p-3 rounded-3 mb-3" style="background: #F8FBF8; border-left: 3px solid #22c55e;">
                <div class="d-flex gap-2">
                    <i class="bi bi-quote" style="color: #22c55e; font-size: 1.5rem; opacity: 0.5;"></i>
                    <div>
                        <span class="badge rounded-pill px-2 py-0 mb-1" style="font-size: 0.6rem; background: rgba(34,197,94,0.1); color: #22c55e;">
                            <i class="bi bi-chat-quote me-1"></i>Legacy Message
                        </span>
                        <p class="fw-light fst-italic mb-2" style="font-size: 0.9rem;">"{{ $item->legacy_message }}"</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 28px; height: 28px; background: #22c55e; color: white; font-size: 0.7rem;">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="fw-semibold mb-0" style="font-size: 0.7rem;">{{ $item->user->name }}</p>
                                <small class="text-secondary" style="font-size: 0.6rem;">{{ $item->user->school ?? 'Sekolah' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN - USER & MODERATION INFO --}}
        <div class="col-xl-4">
            {{-- UPLOADER INFO --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <h6 class="fw-semibold mb-2" style="font-size: 0.9rem;">
                    <i class="bi bi-person me-1" style="color: #22c55e;"></i>Informasi Uploader
                </h6>

                <div class="d-flex align-items-center gap-2 mb-2">
                    @if($item->user->profile_photo)
                        <img src="{{ Storage::url($item->user->profile_photo) }}" alt="" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    @else
                        <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background: #22c55e; color: white; font-size: 1rem;">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $item->user->name }}</p>
                        <small class="text-secondary" style="font-size: 0.6rem;">{{ $item->user->email }}</small>
                    </div>
                </div>

                <div class="vstack gap-1 mb-2">
                    <div class="d-flex justify-content-between">
                        <small class="text-secondary" style="font-size: 0.65rem;">Total Barang</small>
                        <span class="fw-semibold" style="font-size: 0.7rem;">{{ $item->user->items()->count() }} barang</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-secondary" style="font-size: 0.65rem;">Bergabung</small>
                        <span class="fw-semibold" style="font-size: 0.7rem;">{{ $item->user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-secondary" style="font-size: 0.65rem;">Status</small>
                        <span class="badge rounded-pill px-2 py-0" style="font-size: 0.6rem; background: rgba(34,197,94,0.1); color: #22c55e;">Aktif</span>
                    </div>
                </div>

                <hr class="opacity-25 my-2">

                <a href="{{ route('admin.users.show', $item->user_id) }}" class="btn btn-sm rounded-4 w-100 py-1"
                   style="background: #F8FBF8; border: 1px solid #EDF2F0; color: #22c55e; font-size: 0.7rem;">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Profil Uploader
                </a>
            </div>

            {{-- MODERATION HISTORY --}}
            <div class="admin-card p-3 rounded-3 mb-3" style="background: white; border: 1px solid #EDF2F0;">
                <h6 class="fw-semibold mb-2" style="font-size: 0.9rem;">
                    <i class="bi bi-clock-history me-1" style="color: #22c55e;"></i>Riwayat Moderasi
                </h6>

                @if($moderation_history->count() > 0)
                    <div class="vstack gap-2">
                        @foreach($moderation_history as $log)
                            <div class="d-flex gap-2">
                                <div class="timeline-icon flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 28px; height: 28px; background: rgba({{ $log->action == 'approved' ? '25,135,84' : '220,53,69' }}, 0.1);">
                                        <i class="bi bi-{{ $log->action == 'approved' ? 'check-circle' : 'x-circle' }}"
                                           style="color: {{ $log->action == 'approved' ? '#198754' : '#dc3545' }}; font-size: 0.8rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-semibold mb-0" style="font-size: 0.7rem;">{{ ucfirst($log->action) }} oleh {{ $log->admin->name }}</p>
                                        <small class="text-secondary" style="font-size: 0.55rem;">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($log->reason)
                                        <p class="small text-secondary mb-0 mt-1 p-1 rounded-2" style="font-size: 0.6rem; background: #F8FBF8;">
                                            <i class="bi bi-chat me-1"></i>{{ $log->reason }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-2">
                        <i class="bi bi-clock text-secondary" style="font-size: 1.5rem;"></i>
                        <p class="small text-secondary mt-1 mb-0">Belum ada riwayat moderasi</p>
                    </div>
                @endif
            </div>

            {{-- MODERATION GUIDELINES --}}
            <div class="p-2 rounded-3" style="background: #F8FBF8;">
                <h6 class="fw-semibold small mb-1" style="color: #1A2A24;">
                    <i class="bi bi-shield-check me-1" style="color: #22c55e; font-size: 0.7rem;"></i>Panduan Moderasi
                </h6>
                <ul class="small text-secondary mb-0" style="padding-left: 1rem; font-size: 0.6rem;">
                    <li class="mb-1">Pastikan foto barang jelas</li>
                    <li class="mb-1">Deskripsi harus lengkap</li>
                    <li class="mb-1">Harga barang wajar</li>
                    <li class="mb-1">Legacy Message positif</li>
                    <li class="mb-1">Tidak mengandung SARA</li>
                    <li>Tidak ada duplikasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- APPROVE MODAL MINI --}}
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pt-3 px-3">
                <h6 class="modal-title fw-semibold">Setujui Barang</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
            </div>
            <form action="{{ route('admin.moderation.approve', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body px-3 pb-3">
                    <p class="small text-secondary mb-2">Apakah kamu yakin ingin menyetujui barang ini?</p>
                    <div class="alert p-2 rounded-3 mb-2" style="background: rgba(25,135,84,0.05);">
                        <div class="d-flex gap-1">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 0.8rem;"></i>
                            <div>
                                <small class="text-success d-block fw-semibold" style="font-size: 0.65rem;">Setelah disetujui:</small>
                                <small class="text-secondary" style="font-size: 0.6rem;">Barang akan langsung tampil di katalog.</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Catatan (Opsional)</label>
                        <textarea class="form-control rounded-3" name="note" rows="1" style="font-size: 0.7rem;" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-3 pb-3">
                    <button type="button" class="btn btn-sm rounded-4 px-3 py-1" data-bs-dismiss="modal" style="background: white; border: 1px solid #EDF2F0; color: #1A2A24;">Batal</button>
                    <button type="submit" class="btn btn-sm rounded-4 px-3 py-1" style="background: #22c55e; color: white; border: none;">
                        <i class="bi bi-check-circle me-1"></i>Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- REJECT MODAL MINI --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pt-3 px-3">
                <h6 class="modal-title fw-semibold">Tolak Barang</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
            </div>
            <form action="{{ route('admin.moderation.reject', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body px-3 pb-3">
                    <div class="alert p-2 rounded-3 mb-2" style="background: rgba(220,53,69,0.05);">
                        <div class="d-flex gap-1">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 0.8rem;"></i>
                            <div>
                                <small class="text-danger d-block fw-semibold" style="font-size: 0.65rem;">Perhatikan:</small>
                                <small class="text-secondary" style="font-size: 0.6rem;">Alasan penolakan akan dikirim ke pengupload.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Kategori Alasan <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="reason_category" name="reason_category" required style="font-size: 0.7rem;">
                            <option value="">Pilih kategori</option>
                            <option value="foto_kurang_jelas">Foto kurang jelas</option>
                            <option value="deskripsi_kurang_lengkap">Deskripsi kurang lengkap</option>
                            <option value="harga_tidak_wajar">Harga tidak wajar</option>
                            <option value="kondisi_tidak_sesuai">Kondisi tidak sesuai</option>
                            <option value="kategori_salah">Kategori salah</option>
                            <option value="gambar_tidak_relevan">Gambar tidak relevan</option>
                            <option value="duplikat">Barang duplikat</option>
                            <option value="melanggar_aturan">Melanggar aturan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="small fw-semibold mb-1" style="font-size: 0.7rem;">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3" id="reason" name="reason" rows="2" required style="font-size: 0.7rem;"></textarea>
                        <small class="text-secondary d-block mt-1" style="font-size: 0.55rem;">Minimal 10 karakter</small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-3 pb-3">
                    <button type="button" class="btn btn-sm rounded-4 px-3 py-1" data-bs-dismiss="modal" style="background: white; border: 1px solid #EDF2F0; color: #1A2A24;">Batal</button>
                    <button type="submit" class="btn btn-sm rounded-4 px-3 py-1" style="background: #dc3545; color: white; border: none;">
                        <i class="bi bi-x-circle me-1"></i>Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Change main image
    window.changeMainImage = function(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail-container').forEach(el => {
            el.classList.remove('active');
            el.style.borderColor = '#EDF2F0';
        });
        element.classList.add('active');
        element.style.borderColor = '#22c55e';
    };

    // Approve item
    window.approveItem = function() {
        const modalEl = document.getElementById('approveModal');
        if (modalEl) {
            new bootstrap.Modal(modalEl).show();
        }
    };

    // Reject item
    window.rejectItem = function() {
        const modalEl = document.getElementById('rejectModal');
        if (modalEl) {
            new bootstrap.Modal(modalEl).show();
        }
    };

    // Auto-fill reason
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('reason_category');
        const reasonField = document.getElementById('reason');

        if (categorySelect && reasonField) {
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

            categorySelect.addEventListener('change', function() {
                if (this.value && reasons[this.value]) {
                    reasonField.value = reasons[this.value];
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
    .admin-card {
        transition: all 0.2s;
        background: white;
        border: 1px solid #EDF2F0 !important;
    }

    .admin-card:hover {
        border-color: rgba(34, 197, 94, 0.2) !important;
    }

    .main-image-container {
        background: #F8FBF8;
        border: 1px solid #EDF2F0;
    }

    .thumbnail-container {
        border: 2px solid #EDF2F0;
        transition: all 0.2s;
    }

    .thumbnail-container.active {
        border-color: #22c55e !important;
    }

    .thumbnail-container:hover {
        opacity: 0.8;
    }

    .info-group {
        background: #F8FBF8;
    }

    .legacy-card {
        background: #F8FBF8;
        border-left: 3px solid #22c55e;
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
    [data-bs-theme="dark"] .admin-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] .main-image-container {
        background: #1A1A2C;
    }

    [data-bs-theme="dark"] .info-group {
        background: rgba(255, 255, 255, 0.03);
    }

    [data-bs-theme="dark"] .thumbnail-container {
        border-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .thumbnail-container.active {
        border-color: #22c55e !important;
    }

    [data-bs-theme="dark"] .text-secondary {
        color: #9CA3AF !important;
    }

    /* MODAL DARK MODE */
    [data-bs-theme="dark"] .modal-content {
        background: #1A1A2C;
    }

    [data-bs-theme="dark"] .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: #E0E0E0;
    }

    [data-bs-theme="dark"] .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
    }
</style>
@endpush
