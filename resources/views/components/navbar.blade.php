@props(['transparent' => false])

<nav class="navbar navbar-expand-lg sticky-top {{ $transparent ? 'navbar-transparent' : '' }}"
     style="{{ $transparent ? 'background: transparent !important; backdrop-filter: none;' : 'background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.02);' }}">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <span class="gradient-text" style="color: #22c55e;">WAKANDE</span>
        </a>

        {{-- Mobile Toggle --}}
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link p-0 border-0 d-lg-none me-2" id="mobile-theme-toggle" style="color: var(--bs-body-color);">
                <i class="bi bi-sun-fill fs-5"></i>
            </button>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        {{-- Main Navigation --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- Menu Tengah --}}
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('items.create') ? 'active' : '' }}"
       href="{{ route('items.create') }}">
        Upload
    </a>
</li>
            </ul>

            {{-- Bagian Kanan --}}
            <div class="d-flex align-items-center gap-2">
                @guest
                    {{-- Guest: Login & Register --}}
                    <a href="{{ route('login') }}" class="btn btn-sm rounded-5 px-3 py-1"
                       style="border: 1px solid #EDF2F0; color: #1A2A24;">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-sm rounded-5 px-3 py-1"
                       style="background: #22c55e; color: white; border: none;">
                        Register
                    </a>
                @else
                    {{-- User sudah login: Upload + Profile --}}

                    {{-- TOMBOL UPLOAD (Trigger Modal) --}}
                    <button type="button" class="btn btn-sm rounded-5 px-3 py-1 d-flex align-items-center"
                            style="background: #22c55e; color: white; border: none;"
                            data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="bi bi-cloud-upload me-1"></i>
                        <span>Upload</span>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div class="dropdown">
                        <button class="btn p-0 border-0 d-flex align-items-center shadow-none"
                                type="button"
                                id="profileDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                     alt="Avatar"
                                     class="rounded-circle"
                                     width="32"
                                     height="32"
                                     style="object-fit: cover; border: 2px solid rgba(34, 197, 94, 0.2);">
                            @else
                                <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle"
                                     style="width: 32px; height: 32px; background: #22c55e;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end rounded-4 py-2 mt-2 shadow-lg border-0"
                            aria-labelledby="profileDropdown"
                            style="min-width: 200px;">
                            <div class="px-3 py-1">
                                <p class="fw-semibold small mb-0">{{ Auth::user()->name }}</p>
                                <small class="text-secondary" style="font-size: 0.7rem;">{{ Auth::user()->email }}</small>
                            </div>
                            <div class="dropdown-divider my-1"></div>
                            <li><a href="{{ route('dashboard') }}" class="dropdown-item py-1 small"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="dropdown-item py-1 small"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a href="{{ route('transactions.index') }}" class="dropdown-item py-1 small"><i class="bi bi-credit-card me-2"></i>Transaksi</a></li>
                            <div class="dropdown-divider my-1"></div>
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-item py-1 small text-danger"
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
