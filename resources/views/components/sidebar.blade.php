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
                        <i class="bi bi-sun-fill" id="light-icon-admin"></i>
                        <i class="bi bi-moon-stars-fill" id="dark-icon-admin" style="display: none;"></i>
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
                    <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid rgba(102, 126, 234, 0.2);">
                @else
                    <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 2rem;">
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
