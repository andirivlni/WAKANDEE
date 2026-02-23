<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0c10" media="(prefers-color-scheme: dark)">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - WAKANDE</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/dark-mode.css', 'resources/css/admin.css'])
    @stack('styles')

    <style>
        :root {
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --admin-sidebar-width: 280px;
        }

        body {
            font-family: var(--font-sans);
            background-color: #f8fafc;
            color: #0a0c10;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        [data-bs-theme="dark"] body {
            background-color: #0a0c10;
            color: #f8fafc;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            padding: 2rem 1.5rem;
            z-index: 1000;
        }

        [data-bs-theme="dark"] .admin-sidebar {
            background: linear-gradient(135deg, #1a1a2c 0%, #16213e 100%);
        }

        .admin-main {
            flex: 1;
            margin-left: var(--admin-sidebar-width);
            padding: 2rem;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .admin-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
        }

        [data-bs-theme="dark"] .admin-card {
            background: #1a1a2c;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.04);
        }

        .badge-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            padding: 0.5rem 1rem;
            border-radius: 100px;
        }

        .badge-approved {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 0.5rem 1rem;
            border-radius: 100px;
        }

        .badge-rejected {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 0.5rem 1rem;
            border-radius: 100px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            height: 100%;
        }

        [data-bs-theme="dark"] .stat-card {
            background: #1a1a2c;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1050;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <div class="d-flex flex-column h-100">
                <div class="mb-4">
                    <h4 class="fw-bold mb-0">
                        <span style="color: white;">WAKANDE</span>
                    </h4>
                    <p class="small opacity-75 mt-2">Admin Panel</p>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Admin" class="rounded-circle me-3" width="48" height="48" style="object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                        @else
                            <div class="avatar-circle me-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="fw-semibold mb-0 text-white">{{ Auth::user()->name }}</p>
                            <p class="small opacity-75 mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="nav flex-column grow">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.moderation.index') }}" class="nav-link {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> Moderasi
                        @php
                            $pendingCount = \App\Models\Item::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Manajemen User
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i> Monitoring Transaksi
                    </a>
                </div>

                <div class="mt-auto pt-4">
                    <hr class="opacity-25">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-link text-white p-0" id="theme-toggle-admin">
                            <i class="bi bi-sun-fill" id="light-icon-admin"></i>
                            <i class="bi bi-moon-stars-fill" id="dark-icon-admin" style="display: none;"></i>
                        </button>
                        <a href="{{ route('home') }}" class="text-white text-decoration-none small" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Website
                        </a>
                        <a href="{{ route('logout') }}" class="text-white text-decoration-none small" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar Toggle -->
        <div class="position-fixed bottom-0 end-0 m-3 d-md-none" style="z-index: 1060;">
            <button class="btn btn-primary rounded-circle p-3 shadow" onclick="document.getElementById('adminSidebar').classList.toggle('show')" style="width: 56px; height: 56px;">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
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
    </div>

    {{-- @vite(['resources/js/app.js', 'resources/js/theme.js', 'resources/js/admin.js']) --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

    <script>
        // Mobile sidebar toggle
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = event.target.closest('.rounded-circle.p-3.shadow');

            if (window.innerWidth < 768) {
                if (!sidebar.contains(event.target) && !toggleBtn) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>
