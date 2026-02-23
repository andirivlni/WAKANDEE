<footer class="mt-5 py-5" style="background: var(--bs-body-bg); border-top: 1px solid rgba(var(--bs-secondary-rgb), 0.1);">
    <div class="container">
        <div class="row g-4">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <div class="mb-4">
                    <h4 class="fw-bold mb-3">
                        <span style="background: #22c55e; color: #22c55e;">
                            WAKANDE
                        </span>
                    </h4>
                    <p class="text-secondary mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                        Ekosistem sirkular perlengkapan sekolah. Warisan akademik untuk generasi berikutnya.
                        Berbasis di Balikpapan, penyangga IKN.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary hover-primary" style="transition: color 0.2s;">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="#" class="text-secondary hover-primary" style="transition: color 0.2s;">
                            <i class="bi bi-tiktok fs-5"></i>
                        </a>
                        <a href="#" class="text-secondary hover-primary" style="transition: color 0.2s;">
                            <i class="bi bi-twitter-x fs-5"></i>
                        </a>
                        <a href="#" class="text-secondary hover-primary" style="transition: color 0.2s;">
                            <i class="bi bi-linkedin fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-3">Explore</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/') }}" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('catalog.index') }}" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Katalog
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Cara Kerja
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-3">Support</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Kebijakan Privasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Syarat & Ketentuan
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-secondary text-decoration-none hover-link">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Kontak
                        </a>
                    </li>
                </ul>
            </div>

            <!-- PWA & Download -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-semibold mb-3">Download App</h6>
                <p class="text-secondary small mb-3">
                    Install WAKANDE sebagai aplikasi di perangkatmu untuk pengalaman yang lebih baik.
                </p>
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-outline-secondary btn-sm btn-rounded" onclick="window.location.href = '{{ asset('manifest.json') }}'">
                        <i class="bi bi-download me-1"></i> Install PWA
                    </button>
                    <button class="btn btn-outline-secondary btn-sm btn-rounded" id="share-button">
                        <i class="bi bi-share me-1"></i> Bagikan
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="small text-secondary">
                        <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.75rem;"></i>
                        Available on
                    </div>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">iOS</span>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">Android</span>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">Web</span>
                </div>
            </div>
        </div>

        <hr class="my-4" style="opacity: 0.05;">

        <!-- Bottom Bar -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="text-center text-md-start text-secondary small">
                    © {{ date('Y') }} WAKANDE. Made with <i class="bi bi-heart-fill text-danger"></i> for Balikpapan.
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center text-md-end text-secondary small">
                    <span>v1.0.0</span>
                    <span class="mx-2">•</span>
                    <span>Green City Initiative</span>
                    <span class="mx-2">•</span>
                    <span>Circular Economy</span>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
<script>
    document.getElementById('share-button')?.addEventListener('click', async function() {
        if (navigator.share) {
            try {
                await navigator.share({
                    title: 'WAKANDE',
                    text: 'Ekosistem sirkular perlengkapan sekolah',
                    url: window.location.origin,
                });
            } catch (err) {
                console.log('Share cancelled');
            }
        } else {
            alert('Copy link: ' + window.location.origin);
        }
    });
</script>
@endpush
