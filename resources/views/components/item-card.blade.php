@props(['item'])

<div class="col">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
        <!-- Image Container -->
        <div class="position-relative" style="padding-top: 75%; overflow: hidden;">
            @php
                // Pastikan images dalam bentuk array
                $images = $item->images ?? [];
                if (is_string($images)) {
                    $images = json_decode($images, true) ?? [];
                }

                // Ambil gambar pertama atau default
                $firstImage = !empty($images) ? $images[0] : null;
                $imageUrl = $firstImage ? Storage::url($firstImage) : asset('images/default-item.png');
                $status = $item->status;
            @endphp

            <img src="{{ $imageUrl }}"
                 alt="{{ $item->name }}"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit: cover; transition: transform 0.3s ease;"
                 loading="lazy"
                 onerror="this.src='{{ asset('images/default-item.png') }}'"
                 onmouseover="this.style.transform='scale(1.05)'"
                 onmouseout="this.style.transform='scale(1)'">

            <!-- Badges -->
            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                @if($item->type === 'gift')
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        <i class="bi bi-gift me-1"></i> Gratis
                    </span>
                @else
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="bi bi-tag me-1"></i> Dijual
                    </span>
                @endif

                @if($status !== 'approved')
                    <span class="badge bg-warning rounded-pill px-3 py-2">
                        <i class="bi bi-clock me-1"></i> {{ ucfirst($status) }}
                    </span>
                @endif
            </div>

            <!-- Category Badge -->
            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm">
                    <i class="bi bi-book me-1"></i> {{ $item->category_label }}
                </span>
            </div>

            <!-- Condition Badge -->
            <div class="position-absolute bottom-0 start-0 m-3">
                <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i> {{ $item->condition_label }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="card-title fw-bold mb-0" style="font-size: 1rem;">
                    {{ Str::limit($item->name, 50) }}
                </h6>
                @auth
                    <button class="btn p-0 border-0 wishlist-btn"
                            data-item-id="{{ $item->id }}"
                            style="color: {{ isset($item->is_wishlisted) && $item->is_wishlisted ? '#dc3545' : 'var(--bs-secondary)' }};">
                        <i class="bi {{ isset($item->is_wishlisted) && $item->is_wishlisted ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                    </button>
                @endauth
            </div>

            <!-- Legacy Message Preview -->
            <div class="mb-3 p-3 rounded-3" style="background: rgba(102, 126, 234, 0.05); border-left: 3px solid #667eea;">
                <p class="small text-secondary mb-0" style="line-height: 1.4;">
                    <i class="bi bi-quote me-1" style="color: #667eea;"></i>
                    {{ Str::limit($item->legacy_message, 60) }}
                </p>
            </div>

            <!-- Price & Seller -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @if($item->type === 'sale')
                        <span class="fw-bold" style="color: #667eea; font-size: 1.1rem;">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </span>
                        <span class="text-secondary small ms-1">
                            + admin Rp1.000
                        </span>
                    @else
                        <span class="fw-bold text-success">Gratis</span>
                    @endif
                </div>
                <div class="text-end">
                    <small class="text-secondary d-block">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Str::limit($item->user->name, 15) }}
                    </small>
                    <small class="text-secondary">
                        <i class="bi bi-building me-1"></i>
                        {{ Str::limit($item->user->school ?? 'Sekolah', 20) }}
                    </small>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-3">
                <a href="{{ route('catalog.show', $item->id) }}"
                   class="btn w-100 rounded-pill"
                   style="background: rgba(102, 126, 234, 0.1); color: #667eea; border: 1px solid rgba(102, 126, 234, 0.2);">
                    <i class="bi bi-eye me-1"></i> Lihat Detail
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Views & Timestamp -->
        <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
            <div class="d-flex justify-content-between small text-secondary">
                <span>
                    <i class="bi bi-eye me-1"></i> {{ $item->views_count }} views
                </span>
                <span>
                    <i class="bi bi-clock me-1"></i> {{ $item->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();

            @auth
                const itemId = this.dataset.itemId;
                const icon = this.querySelector('i');

                try {
                    const response = await fetch(`/wishlist/toggle/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (data.status === 'added') {
                            icon.className = 'bi bi-heart-fill fs-5';
                            this.style.color = '#dc3545';
                        } else {
                            icon.className = 'bi bi-heart fs-5';
                            this.style.color = 'var(--bs-secondary)';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        });
    });
</script>
@endpush
