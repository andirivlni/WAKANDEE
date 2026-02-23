<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'WAKANDE') - Ekosistem Sirkular</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/dark-mode.css'])
    @stack('styles')

    <style>
        :root {
            --font-sans: 'Inter', sans-serif;
            --primary-green: #4ade80;
            --light-green: #dcfce7;
        }

        body {
            font-family: var(--font-sans);
            -webkit-font-smoothing: antialiased;
            background-color: #ffffff;
        }

        .navbar {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1050;
            border-bottom: 1px solid rgba(74, 222, 128, 0.2);
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-green);
            color: #166534;
            font-weight: 600;
        }

        .gradient-text {
            color: var(--primary-green);
            font-weight: 800;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            z-index: 1100;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg sticky-top bg-white">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <span class="gradient-text">WAKANDE</span>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                                href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('catalog*') ? 'active' : '' }}"
                                href="{{ route('catalog.index') }}">Katalog</a></li>
                        @auth
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a
                                    class="nav-link {{ request()->routeIs('items.create') ? 'active' : '' }}"
                                    href="{{ route('items.create') }}">Upload</a></li>
                        @endauth
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        @guest
                            <a href="{{ route('login') }}"
                                class="btn btn-outline-success btn-sm rounded-pill px-4">Login</a>
                        @else
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                    role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                                    @if (Auth::user()->profile_photo)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                            class="rounded-circle border" width="32" height="32"
                                            style="object-fit: cover;">
                                    @else
                                        <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm border-0"
                                    aria-labelledby="userDropdown">
                                    <li>
                                        <h6 class="dropdown-header">Manage Account</h6>
                                    </li>
                                    <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i
                                                class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i
                                                class="bi bi-person me-2"></i>Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
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
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-alert="dismiss"></button>
                    </div>
                @endif

                @if (session('error'))
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


    <script>
        window.onload = function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            dropdownElementList.map(function(dropdownToggleEl) {
                const instance = bootstrap.Dropdown.getInstance(dropdownToggleEl);
                if (instance) {
                    instance.dispose();
                }
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        };
    </script>
</body>

</html>
