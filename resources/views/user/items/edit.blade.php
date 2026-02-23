@extends('layouts.app')

@section('title', 'Edit Barang - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center gap-3 mb-5">
                <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 fw-bold mb-1">Edit Barang</h1>
                    <p class="text-secondary mb-0">Perbarui informasi barangmu</p>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-warning rounded-pill px-4 py-2">
                        <i class="bi bi-clock me-1"></i>Status: Menunggu Moderasi
                    </span>
                </div>
            </div>

            <!-- Alert -->
            <div class="alert alert-warning rounded-4 border-0 mb-4">
                <div class="d-flex">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                    <div>
                        <p class="fw-semibold mb-1">Perhatian!</p>
                        <p class="small mb-0">Barang dengan status pending masih bisa diedit. Setelah admin memproses, barang tidak bisa diubah lagi.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Informasi Dasar -->
                <div class="form-step-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">1</span>
                        </div>
                        <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-4 @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $item->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category" class="form-label fw-semibold">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg rounded-4 @error('category') is-invalid @enderror"
                                    id="category"
                                    name="category"
                                    required>
                                <option value="buku" {{ old('category', $item->category) == 'buku' ? 'selected' : '' }}>📚 Buku</option>
                                <option value="seragam" {{ old('category', $item->category) == 'seragam' ? 'selected' : '' }}>👕 Seragam</option>
                                <option value="alat_praktikum" {{ old('category', $item->category) == 'alat_praktikum' ? 'selected' : '' }}>🔬 Alat Praktikum</option>
                                <option value="lainnya" {{ old('category', $item->category) == 'lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="condition" class="form-label fw-semibold">
                                Kondisi Barang <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg rounded-4 @error('condition') is-invalid @enderror"
                                    id="condition"
                                    name="condition"
                                    required>
                                <option value="baru" {{ old('condition', $item->condition) == 'baru' ? 'selected' : '' }}>🆕 Baru</option>
                                <option value="sangat_baik" {{ old('condition', $item->condition) == 'sangat_baik' ? 'selected' : '' }}>✨ Sangat Baik</option>
                                <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>👍 Baik</option>
                                <option value="cukup" {{ old('condition', $item->condition) == 'cukup' ? 'selected' : '' }}>🆗 Cukup</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">
                                Deskripsi Barang <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control rounded-4 @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      required>{{ old('description', $item->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Foto Barang -->
                <div class="form-step-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">2</span>
                        </div>
                        <h5 class="fw-bold mb-0">Foto Barang</h5>
                    </div>

                    <!-- Existing Images -->
                    @php
                        $existingImages = is_array($item->images) ? $item->images : (is_string($item->images) ? json_decode($item->images, true) : []) ?? [];
                    @endphp

                    @if(!empty($existingImages))
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Saat Ini</label>
                            <div class="row g-3">
                                @foreach($existingImages as $index => $image)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="position-relative">
                                            <img src="{{ Storage::url($image) }}" class="w-100 rounded-3" style="object-fit: cover; aspect-ratio: 1;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle p-1" onclick="removeExistingImage({{ $index }})" style="width: 30px; height: 30px;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="existing_images" id="existingImages" value="{{ json_encode($existingImages) }}">
                        </div>
                    @endif

                    <!-- Upload New Images -->
                    <div>
                        <label for="images" class="form-label fw-semibold">
                            Tambah Foto Baru (Opsional)
                        </label>
                        <div class="upload-area p-5 text-center rounded-4" id="uploadArea">
                            <i class="bi bi-cloud-upload fs-1 text-secondary mb-3"></i>
                            <h6 class="fw-bold mb-2">Drag & drop foto disini</h6>
                            <p class="small text-secondary mb-3">atau</p>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" id="selectFilesBtn">
                                Pilih File
                            </button>
                            <input type="file" class="d-none" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg">
                        </div>
                    </div>

                    <!-- Preview New Images -->
                    <div class="preview-container row g-3 mt-3" id="previewContainer"></div>
                </div>

                <!-- Tipe & Harga -->
                <div class="form-step-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">3</span>
                        </div>
                        <h5 class="fw-bold mb-0">Tipe & Harga</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeGift" value="gift" {{ old('type', $item->type) == 'gift' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="typeGift">
                                        <span class="fw-semibold">🎁 Hibah (Gratis)</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeSale" value="sale" {{ old('type', $item->type) == 'sale' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="typeSale">
                                        <span class="fw-semibold">💰 Dijual (Harga Subsidi)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" id="priceField" style="{{ old('type', $item->type) == 'sale' ? '' : 'display: none;' }}">
                            <label for="price" class="form-label fw-semibold">
                                Harga Barang <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent rounded-start-4 border-end-0">Rp</span>
                                <input type="number" class="form-control form-control-lg rounded-end-4" id="price" name="price" value="{{ old('price', $item->price) }}" min="1000" max="10000000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legacy Message -->
                <div class="form-step-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">4</span>
                        </div>
                        <h5 class="fw-bold mb-0">Legacy Message ✨</h5>
                    </div>

                    <div class="legacy-message-field p-4 rounded-4">
                        <label for="legacy_message" class="form-label fw-semibold">
                            Pesan untuk adik kelas <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control rounded-4 @error('legacy_message') is-invalid @enderror"
                                  id="legacy_message"
                                  name="legacy_message"
                                  rows="4"
                                  required>{{ old('legacy_message', $item->legacy_message) }}</textarea>
                        @error('legacy_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-secondary">
                                <i class="bi bi-quote"></i> Edit pesan inspirasimu
                            </small>
                            <small class="text-secondary">
                                <span id="charCount">{{ strlen(old('legacy_message', $item->legacy_message)) }}</span>/1000 karakter
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary btn-rounded px-5 py-3 grow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle price field
    const typeGift = document.getElementById('typeGift');
    const typeSale = document.getElementById('typeSale');
    const priceField = document.getElementById('priceField');

    typeGift.addEventListener('change', function() {
        if (this.checked) priceField.style.display = 'none';
    });

    typeSale.addEventListener('change', function() {
        if (this.checked) priceField.style.display = 'block';
    });

    // Image upload preview
    const uploadArea = document.getElementById('uploadArea');
    const selectFilesBtn = document.getElementById('selectFilesBtn');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('previewContainer');

    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'rgba(102,126,234,0.05)';
        uploadArea.style.borderColor = '#667eea';
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'transparent';
        uploadArea.style.borderColor = 'rgba(0,0,0,0.05)';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'transparent';
        uploadArea.style.borderColor = 'rgba(0,0,0,0.05)';
        fileInput.files = e.dataTransfer.files;
        previewImages();
    });

    selectFilesBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.click();
    });

    fileInput.addEventListener('change', previewImages);

    function previewImages() {
        previewContainer.innerHTML = '';
        Array.from(fileInput.files).forEach((file, index) => {
            if (index < 5) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-4 col-lg-3';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="w-100 rounded-3" style="object-fit: cover; aspect-ratio: 1;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle p-1" onclick="removeImage(${index})" style="width: 30px; height: 30px;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(col);
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

    // Remove existing image
    window.removeExistingImage = function(index) {
        const existingImages = JSON.parse(document.getElementById('existingImages').value || '[]');
        existingImages.splice(index, 1);
        document.getElementById('existingImages').value = JSON.stringify(existingImages);
        location.reload();
    }

    // Character counter
    const legacyMessage = document.getElementById('legacy_message');
    const charCount = document.getElementById('charCount');

    legacyMessage.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
</script>
@endpush
@endsection
