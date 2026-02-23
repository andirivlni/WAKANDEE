@extends('layouts.guest')

@section('title', 'Login - WAKANDE')
@section('subtitle', 'Masuk ke akun WAKANDE-mu')

@section('content')
<div class="auth-form">
    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-secondary mb-2">
                <i class="bi bi-envelope me-1"></i>Email @belajar.id
            </label>
            <div class="position-relative">
                <input type="email"
                       class="form-control auth-input @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@belajar.id"
                       required
                       autofocus
                       autocomplete="username">
                <div class="invalid-feedback">
                    @error('email')
                        {{ $message }}
                    @else
                        Email wajib diisi dengan domain @belajar.id
                    @enderror
                </div>
            </div>
            <small class="text-secondary mt-2 d-block">
                <i class="bi bi-info-circle"></i> Gunakan email belajar.id yang aktif
            </small>
        </div>

        <!-- Password Field -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="password" class="form-label small fw-semibold text-secondary mb-0">
                    <i class="bi bi-lock me-1"></i>Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none small" style="color: #22c55e;">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="position-relative">
                <input type="password"
                       class="form-control auth-input @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="••••••••"
                       required
                       autocomplete="current-password">
                <button type="button"
                        class="btn position-absolute end-0 top-50 translate-middle-y border-0"
                        style="right: 10px !important;"
                        onclick="togglePassword()">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-secondary" for="remember">
                    Ingat saya
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-auth mb-4">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>

        <!-- Register Link -->
        <div class="text-center">
            <span class="text-secondary small">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-decoration-none small fw-semibold ms-2" style="color: #22c55e;">
                Daftar sekarang <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword() {
        const password = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

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
</script>
@endpush

@push('styles')
<style>
    .auth-input.is-invalid {
        background-image: none;
        border-color: #dc3545;
    }

    .auth-input.is-valid {
        background-image: none;
        border-color: #198754;
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
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .btn-auth:hover::after {
        width: 300px;
        height: 300px;
    }
</style>
@endpush
@endsection
