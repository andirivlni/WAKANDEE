@extends('layouts.guest')

@section('title', 'Register - WAKANDE')
@section('subtitle', 'Mulai perjalanan berbagimu')

@section('content')
<div class="auth-form">
    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold text-secondary mb-2">
                <i class="bi bi-person me-1"></i>Nama Lengkap
            </label>
            <input type="text"
                   class="form-control auth-input @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   placeholder="Contoh: John Doe"
                   required
                   autofocus
                   autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-secondary mb-2">
                <i class="bi bi-envelope me-1"></i>Email @belajar.id
            </label>
            <input type="email"
                   class="form-control auth-input @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="nama@belajar.id"
                   required
                   autocomplete="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="valid-feedback">
                    <i class="bi bi-check-circle"></i> Email valid
                </div>
            @enderror
            <small class="text-secondary mt-2 d-block">
                <i class="bi bi-info-circle"></i> Wajib menggunakan email belajar.id
            </small>
        </div>

        <!-- School & Grade Row -->
        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <label for="school" class="form-label small fw-semibold text-secondary mb-2">
                    <i class="bi bi-building me-1"></i>Asal Sekolah
                </label>
                <input type="text"
                       class="form-control auth-input @error('school') is-invalid @enderror"
                       id="school"
                       name="school"
                       value="{{ old('school') }}"
                       placeholder="Contoh: SMAN 1 Balikpapan"
                       required>
                @error('school')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="grade" class="form-label small fw-semibold text-secondary mb-2">
                    <i class="bi bi-book me-1"></i>Kelas
                </label>
                <select class="form-select auth-input @error('grade') is-invalid @enderror"
                        id="grade"
                        name="grade"
                        required>
                    <option value="" disabled {{ old('grade') ? '' : 'selected' }}>Pilih kelas</option>
                    <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>Kelas 10</option>
                    <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>Kelas 11</option>
                    <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>Kelas 12</option>
                    <option value="alumni" {{ old('grade') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>
                @error('grade')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Phone Field (Optional) -->
        <div class="mb-3">
            <label for="phone" class="form-label small fw-semibold text-secondary mb-2">
                <i class="bi bi-telephone me-1"></i>Nomor WhatsApp <span class="fw-normal text-secondary">(opsional)</span>
            </label>
            <input type="tel"
                   class="form-control auth-input @error('phone') is-invalid @enderror"
                   id="phone"
                   name="phone"
                   value="{{ old('phone') }}"
                   placeholder="Contoh: 081234567890">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-secondary mt-2 d-block">
                <i class="bi bi-info-circle"></i> Untuk keperluan koordinasi serah terima barang
            </small>
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label small fw-semibold text-secondary mb-2">
                <i class="bi bi-lock me-1"></i>Password
            </label>
            <div class="position-relative">
                <input type="password"
                       class="form-control auth-input @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Minimal 8 karakter"
                       required
                       autocomplete="new-password">
                <button type="button"
                        class="btn position-absolute end-0 top-50 translate-middle-y border-0"
                        style="right: 10px !important;"
                        onclick="togglePassword('password', 'toggleIcon')">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="password-strength mt-2 d-none" id="passwordStrength">
                <div class="d-flex align-items-center">
                    <div class="grow me-2">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" id="strengthBar" style="width: 0%;"></div>
                        </div>
                    </div>
                    <small class="text-secondary" id="strengthText">Kekuatan password</small>
                </div>
                <ul class="list-unstyled mt-2 small text-secondary">
                    <li id="lengthCheck"><i class="bi bi-x-circle me-1"></i> Minimal 8 karakter</li>
                    <li id="letterCheck"><i class="bi bi-x-circle me-1"></i> Huruf dan angka</li>
                </ul>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label small fw-semibold text-secondary mb-2">
                <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password
            </label>
            <div class="position-relative">
                <input type="password"
                       class="form-control auth-input"
                       id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Masukkan password yang sama"
                       required
                       autocomplete="new-password">
                <button type="button"
                        class="btn position-absolute end-0 top-50 translate-middle-y border-0"
                        style="right: 10px !important;"
                        onclick="togglePassword('password_confirmation', 'toggleConfirmIcon')">
                    <i class="bi bi-eye-slash" id="toggleConfirmIcon"></i>
                </button>
            </div>
            <div class="invalid-feedback" id="passwordMatchFeedback"></div>
        </div>

        <!-- Terms & Conditions -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                <label class="form-check-label small text-secondary" for="terms">
                    Saya menyetujui <a href="#" class="text-decoration-none" style="color: #22c55e;">Syarat & Ketentuan</a>
                    dan <a href="#" class="text-decoration-none" style="color: #22c55e;">Kebijakan Privasi</a> WAKANDE
                </label>
                <div class="invalid-feedback">
                    Anda harus menyetujui syarat & ketentuan
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-auth mb-4">
            <i class="bi bi-person-plus me-2"></i>Daftar Akun
        </button>

        <!-- Login Link -->
        <div class="text-center">
            <span class="text-secondary small">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-decoration-none small fw-semibold ms-2" style="color: #22c55e;">
                Masuk sekarang <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(inputId, iconId) {
        const password = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (password.type === 'password') {
            password.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        } else {
            password.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        }
    }

    // Password strength checker
    const password = document.getElementById('password');
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const lengthCheck = document.getElementById('lengthCheck');
    const letterCheck = document.getElementById('letterCheck');

    if (password) {
        password.addEventListener('focus', () => {
            strengthDiv.classList.remove('d-none');
        });

        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;

            // Length check
            if (val.length >= 8) {
                strength += 50;
                lengthCheck.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Minimal 8 karakter';
                lengthCheck.style.color = '#198754';
            } else {
                lengthCheck.innerHTML = '<i class="bi bi-x-circle me-1"></i> Minimal 8 karakter';
                lengthCheck.style.color = '#6c757d';
            }

            // Letter & number check
            if (/[a-zA-Z]/.test(val) && /[0-9]/.test(val)) {
                strength += 50;
                letterCheck.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Huruf dan angka';
                letterCheck.style.color = '#198754';
            } else {
                letterCheck.innerHTML = '<i class="bi bi-x-circle me-1"></i> Huruf dan angka';
                letterCheck.style.color = '#6c757d';
            }

            // Update progress bar
            strengthBar.style.width = strength + '%';

            if (strength <= 50) {
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
    const passwordConfirm = document.getElementById('password_confirmation');
    const matchFeedback = document.getElementById('passwordMatchFeedback');

    if (passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (this.value !== password.value) {
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

    // Auto-detect @belajar.id
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            let email = this.value;
            if (email && !email.includes('@')) {
                this.value = email + '@belajar.id';
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

                // Custom password match validation
                const password = document.getElementById('password');
                const passwordConfirm = document.getElementById('password_confirmation');

                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.classList.add('is-invalid');
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
    .auth-input.is-valid {
        background-image: none;
        border-color: #198754;
    }

    .progress {
        background-color: rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="dark"] .progress {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .btn-auth {
        position: relative;
        overflow: hidden;
    }

    .btn-auth::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .btn-auth:hover::after {
        width: 300px;
        height: 300px;
    }

    /* Dark mode adjustments */
    [data-bs-theme="dark"] .auth-input {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    [data-bs-theme="dark"] .auth-input:focus {
        background: rgba(26, 26, 44, 0.9);
        border-color: #22c55e;
    }

    [data-bs-theme="dark"] .form-check-input {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>
@endpush
@endsection
