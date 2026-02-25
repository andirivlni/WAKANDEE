@extends('layouts.app')

@section('title', 'Ubah Password - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            {{-- HEADER --}}
            <div class="text-center mb-4">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 mb-2" style="font-size: 0.8rem;">
                    <i class="bi bi-shield-lock me-1"></i>KEAMANAN
                </span>
                <h5 class="fw-bold mb-1" style="color: #1A2A24;">Ubah Password</h5>
                <p class="small text-secondary mb-2" style="max-width: 400px; margin: 0 auto; font-size: 0.8rem;">
                    Pastikan passwordmu kuat dan tidak digunakan di platform lain
                </p>
                <a href="{{ route('profile.edit') }}" class="small text-decoration-none" style="color: #22c55e;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Edit Profil
                </a>
            </div>

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success rounded-3 py-2 px-3 mb-3" style="background: rgba(25,135,84,0.05); border: none;">
                    <small class="text-success">{{ session('success') }}</small>
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="background: rgba(220,53,69,0.05); border: none;">
                    @foreach($errors->all() as $error)
                        <small class="text-danger d-block">{{ $error }}</small>
                    @endforeach
                </div>
            @endif

            {{-- FORM --}}
            <div class="password-card p-3" style="background: white; border: 1px solid #EDF2F0; border-radius: 16px;">
                {{-- GUNAKAN profile.password (PUT) --}}
               <form action="{{ route('profile.password.update') }}" method="POST">
               @csrf

                    @method('PUT')

                    {{-- Password Lama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small mb-1">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                <i class="bi bi-lock text-secondary" style="font-size: 0.8rem;"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 border-end-0 @error('current_password') is-invalid @enderror"
                                   name="current_password" placeholder="Masukkan password saat ini" required
                                   style="font-size: 0.85rem; border-color: #EDF2F0;">
                            <button class="input-group-text bg-transparent border-start-0 px-2" type="button"
                                    onclick="togglePassword('current_password', 'currentToggleIcon')"
                                    style="border-color: #EDF2F0;">
                                <i class="bi bi-eye-slash" id="currentToggleIcon" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <small class="text-danger" style="font-size: 0.65rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small mb-1">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                <i class="bi bi-shield-lock text-secondary" style="font-size: 0.8rem;"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 border-end-0 @error('new_password') is-invalid @enderror"
                                   id="new_password" name="new_password" placeholder="Minimal 8 karakter" required
                                   style="font-size: 0.85rem; border-color: #EDF2F0;">
                            <button class="input-group-text bg-transparent border-start-0 px-2" type="button"
                                    onclick="togglePassword('new_password', 'newToggleIcon')"
                                    style="border-color: #EDF2F0;">
                                <i class="bi bi-eye-slash" id="newToggleIcon" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>

                        {{-- Password Strength Meter --}}
                        <div class="password-strength mt-2" id="passwordStrength">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-secondary" style="font-size: 0.65rem;">Kekuatan Password</small>
                                <small class="fw-semibold" id="strengthText" style="font-size: 0.65rem;">Belum dimasukkan</small>
                            </div>
                            <div class="progress" style="height: 4px; background-color: #F0F5F0;">
                                <div class="progress-bar" id="strengthBar" style="width: 0%;"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <span id="lengthCheck" class="text-secondary" style="font-size: 0.6rem;">
                                        <i class="bi bi-x-circle me-1"></i>Minimal 8 karakter
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="uppercaseCheck" class="text-secondary" style="font-size: 0.6rem;">
                                        <i class="bi bi-x-circle me-1"></i>Huruf besar
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="lowercaseCheck" class="text-secondary" style="font-size: 0.6rem;">
                                        <i class="bi bi-x-circle me-1"></i>Huruf kecil
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="numberCheck" class="text-secondary" style="font-size: 0.6rem;">
                                        <i class="bi bi-x-circle me-1"></i>Angka
                                    </span>
                                </div>
                            </div>
                        </div>
                        @error('new_password')
                            <small class="text-danger" style="font-size: 0.65rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small mb-1">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 px-2" style="border-color: #EDF2F0;">
                                <i class="bi bi-check2-circle text-secondary" style="font-size: 0.8rem;"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 border-end-0"
                                   id="new_password_confirmation" name="new_password_confirmation"
                                   placeholder="Masukkan password yang sama" required
                                   style="font-size: 0.85rem; border-color: #EDF2F0;">
                            <button class="input-group-text bg-transparent border-start-0 px-2" type="button"
                                    onclick="togglePassword('new_password_confirmation', 'confirmToggleIcon')"
                                    style="border-color: #EDF2F0;">
                                <i class="bi bi-eye-slash" id="confirmToggleIcon" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="passwordMatchFeedback" style="font-size: 0.65rem;"></div>
                    </div>

                    {{-- Tips --}}
                    <div class="p-3 rounded-3 mb-3" style="background: #F8FBF8;">
                        <h6 class="fw-semibold small mb-2" style="color: #1A2A24;">
                            <i class="bi bi-lightbulb me-1" style="color: #22c55e;"></i>Tips Password Aman
                        </h6>
                        <ul class="small text-secondary mb-0" style="padding-left: 1rem; font-size: 0.65rem;">
                            <li class="mb-1">Gunakan kombinasi huruf besar, huruf kecil, dan angka</li>
                            <li class="mb-1">Jangan gunakan password yang sama dengan akun lain</li>
                            <li class="mb-1">Hindari informasi pribadi (tanggal lahir, nama)</li>
                            <li>Ganti password secara berkala</li>
                        </ul>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm rounded-4 px-4 py-2"
                                style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                            <i class="bi bi-shield-check me-1"></i>Update Password
                        </button>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm rounded-4 px-4 py-2"
                           style="background: white; border: 1px solid #EDF2F0; color: #1A2A24; font-size: 0.8rem;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            {{-- Security Notice --}}
            <div class="text-center mt-3">
                <div class="d-inline-flex align-items-center gap-2 p-2 rounded-3" style="background: #F8FBF8;">
                    <i class="bi bi-shield-check" style="color: #22c55e; font-size: 0.8rem;"></i>
                    <span class="small text-secondary" style="font-size: 0.7rem;">Akunmu dilindungi dengan enkripsi SSL</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }

    // Password strength checker
    const passwordInput = document.getElementById('new_password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const lengthCheck = document.getElementById('lengthCheck');
    const uppercaseCheck = document.getElementById('uppercaseCheck');
    const lowercaseCheck = document.getElementById('lowercaseCheck');
    const numberCheck = document.getElementById('numberCheck');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Length check
            if (password.length >= 8) {
                strength += 25;
                lengthCheck.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>Minimal 8 karakter';
                lengthCheck.style.color = '#198754';
            } else {
                lengthCheck.innerHTML = '<i class="bi bi-x-circle me-1"></i>Minimal 8 karakter';
                lengthCheck.style.color = '#6c757d';
            }

            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 25;
                uppercaseCheck.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>Huruf besar';
                uppercaseCheck.style.color = '#198754';
            } else {
                uppercaseCheck.innerHTML = '<i class="bi bi-x-circle me-1"></i>Huruf besar';
                uppercaseCheck.style.color = '#6c757d';
            }

            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 25;
                lowercaseCheck.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>Huruf kecil';
                lowercaseCheck.style.color = '#198754';
            } else {
                lowercaseCheck.innerHTML = '<i class="bi bi-x-circle me-1"></i>Huruf kecil';
                lowercaseCheck.style.color = '#6c757d';
            }

            // Number check
            if (/[0-9]/.test(password)) {
                strength += 25;
                numberCheck.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>Angka';
                numberCheck.style.color = '#198754';
            } else {
                numberCheck.innerHTML = '<i class="bi bi-x-circle me-1"></i>Angka';
                numberCheck.style.color = '#6c757d';
            }

            // Update progress bar
            strengthBar.style.width = strength + '%';

            if (strength === 0) {
                strengthBar.className = 'progress-bar';
                strengthText.textContent = 'Belum dimasukkan';
                strengthText.style.color = '#6c757d';
            } else if (strength <= 25) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Sangat Lemah';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 50) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Lemah';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 75) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Sedang';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Kuat';
                strengthText.style.color = '#198754';
            }
        });
    }

    // Password match checker
    const confirmPassword = document.getElementById('new_password_confirmation');
    const matchFeedback = document.getElementById('passwordMatchFeedback');

    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            const password = document.getElementById('new_password').value;

            if (this.value && this.value !== password) {
                this.classList.add('is-invalid');
                matchFeedback.innerHTML = 'Password tidak cocok';
                matchFeedback.style.display = 'block';
                matchFeedback.style.color = '#dc3545';
            } else if (this.value && this.value === password) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                matchFeedback.style.display = 'none';
            } else {
                this.classList.remove('is-invalid', 'is-valid');
                matchFeedback.style.display = 'none';
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .password-card {
        transition: all 0.2s;
        background: white;
    }

    .password-card:hover {
        border-color: rgba(34, 197, 94, 0.2) !important;
    }

    .progress {
        background-color: #F0F5F0;
        border-radius: 100px;
    }

    .form-control:focus {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .form-control.is-valid {
        border-color: #198754;
        background-image: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    /* DARK MODE */
    [data-bs-theme="dark"] .password-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1A1A2C !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FBF8"] {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .input-group-text {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
    }

    [data-bs-theme="dark"] .form-control:focus {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    [data-bs-theme="dark"] .progress {
        background-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .text-secondary {
        color: #9CA3AF !important;
    }

    [data-bs-theme="dark"] .bg-success.bg-opacity-10 {
        background: rgba(34, 197, 94, 0.2) !important;
    }
</style>
@endpush
