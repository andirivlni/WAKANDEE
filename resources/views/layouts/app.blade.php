<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'WAKANDE') - Ekosistem Sirkular</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/dark-mode.css'])
    @stack('styles')

    <style>
        :root { --font-sans: 'Inter', sans-serif; }
        body { font-family: var(--font-sans); -webkit-font-smoothing: antialiased; background-color: #f8f9fa; }
        .navbar { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 1050; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .avatar-circle {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; font-weight: 600;
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dropdown-menu { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; z-index: 1100; }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg sticky-top bg-white">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <span class="gradient-text">WAKANDE</span>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('catalog*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">Katalog</a></li>
                        @auth
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('items.create') ? 'active' : '' }}" href="{{ route('items.create') }}">Upload</a></li>
                        @endauth
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Login</a>
                        @else
                            <div class="dropdown">
                                <button class="btn p-0 border-0 d-flex align-items-center dropdown-toggle shadow-none"
                                        type="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}" class="rounded-circle border" width="36" height="36" style="object-fit: cover;">
                                    @else
                                        <div class="avatar-circle">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                    @endif
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end mt-2 p-2 shadow" aria-labelledby="userDropdown">
                                    <li><h6 class="dropdown-header text-dark fw-bold">{{ Auth::user()->name }}</h6></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-alert="dismiss"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-alert="dismiss"></button>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    @vite(['resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Re-inisialisasi paksa jika dropdown macet
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
