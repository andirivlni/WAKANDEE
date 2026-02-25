@props(['type' => 'user']) {{-- user or admin --}}

@if($type === 'admin')
    <!-- Admin Sidebar -->
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
                    @if(request()->routeIs('admin.dashboard'))
                        <i class="bi bi-check-circle-fill ms-auto" style="font-size: 0.75rem;"></i>
                    @endif
                </a>

                <a href="{{ route('admin.moderation.index') }}" class="nav-link {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i> Moderasi
                    @php
                        $pendingCount = App\Models\Item::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-2 rounded-pill">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Manajemen User
                </a>

                <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> Monitoring Transaksi
                </a>

                <hr class="opacity-25 my-3">

                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Lihat Website
                </a>
            </div>

            <div class="mt-auto pt-4">
                <hr class="opacity-25">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-link text-white p-0" id="theme-toggle-admin">
                        <i class="bi bi-sun-fill" id="light-icon-admin" style="display: {{ session('theme', 'light') === 'light' ? 'inline-block' : 'none' }};"></i>
                        <i class="bi bi-moon-stars-fill" id="dark-icon-admin" style="display: {{ session('theme', 'light') === 'dark' ? 'inline-block' : 'none' }};"></i>
                    </button>
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
@else
    <!-- User Sidebar (Dashboard) -->
    <div class="user-sidebar">
        <div class="d-flex flex-column">
            <div class="text-center mb-4">
                @if(Auth::user()->profile_photo)
                    <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid rgba(34, 197, 94, 0.2);">
                @else
                    <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; background: #22c55e; font-size: 2rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <h6 class="fw-bold mb-1">{{ Auth::user()->name }}</h6>
                <p class="text-secondary small mb-0">{{ Auth::user()->email }}</p>
                <span class="badge bg-light text-dark mt-2 rounded-pill px-3 py-1">
                    <i class="bi bi-building me-1"></i> {{ Auth::user()->school ?? 'Sekolah' }}
                </span>
            </div>

            <hr class="opacity-25">

            <div class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <a href="{{ route('items.index') }}" class="nav-link {{ request()->routeIs('items.index') ? 'active' : '' }}">
                    <i class="bi bi-box"></i> Barang Saya
                    @php
                        $userItemsCount = App\Models\Item::where('user_id', Auth::id())->count();
                    @endphp
                    @if($userItemsCount > 0)
                        <span class="badge bg-secondary ms-2 rounded-pill">{{ $userItemsCount }}</span>
                    @endif
                </a>

                <a href="{{ route('items.create') }}" class="nav-link {{ request()->routeIs('items.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> Upload Barang
                </a>

                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> Transaksi Saya
                </a>

                <a href="{{ route('wishlist.index') }}" class="nav-link {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i> Wishlist
                    @php
                        $wishlistCount = App\Models\Wishlist::where('user_id', Auth::id())->count();
                    @endphp
                    @if($wishlistCount > 0)
                        <span class="badge bg-danger ms-2 rounded-pill">{{ $wishlistCount }}</span>
                    @endif
                </a>

                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> Profile
                </a>

                <hr class="opacity-25">

                <a href="{{ route('catalog.index') }}" class="nav-link">
                    <i class="bi bi-grid"></i> Jelajahi Katalog
                </a>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle-admin');
        if (!themeToggle) return;

        const lightIcon = document.getElementById('light-icon-admin');
        const darkIcon = document.getElementById('dark-icon-admin');

        // Get current theme from localStorage or default to light
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', currentTheme);

        // Update icons based on current theme
        if (currentTheme === 'dark') {
            lightIcon.style.display = 'none';
            darkIcon.style.display = 'inline-block';
        } else {
            lightIcon.style.display = 'inline-block';
            darkIcon.style.display = 'none';
        }

        // Toggle theme on button click
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            // Update HTML attribute
            document.documentElement.setAttribute('data-bs-theme', newTheme);

            // Save to localStorage
            localStorage.setItem('theme', newTheme);

            // Update icons
            if (newTheme === 'dark') {
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'inline-block';
            } else {
                lightIcon.style.display = 'inline-block';
                darkIcon.style.display = 'none';
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .admin-sidebar {
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: linear-gradient(180deg, #0B2A1F 0%, #1A4A35 100%);
        color: white;
        padding: 1.5rem 1rem;
        overflow-y: auto;
        z-index: 1000;
    }

    .admin-sidebar .nav-link {
        color: rgba(255, 255, 255, 0.7);
        padding: 0.75rem 1rem;
        margin: 0.25rem 0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .admin-sidebar .nav-link i {
        font-size: 1.1rem;
    }

    .admin-sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .admin-sidebar .nav-link.active {
        background: #22c55e;
        color: white;
    }

    .admin-sidebar .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    .user-sidebar {
        width: 260px;
        background: white;
        border-right: 1px solid #EDF2F0;
        padding: 1.5rem 1rem;
        height: 100vh;
        position: sticky;
        top: 0;
        overflow-y: auto;
    }

    .user-sidebar .nav-link {
        color: #4A5A54;
        padding: 0.75rem 1rem;
        margin: 0.25rem 0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .user-sidebar .nav-link i {
        font-size: 1.1rem;
        color: #22c55e;
    }

    .user-sidebar .nav-link:hover {
        background: #F8FBF8;
        color: #1A2A24;
    }

    .user-sidebar .nav-link.active {
        background: #22c55e;
        color: white;
    }

    .user-sidebar .nav-link.active i {
        color: white;
    }

    .user-sidebar .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        color: white;
    }

    /* Dark mode untuk user sidebar */
    [data-bs-theme="dark"] .user-sidebar {
        background: #1A1A2C;
        border-right-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .user-sidebar .nav-link {
        color: #9CA3AF;
    }

    [data-bs-theme="dark"] .user-sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.03);
        color: #E0E0E0;
    }

    [data-bs-theme="dark"] .user-sidebar .nav-link.active {
        background: #22c55e;
        color: white;
    }

    [data-bs-theme="dark"] .badge.bg-light {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #E0E0E0 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s;
        }
        .admin-sidebar.show {
            transform: translateX(0);
        }
    }
</style>
@endpush
