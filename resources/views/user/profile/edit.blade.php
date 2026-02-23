@extends('layouts.app')

@section('title', 'Edit Profil - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 mb-3">
                    <i class="bi bi-person me-2"></i>PROFIL
                </span>
                <h1 class="h2 fw-bold mb-3">Edit Profil</h1>
                <p class="text-secondary">
                    Perbarui informasi dirimu untuk pengalaman yang lebih baik di WAKANDE
                </p>
            </div>

            <!-- Profile Photo -->
            <div class="profile-card p-4 p-md-5 mb-4 text-center">
                <div class="position-relative d-inline-block">
                    <!-- Photo Preview -->
                    <div class="photo-preview mb-3">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                 alt="{{ Auth::user()->name }}"
                                 id="profilePreview"
                                 class="rounded-circle"
                                 width="120"
                                 height="120"
                                 style="object-fit: cover; border: 4px solid rgba(102,126,234,0.2);">
                        @else
                            <div class="avatar-circle mx-auto"
                                 id="profilePreview"
                                 style="width: 120px; height: 120px; font-size: 3rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Upload Button -->
                    <label for="profile_photo" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 p-2"
                           style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 2px solid white;">
                        <i class="bi bi-camera-fill"></i>
                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*" form="profileForm">
                    </label>
                </div>

                <p class="small text-secondary mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Format: JPG, PNG. Maksimal 2MB
                </p>

                @if(Auth::user()->profile_photo)
                    <button type="button" class="btn btn-link text-danger text-decoration-none mt-2" onclick="removePhoto()">
                        <i class="bi bi-trash me-1"></i>Hapus Foto
                    </button>
                @endif
            </div>

            <!-- Edit Profile Form -->
            <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Personal Information -->
                <div class="profile-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="section-icon d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-badge text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Informasi Pribadi</h5>
                    </div>

                    <div class="row g-4">
                        <!-- Name -->
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                    <i class="bi bi-person text-secondary"></i>
                                </span>
                                <input type="text"
                                       class="form-control form-control-lg border-start-0 rounded-end-4 @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', Auth::user()->name) }}"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email (Read Only) -->
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold">
                                Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                    <i class="bi bi-envelope text-secondary"></i>
                                </span>
                                <input type="email"
                                       class="form-control form-control-lg border-start-0 rounded-end-4 bg-light"
                                       id="email"
                                       value="{{ Auth::user()->email }}"
                                       readonly
                                       disabled>
                            </div>
                            <small class="text-secondary d-block mt-2">
                                <i class="bi bi-info-circle"></i> Email tidak dapat diubah
                            </small>
                        </div>

                        <!-- Phone -->
                        <div class="col-12">
                            <label for="phone" class="form-label fw-semibold">
                                Nomor WhatsApp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                    <i class="bi bi-whatsapp text-secondary"></i>
                                </span>
                                <input type="tel"
                                       class="form-control form-control-lg border-start-0 rounded-end-4 @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', Auth::user()->phone) }}"
                                       placeholder="Contoh: 081234567890">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-secondary d-block mt-2">
                                <i class="bi bi-info-circle"></i> Digunakan untuk koordinasi serah terima barang
                            </small>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="profile-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="section-icon d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <i class="bi bi-building text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Informasi Sekolah</h5>
                    </div>

                    <div class="row g-4">
                        <!-- School -->
                        <div class="col-md-8">
                            <label for="school" class="form-label fw-semibold">
                                Asal Sekolah <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                    <i class="bi bi-building text-secondary"></i>
                                </span>
                                <input type="text"
                                       class="form-control form-control-lg border-start-0 rounded-end-4 @error('school') is-invalid @enderror"
                                       id="school"
                                       name="school"
                                       value="{{ old('school', Auth::user()->school) }}"
                                       placeholder="Contoh: SMAN 1 Balikpapan"
                                       required>
                            </div>
                            @error('school')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Grade -->
                        <div class="col-md-4">
                            <label for="grade" class="form-label fw-semibold">
                                Kelas <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg rounded-4 @error('grade') is-invalid @enderror"
                                    id="grade"
                                    name="grade"
                                    required>
                                <option value="" disabled {{ !old('grade', Auth::user()->grade) ? 'selected' : '' }}>Pilih kelas</option>
                                <option value="10" {{ old('grade', Auth::user()->grade) == '10' ? 'selected' : '' }}>Kelas 10</option>
                                <option value="11" {{ old('grade', Auth::user()->grade) == '11' ? 'selected' : '' }}>Kelas 11</option>
                                <option value="12" {{ old('grade', Auth::user()->grade) == '12' ? 'selected' : '' }}>Kelas 12</option>
                                <option value="alumni" {{ old('grade', Auth::user()->grade) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                            </select>
                            @error('grade')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="profile-card p-4 p-md-5 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="section-icon d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Informasi Akun</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                                <small class="text-secondary d-block mb-1">Member Since</small>
                                <span class="fw-semibold">{{ Auth::user()->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                                <small class="text-secondary d-block mb-1">Status Akun</small>
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-4 d-flex justify-content-between align-items-center" style="background: rgba(102,126,234,0.02);">
                                <div>
                                    <small class="text-secondary d-block mb-1">Password</small>
                                    <span class="fw-semibold">••••••••</span>
                                </div>
                                <a href="{{ route('profile.password') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                    <i class="bi bi-pencil me-1"></i>Ubah Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary btn-rounded px-5 py-3 grow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview profile photo before upload
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.querySelector('.photo-preview');
                const currentPreview = document.getElementById('profilePreview');

                if (currentPreview.tagName === 'IMG') {
                    currentPreview.src = e.target.result;
                } else {
                    // Replace avatar circle with image
                    const img = document.createElement('img');
                    img.id = 'profilePreview';
                    img.src = e.target.result;
                    img.alt = '{{ Auth::user()->name }}';
                    img.className = 'rounded-circle';
                    img.style = 'width: 120px; height: 120px; object-fit: cover; border: 4px solid rgba(102,126,234,0.2);';
                    previewContainer.innerHTML = '';
                    previewContainer.appendChild(img);
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove profile photo
    function removePhoto() {
        Swal.fire({
            title: 'Hapus Foto Profil?',
            text: 'Foto profil akan dihapus dan diganti dengan avatar default',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profile.photo.remove") }}';

                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Form validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endpush

@push('styles')
<style>
    .profile-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        border-radius: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .section-icon {
        transition: all 0.3s;
    }

    .profile-card:hover .section-icon {
        transform: scale(1.1);
    }

    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    [data-bs-theme="dark"] .profile-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .bg-light {
        background: rgba(255,255,255,0.05) !important;
        color: white !important;
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0,0,0,0.02);
    }

    [data-bs-theme="dark"] .form-control:disabled,
    [data-bs-theme="dark"] .form-control[readonly] {
        background-color: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.7);
    }
</style>
@endpush
@endsection
