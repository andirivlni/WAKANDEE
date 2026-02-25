@extends('layouts.app')

@section('title', 'Edit Barang - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- HEADER MINI --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center"
                   style="width: 32px; height: 32px; background: #F8FBF8; border: 1px solid #EDF2F0;">
                    <i class="bi bi-arrow-left" style="font-size: 0.9rem;"></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0" style="color: #1A2A24;">Edit Barang</h5>
                    <p class="small text-secondary mb-0" style="font-size: 0.75rem;">Perbarui informasi barangmu</p>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-warning rounded-pill px-3 py-1" style="font-size: 0.7rem;">
                        <i class="bi bi-clock me-1" style="font-size: 0.6rem;"></i>Menunggu Moderasi
                    </span>
                </div>
            </div>

            {{-- ALERT MINI --}}
            <div class="alert alert-warning rounded-3 py-2 px-3 mb-3" style="background: rgba(255,193,7,0.05); border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill" style="color: #ffc107; font-size: 0.9rem;"></i>
                    <small class="text-warning" style="font-size: 0.75rem;">
                        Barang dengan status pending masih bisa diedit. Setelah admin memproses, barang tidak bisa diubah lagi.
                    </small>
                </div>
            </div>

            {{-- FORM --}}
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- STEP 1: INFORMASI DASAR --}}
                <div class="form-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <span class="fw-semibold" style="color: #22c55e; font-size: 0.8rem;">1</span>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Informasi Dasar</h6>
                    </div>

                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm rounded-3 @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name', $item->name) }}" required
                                   style="font-size: 0.85rem; border-color: #EDF2F0;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small mb-1">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm rounded-3 @error('category') is-invalid @enderror"
                                    name="category" required style="font-size: 0.85rem;">
                                <option value="buku" {{ old('category', $item->category) == 'buku' ? 'selected' : '' }}>📚 Buku</option>
                                <option value="seragam" {{ old('category', $item->category) == 'seragam' ? 'selected' : '' }}>👕 Seragam</option>
                                <option value="alat_praktikum" {{ old('category', $item->category) == 'alat_praktikum' ? 'selected' : '' }}>🔬 Alat Praktikum</option>
                                <option value="lainnya" {{ old('category', $item->category) == 'lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small mb-1">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm rounded-3 @error('condition') is-invalid @enderror"
                                    name="condition" required style="font-size: 0.85rem;">
                                <option value="baru" {{ old('condition', $item->condition) == 'baru' ? 'selected' : '' }}>🆕 Baru</option>
                                <option value="sangat_baik" {{ old('condition', $item->condition) == 'sangat_baik' ? 'selected' : '' }}>✨ Sangat Baik</option>
                                <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>👍 Baik</option>
                                <option value="cukup" {{ old('condition', $item->condition) == 'cukup' ? 'selected' : '' }}>🆗 Cukup</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-3 @error('description') is-invalid @enderror"
                                      name="description" rows="3" required
                                      style="font-size: 0.85rem;">{{ old('description', $item->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: FOTO BARANG --}}
                <div class="form-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <span class="fw-semibold" style="color: #22c55e; font-size: 0.8rem;">2</span>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Foto Barang</h6>
                    </div>

                    {{-- Existing Images --}}
                    @php
                        $existingImages = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []) ?? [];
                    @endphp

                    @if(!empty($existingImages))
                        <div class="mb-3">
                            <label class="small fw-semibold mb-2">Foto Saat Ini</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($existingImages as $index => $image)
                                    <div class="position-relative" style="width: 70px; height: 70px;">
                                        <img src="{{ Storage::url($image) }}" class="w-100 h-100 rounded-3" style="object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 rounded-circle d-flex align-items-center justify-content-center"
                                                onclick="removeExistingImage({{ $index }})"
                                                style="width: 20px; height: 20px; font-size: 0.6rem;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="existing_images" id="existingImages" value="{{ json_encode($existingImages) }}">
                        </div>
                    @endif

                    {{-- Upload New --}}
                    <div>
                        <label class="small fw-semibold mb-2">Tambah Foto Baru (Opsional)</label>
                        <div class="upload-area p-3 text-center rounded-3" id="uploadArea" style="background: #F8FBF8; border: 1px dashed #EDF2F0; cursor: pointer;">
                            <i class="bi bi-cloud-upload" style="color: #22c55e; font-size: 1.2rem;"></i>
                            <p class="small mb-1" style="color: #6c757d;">Drag & drop atau <span style="color: #22c55e;">pilih file</span></p>
                            <input type="file" class="d-none" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg">
                        </div>
                    </div>

                    {{-- Preview New --}}
                    <div class="d-flex flex-wrap gap-2 mt-2" id="previewContainer"></div>
                </div>

                {{-- STEP 3: TIPE & HARGA --}}
                <div class="form-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <span class="fw-semibold" style="color: #22c55e; font-size: 0.8rem;">3</span>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Tipe & Harga</h6>
                    </div>

                    <div class="d-flex gap-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeGift" value="gift" {{ old('type', $item->type) == 'gift' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="typeGift">🎁 Hibah (Gratis)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeSale" value="sale" {{ old('type', $item->type) == 'sale' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="typeSale">💰 Dijual</label>
                        </div>
                    </div>

                    <div id="priceField" style="{{ old('type', $item->type) == 'sale' ? '' : 'display: none;' }}">
                        <label class="small fw-semibold mb-1">Harga</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent">Rp</span>
                            <input type="number" class="form-control" name="price" value="{{ old('price', $item->price) }}" style="font-size: 0.85rem;">
                        </div>
                    </div>
                </div>

                {{-- STEP 4: LEGACY MESSAGE --}}
                <div class="form-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <span class="fw-semibold" style="color: #22c55e; font-size: 0.8rem;">4</span>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Pesan Warisan</h6>
                    </div>

                    <textarea class="form-control rounded-3 @error('legacy_message') is-invalid @enderror"
                              name="legacy_message" rows="2" required
                              style="font-size: 0.85rem;">{{ old('legacy_message', $item->legacy_message) }}</textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-secondary" style="font-size: 0.6rem;"><i class="bi bi-quote"></i> Edit pesan inspirasimu</small>
                        <small class="text-secondary" style="font-size: 0.6rem;"><span id="charCount">{{ strlen(old('legacy_message', $item->legacy_message)) }}</span>/1000</small>
                    </div>
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm rounded-4 px-4 py-2" style="background: #22c55e; color: white; border: none;">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </button>
                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm rounded-4 px-4 py-2" style="background: white; border: 1px solid #EDF2F0; color: #1A2A24;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle price field
    const typeGift = document.getElementById('typeGift');
    const typeSale = document.getElementById('typeSale');
    const priceField = document.getElementById('priceField');

    typeGift?.addEventListener('change', function() {
        if (this.checked) priceField.style.display = 'none';
    });

    typeSale?.addEventListener('change', function() {
        if (this.checked) priceField.style.display = 'block';
    });

    // Upload area click
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('previewContainer');

    uploadArea?.addEventListener('click', () => fileInput.click());

    uploadArea?.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'rgba(34,197,94,0.05)';
        uploadArea.style.borderColor = '#22c55e';
    });

    uploadArea?.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.background = '#F8FBF8';
        uploadArea.style.borderColor = '#EDF2F0';
    });

    uploadArea?.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.background = '#F8FBF8';
        uploadArea.style.borderColor = '#EDF2F0';
        fileInput.files = e.dataTransfer.files;
        previewImages();
    });

    fileInput?.addEventListener('change', previewImages);

    function previewImages() {
        previewContainer.innerHTML = '';
        Array.from(fileInput.files).forEach((file, index) => {
            if (index < 5) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'position-relative';
                    div.style.width = '70px';
                    div.style.height = '70px';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-100 h-100 rounded-3" style="object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 rounded-circle d-flex align-items-center justify-content-center"
                                onclick="removeImage(${index})"
                                style="width: 20px; height: 20px; font-size: 0.6rem;">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            }
        });
    }

    window.removeImage = function(index) {
        const dt = new DataTransfer();
        Array.from(fileInput.files).forEach((file, i) => {
            if (i !== index) dt.items.add(file);
        });
        fileInput.files = dt.files;
        previewImages();
    }

    window.removeExistingImage = function(index) {
        const existingImages = JSON.parse(document.getElementById('existingImages')?.value || '[]');
        existingImages.splice(index, 1);
        document.getElementById('existingImages').value = JSON.stringify(existingImages);
        location.reload();
    }

    // Character counter
    const legacyMessage = document.getElementById('legacy_message');
    const charCount = document.getElementById('charCount');

    legacyMessage?.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
</script>
@endpush

@push('styles')
<style>
    .form-card {
        transition: all 0.2s;
        background: white;
    }

    .form-card:hover {
        border-color: rgba(34, 197, 94, 0.2) !important;
    }

    .upload-area {
        transition: all 0.2s;
    }

    .upload-area:hover {
        background: rgba(34, 197, 94, 0.05) !important;
        border-color: #22c55e !important;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .form-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255,255,255,0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255,255,255,0.03) !important;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select,
    [data-bs-theme="dark"] .input-group-text {
        background: rgba(255,255,255,0.05) !important;
        border-color: rgba(255,255,255,0.1) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .form-label,
    [data-bs-theme="dark"] h6 {
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .small.text-secondary {
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] .btn-outline-secondary {
        border-color: rgba(255,255,255,0.1) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .btn-outline-secondary:hover {
        background: rgba(255,255,255,0.1) !important;
    }
</style>
@endpush
