@extends('layouts.app')

@section('title', 'Edit Profil - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- HEADER MINI --}}
            <div class="text-center mb-4">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 mb-2" style="font-size: 0.8rem;">
                    <i class="bi bi-person me-1"></i>PROFIL
                </span>
                <h5 class="fw-bold mb-1" style="color: #1A2A24;">Edit Profil</h5>
                <p class="small text-secondary" style="max-width: 500px; margin: 0 auto; font-size: 0.8rem;">
                    Perbarui informasi dirimu untuk pengalaman yang lebih baik di WAKANDE
                </p>
            </div>

            {{-- PROFILE PHOTO --}}
            <div class="profile-card p-4 mb-3 text-center" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                <div class="position-relative d-inline-block">
                    {{-- Photo Preview --}}
                    <div class="photo-preview mb-2">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                 alt="{{ Auth::user()->name }}"
                                 id="profilePreview"
                                 class="rounded-circle"
                                 width="80"
                                 height="80"
                                 style="object-fit: cover; border: 3px solid rgba(34, 197, 94, 0.2);">
                        @else
                            <div class="avatar-circle mx-auto d-flex align-items-center justify-content-center"
                                 id="profilePreview"
                                 style="width: 80px; height: 80px; font-size: 2rem; background: #22c55e; color: white; border-radius: 50%;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Upload Button --}}
                    <label for="profile_photo" class="btn btn-sm rounded-circle position-absolute bottom-0 end-0 p-1 d-flex align-items-center justify-content-center"
                           style="width: 28px; height: 28px; background: #22c55e; border: 2px solid white;">
                        <i class="bi bi-camera-fill" style="font-size: 0.7rem; color: white;"></i>
                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*" form="profileForm">
                    </label>
                </div>

                <p class="small text-secondary mt-2 mb-0" style="font-size: 0.7rem;">
                    <i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maksimal 2MB
                </p>

                @if(Auth::user()->profile_photo)
                    <button type="button" class="btn btn-link text-danger text-decoration-none mt-1 p-0" style="font-size: 0.7rem;" onclick="removePhoto()">
                        <i class="bi bi-trash me-1"></i>Hapus Foto
                    </button>
                @endif
            </div>

            {{-- FORM --}}
            <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                {{-- INFORMASI PRIBADI --}}
                <div class="profile-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <i class="bi bi-person-badge" style="color: #22c55e; font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Informasi Pribadi</h6>
                    </div>

                    <div class="row g-2">
                        {{-- Name --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                    <i class="bi bi-person text-secondary" style="font-size: 0.8rem;"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       style="font-size: 0.85rem; border-color: #EDF2F0;">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                    <i class="bi bi-envelope text-secondary" style="font-size: 0.8rem;"></i>
                                </span>
                                <input type="email" class="form-control border-start-0 bg-light"
                                       value="{{ Auth::user()->email }}" readonly disabled
                                       style="font-size: 0.85rem; border-color: #EDF2F0;">
                            </div>
                            <small class="text-secondary d-block mt-1" style="font-size: 0.65rem;">
                                <i class="bi bi-info-circle"></i> Email tidak dapat diubah
                            </small>
                        </div>

                        {{-- Phone --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                    <i class="bi bi-whatsapp text-secondary" style="font-size: 0.8rem;"></i>
                                </span>
                                <input type="tel" class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                       placeholder="081234567890" style="font-size: 0.85rem; border-color: #EDF2F0;">
                            </div>
                            <small class="text-secondary d-block mt-1" style="font-size: 0.65rem;">
                                <i class="bi bi-info-circle"></i> Untuk koordinasi serah terima barang
                            </small>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI SEKOLAH --}}
                <div class="profile-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <i class="bi bi-building" style="color: #22c55e; font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Informasi Sekolah</h6>
                    </div>

                    <div class="row g-2">
                        {{-- School --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small mb-1">Asal Sekolah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                    <i class="bi bi-building text-secondary" style="font-size: 0.8rem;"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 @error('school') is-invalid @enderror"
                                       name="school" value="{{ old('school', Auth::user()->school) }}"
                                       placeholder="SMAN 1 Balikpapan" required
                                       style="font-size: 0.85rem; border-color: #EDF2F0;">
                            </div>
                        </div>

                        {{-- Grade --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small mb-1">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select @error('grade') is-invalid @enderror"
                                    name="grade" required style="font-size: 0.85rem; border-color: #EDF2F0;">
                                <option value="" disabled {{ !old('grade', Auth::user()->grade) ? 'selected' : '' }}>Pilih</option>
                                <option value="10" {{ old('grade', Auth::user()->grade) == '10' ? 'selected' : '' }}>Kelas 10</option>
                                <option value="11" {{ old('grade', Auth::user()->grade) == '11' ? 'selected' : '' }}>Kelas 11</option>
                                <option value="12" {{ old('grade', Auth::user()->grade) == '12' ? 'selected' : '' }}>Kelas 12</option>
                                <option value="alumni" {{ old('grade', Auth::user()->grade) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI AKUN --}}
                <div class="profile-card p-3 mb-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(34,197,94,0.1);">
                            <i class="bi bi-shield-check" style="color: #22c55e; font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #1A2A24; font-size: 0.9rem;">Informasi Akun</h6>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="p-2 rounded-3" style="background: #F8FBF8;">
                                <small class="text-secondary d-block mb-0" style="font-size: 0.65rem;">Member Since</small>
                                <span class="fw-semibold" style="font-size: 0.8rem;">{{ Auth::user()->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 rounded-3" style="background: #F8FBF8;">
                                <small class="text-secondary d-block mb-0" style="font-size: 0.65rem;">Status Akun</small>
                                <span class="badge bg-success rounded-pill px-2 py-0" style="font-size: 0.7rem;">
                                    <i class="bi bi-check-circle me-1" style="font-size: 0.6rem;"></i>Aktif
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: #F8FBF8;">
                                <div>
                                    <small class="text-secondary d-block mb-0" style="font-size: 0.65rem;">Password</small>
                                    <span class="fw-semibold" style="font-size: 0.8rem;">••••••••</span>
                                </div>
                                <a href="{{ route('profile.password') }}" class="btn btn-sm rounded-4 px-3 py-1"
                                   style="background: white; border: 1px solid #EDF2F0; color: #22c55e; font-size: 0.7rem;">
                                    <i class="bi bi-pencil me-1"></i>Ubah
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm rounded-4 px-4 py-2" style="background: #22c55e; color: white; border: none;">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm rounded-4 px-4 py-2" style="background: white; border: 1px solid #EDF2F0; color: #1A2A24;">
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
    // Preview profile photo
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
                    const img = document.createElement('img');
                    img.id = 'profilePreview';
                    img.src = e.target.result;
                    img.alt = '{{ Auth::user()->name }}';
                    img.className = 'rounded-circle';
                    img.style = 'width: 80px; height: 80px; object-fit: cover; border: 3px solid rgba(34, 197, 94, 0.2);';
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
            cancelButtonText: 'Batal',
            background: 'var(--bs-body-bg)',
            color: 'var(--bs-body-color)',
            customClass: {
                popup: 'rounded-4 p-3',
                title: 'small fw-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profile.photo.remove") }}';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .profile-card {
        transition: all 0.2s;
        background: white;
    }

    .profile-card:hover {
        border-color: rgba(34, 197, 94, 0.2) !important;
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
    [data-bs-theme="dark"] .profile-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select,
    [data-bs-theme="dark"] .input-group-text {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .form-control:disabled,
    [data-bs-theme="dark"] .form-control[readonly] {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] .bg-light {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .text-secondary {
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] .btn-outline-success {
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
    }
</style>
@endpush
