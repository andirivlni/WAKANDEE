@extends('layouts.app')

@section('title', 'Upload Barang - WAKANDE')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Barang
                        </span>
                    </div>
                    <h1 class="h2 fw-bold mb-3">Wariskan Perlengkapan Sekolahmu</h1>
                    <p class="text-secondary">Setiap barang yang kamu bagikan akan menjadi legacy untuk adik kelas.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 mb-4 shadow-sm">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <div>
                                <p class="fw-bold mb-1">Ada kesalahan input:</p>
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" id="uploadForm" novalidate>
                    @csrf

                    <div class="form-step-card p-4 p-md-5 mb-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                                style="width: 40px; height: 40px;">
                                <span class="fw-bold text-primary">1</span>
                            </div>
                            <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold">Nama Barang <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="form-control form-control-lg rounded-4 @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Buku Matematika Kelas 10" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold">Kategori <span
                                        class="text-danger">*</span></label>
                                <select name="category" id="category"
                                    class="form-select form-select-lg rounded-4 @error('category') is-invalid @enderror"
                                    required>
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori
                                    </option>
                                    <option value="buku" {{ old('category') == 'buku' ? 'selected' : '' }}>📚 Buku
                                    </option>
                                    <option value="seragam" {{ old('category') == 'seragam' ? 'selected' : '' }}>👕 Seragam
                                    </option>
                                    <option value="alat_praktikum"
                                        {{ old('category') == 'alat_praktikum' ? 'selected' : '' }}>🔬 Alat Praktikum
                                    </option>
                                    <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>📦 Lainnya
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="condition" class="form-label fw-semibold">Kondisi Barang <span
                                        class="text-danger">*</span></label>
                                <select name="condition" id="condition"
                                    class="form-select form-select-lg rounded-4 @error('condition') is-invalid @enderror"
                                    required>
                                    <option value="" disabled {{ old('condition') ? '' : 'selected' }}>Pilih kondisi
                                    </option>
                                    <option value="baru" {{ old('condition') == 'baru' ? 'selected' : '' }}>🆕 Baru
                                    </option>
                                    <option value="sangat_baik" {{ old('condition') == 'sangat_baik' ? 'selected' : '' }}>✨
                                        Sangat Baik</option>
                                    <option value="baik" {{ old('condition') == 'baik' ? 'selected' : '' }}>👍 Baik
                                    </option>
                                    <option value="cukup" {{ old('condition') == 'cukup' ? 'selected' : '' }}>🆗 Cukup
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Deskripsi Barang <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control rounded-4 @error('description') is-invalid @enderror"
                                    placeholder="Jelaskan detail barangmu (Min. 20 karakter)" required>{{ old('description') }}</textarea>
                                <small class="text-muted"><i class="bi bi-info-circle"></i> Minimal 20 karakter</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-step-card p-4 p-md-5 mb-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                                style="width: 40px; height: 40px;">
                                <span class="fw-bold text-primary">2</span>
                            </div>
                            <h5 class="fw-bold mb-0">Foto Barang</h5>
                        </div>

                        <div class="upload-area p-5 text-center rounded-4" id="uploadArea"
                            style="border: 2px dashed #ddd; cursor: pointer;">
                            <i class="bi bi-cloud-upload fs-1 text-secondary mb-3"></i>
                            <h6 class="fw-bold mb-2">Pilih atau Drag & Drop Foto</h6>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4">Pilih
                                File</button>
                            <input type="file" name="images[]" id="images" class="d-none" multiple accept="image/*">
                        </div>
                        <div class="text-danger small mt-2 d-none" id="imageError">Pilih minimal 1 foto barang terlebih
                            dahulu.</div>
                        <div class="preview-container row g-3 mt-3" id="previewContainer"></div>
                        @error('images')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-step-card p-4 p-md-5 mb-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                                style="width: 40px; height: 40px;">
                                <span class="fw-bold text-primary">3</span>
                            </div>
                            <h5 class="fw-bold mb-0">Tipe & Harga</h5>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tipe Barang <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="typeGift"
                                            value="gift" {{ old('type', 'gift') == 'gift' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeGift">🎁 Hibah (Gratis)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="typeSale"
                                            value="sale" {{ old('type') == 'sale' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeSale">💰 Dijual</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" id="priceField"
                                style="{{ old('type') == 'sale' ? '' : 'display: none;' }}">
                                <label for="price" class="form-label fw-semibold">Harga Barang <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent rounded-start-4">Rp</span>
                                    <input type="number" name="price" id="price" value="{{ old('price', 0) }}"
                                        class="form-control form-control-lg rounded-end-4" placeholder="10000">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step-card p-4 p-md-5 mb-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                                style="width: 40px; height: 40px;">
                                <span class="fw-bold text-primary">4</span>
                            </div>
                            <h5 class="fw-bold mb-0">Legacy Message ✨</h5>
                        </div>
                        <textarea name="legacy_message" id="legacy_message" rows="4"
                            class="form-control rounded-4 @error('legacy_message') is-invalid @enderror"
                            placeholder="Pesan untuk adik kelas..." required>{{ old('legacy_message') }}</textarea>
                        <div class="d-flex justify-content-between mt-2 small text-secondary">
                            <span>Min. 10 karakter</span>
                            <span><span id="charCount">0</span>/1000</span>
                        </div>

                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2" onclick="setLegacyMessage(this)"
                                style="cursor:pointer">Semoga bermanfaat! 📚</span>
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2" onclick="setLegacyMessage(this)"
                                style="cursor:pointer">Belajar yang rajin ya! ⭐</span>
                        </div>
                    </div>

                    <div class="form-step-card p-4 p-md-5 mb-4 shadow-sm text-center">
                        <div class="form-check d-inline-block mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label small text-secondary" for="terms">Saya menyatakan data ini
                                benar.</label>
                        </div>
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 grow fw-bold rounded-4 shadow"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                Upload Sekarang
                            </button>
                            <a href="{{ route('items.index') }}"
                                class="btn btn-outline-secondary btn-lg px-4 py-3 rounded-4">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const typeGift = document.getElementById('typeGift');
                const typeSale = document.getElementById('typeSale');
                const priceField = document.getElementById('priceField');
                const priceInput = document.getElementById('price');
                const fileInput = document.getElementById('images');
                const uploadArea = document.getElementById('uploadArea');
                const legacyMsg = document.getElementById('legacy_message');
                const charCount = document.getElementById('charCount');

                // 1. Toggle Harga
                function togglePrice() {
                    if (typeSale && typeSale.checked) {
                        priceField.style.display = 'block';
                        priceInput.setAttribute('required', 'required');
                    } else {
                        priceField.style.display = 'none';
                        priceInput.removeAttribute('required');
                    }
                }
                if (typeGift) typeGift.addEventListener('change', togglePrice);
                if (typeSale) typeSale.addEventListener('change', togglePrice);

                // 2. Preview Gambar
                if (uploadArea) uploadArea.addEventListener('click', () => fileInput.click());
                if (fileInput) {
                    fileInput.addEventListener('change', function() {
                        const container = document.getElementById('previewContainer');
                        container.innerHTML = '';
                        Array.from(this.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const div = document.createElement('div');
                                div.className = 'col-6 col-md-3';
                                div.innerHTML =
                                    `<img src="${e.target.result}" class="w-100 rounded-3 shadow-sm" style="aspect-ratio:1; object-fit:cover;">`;
                                container.appendChild(div);
                            };
                            reader.readAsDataURL(file);
                        });
                    });
                }

                // 3. Counter Karakter
                if (legacyMsg) {
                    legacyMsg.addEventListener('input', function() {
                        charCount.textContent = this.value.length;
                    });
                }
            });

            // 4. Set Legacy Message (Luar DOMContentLoaded agar bisa dipanggil onclick)
            function setLegacyMessage(element) {
                const input = document.getElementById('legacy_message');
                const display = document.getElementById('charCount');
                if (element && input) {
                    const text = element.textContent.trim();
                    input.value = text;
                    if (display) display.textContent = text.length;
                }
            }

            // 5. Bootstrap Validation
            (function() {
                'use strict'
                const forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        const fileInput = document.getElementById('images');
                        const imageError = document.getElementById('imageError');
                        let isFilesValid = true;

                        if (fileInput && fileInput.files.length === 0) {
                            isFilesValid = false;
                            if (imageError) imageError.classList.remove('d-none');
                        } else {
                            if (imageError) imageError.classList.add('d-none');
                        }

                        if (!form.checkValidity() || !isFilesValid) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            })()
        </script>
    @endpush

    @push('styles')
        <style>
            .form-step-card {
                background: white;
                border-radius: 24px;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }

            .upload-area:hover {
                border-color: #667eea !important;
                background: rgba(102, 126, 234, 0.02);
            }
        </style>
    @endpush
@endsection
