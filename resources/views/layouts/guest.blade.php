<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0c10" media="(prefers-color-scheme: dark)">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'WAKANDE') - Ekosistem Sirkular Perlengkapan Sekolah</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/dark-mode.css'])
    @stack('styles')

    <style>
        :root {
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #0a0c10 0%, #1a1a2c 100%);
        }

        .auth-card {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }

        .glass-auth {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 32px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.02);
        }

        [data-bs-theme="dark"] .glass-auth {
            background: rgba(26, 26, 44, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .auth-input {
            border-radius: 16px;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        [data-bs-theme="dark"] .auth-input {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        [data-bs-theme="dark"] .auth-input:focus {
            background: rgba(26, 26, 44, 0.9);
            border-color: #667eea;
        }

        .btn-auth {
            border-radius: 100px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }

        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #6c757d;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="dark"] .auth-divider::before,
        [data-bs-theme="dark"] .auth-divider::after {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .auth-divider span {
            margin: 0 1rem;
            font-size: 0.875rem;
        }

        .back-home {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 100px;
            padding: 0.5rem 1rem;
            color: var(--bs-body-color);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .back-home:hover {
            background: white;
            color: #667eea;
        }

        [data-bs-theme="dark"] .back-home {
            background: rgba(26, 26, 44, 0.5);
            border-color: rgba(255, 255, 255, 0.05);
            color: white;
        }

        [data-bs-theme="dark"] .back-home:hover {
            background: #1a1a2c;
        }

        .theme-toggle-guest {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 100px;
            padding: 0.5rem 1rem;
            color: var(--bs-body-color);
            border: none;
            transition: all 0.2s;
        }

        .theme-toggle-guest:hover {
            background: white;
        }

        [data-bs-theme="dark"] .theme-toggle-guest {
            background: rgba(26, 26, 44, 0.5);
            border-color: rgba(255, 255, 255, 0.05);
            color: white;
        }

        [data-bs-theme="dark"] .theme-toggle-guest:hover {
            background: #1a1a2c;
        }

        @media (max-width: 576px) {
            body { padding: 16px; }
            .glass-auth { padding: 1.5rem; }
        }
    </style>
</head>

<body>
    <!-- Back to Home -->
    <a href="{{ url('/') }}" class="back-home">
        <i class="bi bi-arrow-left me-1"></i> Back to Home
    </a>

    <!-- Theme Toggle -->
    <button class="theme-toggle-guest" id="theme-toggle-guest">
        <i class="bi bi-sun-fill" id="light-icon-guest"></i>
        <i class="bi bi-moon-stars-fill" id="dark-icon-guest" style="display: none;"></i>
    </button>

    <!-- Auth Content -->
    <div class="auth-card">
        <div class="glass-auth">
            <div class="auth-header">
                <h2>WAKANDE</h2>
                <p>@yield('subtitle', 'Ekosistem Sirkular Perlengkapan Sekolah')</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 mb-4" style="background: rgba(25, 135, 84, 0.1);">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 mb-4" style="background: rgba(220, 53, 69, 0.1);">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        <div class="text-center mt-4 text-secondary small">
            © {{ date('Y') }} WAKANDE. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/js/theme.js'])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
