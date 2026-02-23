@extends('layouts.app')

@section('title', 'Ubah Password - WAKANDE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2">
                        <i class="bi bi-shield-lock me-2"></i>KEAMANAN
                    </span>
                </div>
                <h1 class="h2 fw-bold mb-3">Ubah Password</h1>
                <p class="text-secondary">
                    Pastikan passwordmu kuat dan tidak digunakan di platform lain
                </p>
                <a href="{{ route('profile.edit') }}" class="btn btn-link text-decoration-none" style="color: #667eea;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Edit Profil
                </a>
            </div>

            <!-- Password Form -->
            <div class="password-card p-4 p-md-5 rounded-4">
                <form action="{{ route('profile.password') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold">
                            Password Saat Ini <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                <i class="bi bi-lock text-secondary"></i>
                            </span>
                            <input type="password"
                                   class="form-control form-control-lg border-start-0 border-end-0 @error('current_password') is-invalid @enderror"
                                   id="current_password"
                                   name="current_password"
                                   placeholder="Masukkan password saat ini"
                                   required>
                            <button class="input-group-text bg-transparent border-start-0 rounded-end-4"
                                    type="button"
                                    onclick="togglePassword('current_password', 'currentToggleIcon')">
                                <i class="bi bi-eye-slash" id="currentToggleIcon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="new_password" class="form-label fw-semibold">
                            Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                <i class="bi bi-shield-lock text-secondary"></i>
                            </span>
                            <input type="password"
                                   class="form-control form-control-lg border-start-0 border-end-0 @error('new_password') is-invalid @enderror"
                                   id="new_password"
                                   name="new_password"
                                   placeholder="Minimal 8 karakter"
                                   required>
                            <button class="input-group-text bg-transparent border-start-0 rounded-end-4"
                                    type="button"
                                    onclick="togglePassword('new_password', 'newToggleIcon')">
                                <i class="bi bi-eye-slash" id="newToggleIcon"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- Password Strength Meter -->
                        <div class="password-strength mt-3" id="passwordStrength">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-secondary">Kekuatan Password</small>
                                <small class="fw-semibold" id="strengthText">Belum dimasukkan</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" id="strengthBar" style="width: 0%;"></div>
                            </div>
                            <div class="row mt-3 small">
                                <div class="col-6">
                                    <span id="lengthCheck" class="text-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Minimal 8 karakter
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="uppercaseCheck" class="text-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Huruf besar
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="lowercaseCheck" class="text-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Huruf kecil
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="numberCheck" class="text-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Angka
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-5">
                        <label for="new_password_confirmation" class="form-label fw-semibold">
                            Konfirmasi Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 rounded-start-4">
                                <i class="bi bi-check2-circle text-secondary"></i>
                            </span>
                            <input type="password"
                                   class="form-control form-control-lg border-start-0 border-end-0"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   placeholder="Masukkan password yang sama"
                                   required>
                            <button class="input-group-text bg-transparent border-start-0 rounded-end-4"
                                    type="button"
                                    onclick="togglePassword('new_password_confirmation', 'confirmToggleIcon')">
                                <i class="bi bi-eye-slash" id="confirmToggleIcon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="passwordMatchFeedback"></div>
                    </div>

                    <!-- Password Tips -->
                    <div class="password-tips p-4 rounded-4 mb-4" style="background: rgba(102,126,234,0.02);">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-lightbulb me-2" style="color: #667eea;"></i>
                            Tips Password Aman
                        </h6>
                        <ul class="small text-secondary mb-0" style="padding-left: 1rem;">
                            <li class="mb-1">Gunakan kombinasi huruf besar, huruf kecil, dan angka</li>
                            <li class="mb-1">Jangan gunakan password yang sama dengan akun lain</li>
                            <li class="mb-1">Hindari informasi pribadi (tanggal lahir, nama, dll)</li>
                            <li>Ganti password secara berkala</li>
                        </ul>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-rounded px-5 py-3 grow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="bi bi-shield-check me-2"></i>Update Password
                        </button>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="text-center mt-4">
                <div class="d-inline-flex align-items-center gap-2 p-3 rounded-4" style="background: rgba(102,126,234,0.02);">
                    <i class="bi bi-shield-check text-success"></i>
                    <span class="small text-secondary">
                        Akunmu dilindungi dengan enkripsi SSL
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

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
                strengthBar.style.width = '0%';
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

            if (this.value !== password) {
                this.classList.add('is-invalid');
                matchFeedback.textContent = 'Password tidak cocok';
                matchFeedback.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                matchFeedback.style.display = 'none';
            }
        });
    }

    // Form validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                const password = document.getElementById('new_password').value;
                const confirm = document.getElementById('new_password_confirmation').value;

                if (!form.checkValidity() || password !== confirm) {
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
    .password-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .password-strength {
        transition: all 0.3s ease;
    }

    .progress {
        background-color: rgba(0,0,0,0.05);
        border-radius: 100px;
    }

    [data-bs-theme="dark"] .password-card {
        background: #1a1a2c;
        border-color: rgba(255,255,255,0.05);
    }

    [data-bs-theme="dark"] .progress {
        background-color: rgba(255,255,255,0.1);
    }

    [data-bs-theme="dark"] .password-tips {
        background: rgba(255,255,255,0.02) !important;
    }

    .input-group-text {
        background: transparent;
    }

    .form-control.is-valid {
        border-color: #198754;
        background-image: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }
</style>
@endpush
@endsection
