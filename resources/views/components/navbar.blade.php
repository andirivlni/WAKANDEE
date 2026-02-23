@props(['transparent' => false])

<nav class="navbar navbar-expand-lg sticky-top {{ $transparent ? 'navbar-transparent' : '' }}"
     style="{{ $transparent ? 'background: transparent !important; backdrop-filter: none;' : '' }}">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                WAKANDE
            </span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link p-0 border-0 d-lg-none me-2" id="mobile-theme-toggle" style="color: var(--bs-body-color);">
                <i class="bi bi-sun-fill fs-5"></i>
            </button>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active fw-semibold' : '' }}" href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid me-1"></i>Katalog
                    </a>
                </li>
                @auth
    <div class="dropdown">
        {{-- Perhatikan: Tambahkan atribut id, data-bs-toggle, dan aria-expanded --}}
        <button class="btn p-0 border-0 d-flex align-items-center shadow-none"
                type="button"
                id="profileDropdownMenu"
                data-bs-toggle="dropdown"
                data-bs-display="static"
                aria-expanded="false">

            @if(Auth::user()->profile_photo)
                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Avatar" class="rounded-circle" width="36" height="36" style="object-fit: cover; border: 2px solid rgba(102, 126, 234, 0.2);">
            @else
                <div class="avatar-circle d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
        </button>

        {{-- Gunakan class 'dropdown-menu-end' agar tidak terpotong layar kanan --}}
        <ul class="dropdown-menu dropdown-menu-end rounded-4 py-2 mt-2 shadow-lg border-0"
            aria-labelledby="profileDropdownMenu"
            style="min-width: 240px; z-index: 9999;">

            <div class="px-3 py-2">
                <p class="fw-bold mb-0 text-dark">{{ Auth::user()->name }}</p>
                <small class="text-secondary">{{ Auth::user()->email }}</small>
            </div>
            <div class="dropdown-divider"></div>

            <li><a href="{{ route('dashboard') }}" class="dropdown-item py-2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a href="{{ route('profile.edit') }}" class="dropdown-item py-2"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a href="{{ route('transactions.index') }}" class="dropdown-item py-2"><i class="bi bi-credit-card me-2"></i>Transaksi</a></li>

            <div class="dropdown-divider"></div>
            <li>
                <a href="{{ route('logout') }}" class="dropdown-item py-2 text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-nav-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </li>
        </ul>

        {{-- Form Logout dengan ID unik agar tidak bentrok --}}
        <form id="logout-nav-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
@endauth
            </ul>

            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-rounded">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-rounded">Register</a>
                @else
                    <div class="dropdown">
                        <button class="btn p-0 border-0 d-flex align-items-center shadow-none"
                                type="button"
                                id="dropdownProfile"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Avatar" class="rounded-circle" width="36" height="36" style="object-fit: cover; border: 2px solid rgba(102, 126, 234, 0.2);">
                            @else
                                <div class="avatar-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end rounded-4 py-2 mt-2 shadow-lg border-0" aria-labelledby="dropdownProfile" style="min-width: 240px;">
                            <div class="px-3 py-2">
                                <p class="fw-bold mb-0 text-dark">{{ Auth::user()->name }}</p>
                                <small class="text-secondary">{{ Auth::user()->email }}</small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <li><a href="{{ route('dashboard') }}" class="dropdown-item py-2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="dropdown-item py-2"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a href="{{ route('transactions.index') }}" class="dropdown-item py-2"><i class="bi bi-credit-card me-2"></i>Transaksi</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-item py-2 text-danger"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
