@props(['position' => 'inline', 'size' => 'md'])

@php
    $sizeClass = match($size) {
        'sm' => 'fs-6',
        'lg' => 'fs-4',
        default => 'fs-5',
    };

    $buttonClass = match($position) {
        'fixed' => 'theme-toggle-fixed position-fixed bottom-0 end-0 m-4 shadow-lg',
        'card' => 'btn btn-outline-secondary rounded-circle p-3',
        default => 'btn btn-link p-0 border-0',
    };
@endphp

@if($position === 'fixed')
    <div class="{{ $buttonClass }}" style="z-index: 1050; background: var(--bs-body-bg); border: 1px solid rgba(var(--bs-secondary-rgb), 0.1); border-radius: 100px; padding: 0.75rem 1.25rem;">
        <button class="btn p-0 border-0 d-flex align-items-center gap-2" id="theme-toggle-{{ $position }}" style="color: var(--bs-body-color); background: transparent;">
            <i class="bi bi-sun-fill {{ $sizeClass }}" id="light-icon-{{ $position }}"></i>
            <i class="bi bi-moon-stars-fill {{ $sizeClass }}" id="dark-icon-{{ $position }}" style="display: none;"></i>
            <span class="small" id="theme-text-{{ $position }}">Light Mode</span>
        </button>
    </div>
@elseif($position === 'card')
    <button class="{{ $buttonClass }}" id="theme-toggle-{{ $position }}" style="color: var(--bs-body-color); width: 48px; height: 48px;">
        <i class="bi bi-sun-fill {{ $sizeClass }}" id="light-icon-{{ $position }}"></i>
        <i class="bi bi-moon-stars-fill {{ $sizeClass }}" id="dark-icon-{{ $position }}" style="display: none;"></i>
    </button>
@else
    <button class="{{ $buttonClass }}" id="theme-toggle-{{ $position }}" style="color: var(--bs-body-color);">
        <i class="bi bi-sun-fill {{ $sizeClass }}" id="light-icon-{{ $position }}"></i>
        <i class="bi bi-moon-stars-fill {{ $sizeClass }}" id="dark-icon-{{ $position }}" style="display: none;"></i>
    </button>
@endif

@push('scripts')
<script>
    (function() {
        const themeToggle = document.getElementById('theme-toggle-{{ $position }}');
        const lightIcon = document.getElementById('light-icon-{{ $position }}');
        const darkIcon = document.getElementById('dark-icon-{{ $position }}');
        const themeText = document.getElementById('theme-text-{{ $position }}');

        function setTheme(isDark) {
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');

            if (lightIcon && darkIcon) {
                lightIcon.style.display = isDark ? 'none' : 'inline-block';
                darkIcon.style.display = isDark ? 'inline-block' : 'none';
            }

            if (themeText) {
                themeText.textContent = isDark ? 'Dark Mode' : 'Light Mode';
            }

            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        if (themeToggle) {
            // Check local storage
            const savedTheme = localStorage.getItem('theme');
            const isDark = savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);
            setTheme(isDark);

            // Toggle on click
            themeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                setTheme(currentTheme === 'light');
            });
        }
    })();
</script>
@endpush
