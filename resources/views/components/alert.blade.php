@props(['type' => 'info', 'dismissible' => true, 'icon' => null])

@php
    $alertClasses = [
        'success' => [
            'bg' => 'rgba(25, 135, 84, 0.1)',
            'text' => '#198754',
            'border' => 'rgba(25, 135, 84, 0.2)',
            'icon' => 'bi-check-circle-fill'
        ],
        'danger' => [
            'bg' => 'rgba(220, 53, 69, 0.1)',
            'text' => '#dc3545',
            'border' => 'rgba(220, 53, 69, 0.2)',
            'icon' => 'bi-exclamation-circle-fill'
        ],
        'warning' => [
            'bg' => 'rgba(255, 193, 7, 0.1)',
            'text' => '#ffc107',
            'border' => 'rgba(255, 193, 7, 0.2)',
            'icon' => 'bi-exclamation-triangle-fill'
        ],
        'info' => [
            'bg' => 'rgba(13, 202, 240, 0.1)',
            'text' => '#0dcaf0',
            'border' => 'rgba(13, 202, 240, 0.2)',
            'icon' => 'bi-info-circle-fill'
        ],
        'primary' => [
            'bg' => 'rgba(34, 197, 94, 0.1)',
            'text' => '#22c55e',
            'border' => 'rgba(34, 197, 94, 0.2)',
            'icon' => 'bi-bell-fill'
        ],
    ];

    $alertStyle = $alertClasses[$type] ?? $alertClasses['info'];
    $iconClass = $icon ?? $alertStyle['icon'];
@endphp

<div class="alert alert-dismissible fade show rounded-4 border-0 mb-4"
     role="alert"
     style="background: {{ $alertStyle['bg'] }}; color: {{ $alertStyle['text'] }}; border: 1px solid {{ $alertStyle['border'] }};">

    <div class="d-flex align-items-center">
        <!-- Icon -->
        @if($iconClass)
            <i class="bi {{ $iconClass }} me-2 fs-5"></i>
        @endif

        <!-- Content -->
        <div class="grow">
            @if(isset($title))
                <strong class="d-block mb-1">{{ $title }}</strong>
            @endif
            {{ $slot }}
        </div>

        <!-- Close Button -->
        @if($dismissible)
            <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"
                    style="filter: {{ strpos($alertStyle['text'], '#') === 0 ? 'brightness(0.5)' : 'none' }};">
            </button>
        @endif
    </div>

    <!-- Progress Bar (optional) -->
    @if(isset($progress))
        <div class="progress mt-2" style="height: 4px; background: rgba(0,0,0,0.05);">
            <div class="progress-bar rounded-pill"
                 style="width: {{ $progress }}%; background: {{ $alertStyle['text'] }};"
                 role="progressbar"></div>
        </div>
    @endif
</div>

@once
@push('styles')
<style>
    .alert {
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-dismissible .btn-close {
        padding: 1rem;
    }
</style>
@endpush
@endonce
