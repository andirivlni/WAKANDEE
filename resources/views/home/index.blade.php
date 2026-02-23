@extends('layouts.app')

@section('title', 'WAKANDE - Ekosistem Sirkular Perlengkapan Sekolah')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden" style="min-height: 90vh; display: flex; align-items: center;">
    <!-- Background Pattern -->
    <div class="dot-pattern position-absolute w-100 h-100" style="opacity: 0.4;"></div>

    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <!-- Badge -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-4" style="background: rgba(102, 126, 234, 0.1); border: 1px solid rgba(102, 126, 234, 0.2);">
                    <i class="bi bi-gem" style="color: #667eea;"></i>
                    <span class="small fw-semibold" style="color: #667eea;">#WarisanAkademik</span>
                </div>

                <!-- Headline -->
                <h1 class="display-4 fw-bold mb-4" style="line-height: 1.2;">
                    Warisan Akademik untuk
                    <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Generasi Berikutnya
                    </span>
                </h1>

                <!-- Subheadline -->
                <p class="lead text-secondary mb-5" style="font-size: 1.2rem; max-width: 90%;">
                    Ekosistem sirkular pertama di Balikpapan yang menghubungkan kakak kelas dengan adik kelas untuk mewariskan perlengkapan sekolah.
                    Berbagi itu berkah, mewarisi itu bijak.
                </p>

                <!-- CTA Buttons -->
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-rounded px-5 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-search me-2"></i>Jelajahi Katalog
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                            <i class="bi bi-gift me-2"></i>Mulai Berbagi
                        </a>
                    @else
                        <a href="{{ route('items.create') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Barang
                        </a>
                    @endguest
                </div>

                <!-- Stats -->
                <div class="row g-4">
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="h3 fw-bold mb-1" style="color: #667eea;">{{ $stats['total_items'] ?? 0 }}+</span>
                            <span class="small text-secondary">Barang Tersedia</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="h3 fw-bold mb-1" style="color: #667eea;">{{ $stats['total_transactions'] ?? 0 }}+</span>
                            <span class="small text-secondary">Transaksi Sukses</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="h3 fw-bold mb-1" style="color: #667eea;">{{ $stats['total_students'] ?? 0 }}+</span>
                            <span class="small text-secondary">Siswa Terbantu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image/Illustration -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="position-relative">
                    <!-- Floating Cards -->
                    <div class="position-absolute top-0 start-0 translate-middle-y" style="animation: float 6s ease-in-out infinite;">
                        <div class="glass-card p-3 d-flex align-items-center gap-3">
                            <div class="avatar-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-book text-white"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0">Buku Matematika</p>
                                <small class="text-secondary">Gratis • 2 jam lalu</small>
                            </div>
                        </div>
                    </div>

                    <div class="position-absolute top-50 end-0" style="animation: float 8s ease-in-out infinite;">
                        <div class="glass-card p-3 d-flex align-items-center gap-3">
                            <div class="avatar-circle" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);">
                                <i class="bi bi-bag text-white"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0">Seragam Putih Abu</p>
                                <small class="text-secondary">Rp 50.000 • 5 jam lalu</small>
                            </div>
                        </div>
                    </div>

                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5" style="animation: float 10s ease-in-out infinite;">
                        <div class="glass-card p-3 d-flex align-items-center gap-3">
                            <div class="avatar-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-calculator text-white"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0">Kalkulator Ilmiah</p>
                                <small class="text-secondary">Hibah • 1 hari lalu</small>
                            </div>
                        </div>
                    </div>

                    <!-- Main Illustration -->
                    <div class="text-center">
                        <img src="{{ asset('images/hero-illustration.svg') }}" alt="Hero Illustration" class="img-fluid" style="max-width: 120%;" onerror="this.style.display='none'">
                        <div class="hero-circle position-absolute top-50 start-50 translate-middle" style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%); border-radius: 50%; z-index: -1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-light text-dark rounded-pill px-4 py-2 mb-3">KATEGORI</span>
            <h2 class="fw-bold mb-3">Temukan Kebutuhan Akademikmu</h2>
            <p class="text-secondary" style="max-width: 600px; margin: 0 auto;">
                Dari buku hingga alat praktikum, semua bisa kamu dapatkan secara gratis atau harga terjangkau
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="category-card text-center p-4 rounded-4 h-100" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%); border: 1px solid rgba(102,126,234,0.1); transition: transform 0.3s;">
                    <div class="category-icon mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 30px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-journal-bookmark-fill fs-1 text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Buku</h5>
                    <p class="small text-secondary mb-3">{{ $categories['buku'] ?? 0 }}+ barang tersedia</p>
                    <a href="{{ route('catalog.index', ['category' => 'buku']) }}" class="btn btn-link text-decoration-none p-0" style="color: #667eea;">
                        Jelajahi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="category-card text-center p-4 rounded-4 h-100" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%); border: 1px solid rgba(102,126,234,0.1); transition: transform 0.3s;">
                    <div class="category-icon mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 30px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-bag-check-fill fs-1 text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Seragam</h5>
                    <p class="small text-secondary mb-3">{{ $categories['seragam'] ?? 0 }}+ barang tersedia</p>
                    <a href="{{ route('catalog.index', ['category' => 'seragam']) }}" class="btn btn-link text-decoration-none p-0" style="color: #667eea;">
                        Jelajahi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="category-card text-center p-4 rounded-4 h-100" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%); border: 1px solid rgba(102,126,234,0.1); transition: transform 0.3s;">
                    <div class="category-icon mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 30px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-tools fs-1 text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Alat Praktikum</h5>
                    <p class="small text-secondary mb-3">{{ $categories['alat_praktikum'] ?? 0 }}+ barang tersedia</p>
                    <a href="{{ route('catalog.index', ['category' => 'alat_praktikum']) }}" class="btn btn-link text-decoration-none p-0" style="color: #667eea;">
                        Jelajahi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="category-card text-center p-4 rounded-4 h-100" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%); border: 1px solid rgba(102,126,234,0.1); transition: transform 0.3s;">
                    <div class="category-icon mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 30px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-grid-3x3-gap-fill fs-1 text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Lainnya</h5>
                    <p class="small text-secondary mb-3">{{ $categories['lainnya'] ?? 0 }}+ barang tersedia</p>
                    <a href="{{ route('catalog.index', ['category' => 'lainnya']) }}" class="btn btn-link text-decoration-none p-0" style="color: #667eea;">
                        Jelajahi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5" style="background: rgba(102, 126, 234, 0.02);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-light text-dark rounded-pill px-4 py-2 mb-3">CARA KERJA</span>
            <h2 class="fw-bold mb-3">Gampang, Cepat, Berkah</h2>
            <p class="text-secondary" style="max-width: 600px; margin: 0 auto;">
                Hanya 4 langkah sederhana untuk mulai mewariskan atau mendapatkan perlengkapan sekolah
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4 h-100">
                    <div class="step-number mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: white; border-radius: 20px; box-shadow: 0 8px 16px rgba(102,126,234,0.1);">
                        <span class="h3 fw-bold mb-0" style="color: #667eea;">1</span>
                    </div>
                    <h5 class="fw-bold mb-3">Upload Barang</h5>
                    <p class="small text-secondary">Foto barang, tulis deskripsi, dan jangan lupa tambahkan Legacy Message untuk adik kelas</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4 h-100">
                    <div class="step-number mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: white; border-radius: 20px; box-shadow: 0 8px 16px rgba(102,126,234,0.1);">
                        <span class="h3 fw-bold mb-0" style="color: #667eea;">2</span>
                    </div>
                    <h5 class="fw-bold mb-3">Moderasi Admin</h5>
                    <p class="small text-secondary">Admin akan memverifikasi barangmu dalam 1x24 jam untuk memastikan kualitas</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4 h-100">
                    <div class="step-number mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: white; border-radius: 20px; box-shadow: 0 8px 16px rgba(102,126,234,0.1);">
                        <span class="h3 fw-bold mb-0" style="color: #667eea;">3</span>
                    </div>
                    <h5 class="fw-bold mb-3">Temukan & Transaksi</h5>
                    <p class="small text-secondary">Cari barang yang kamu butuhkan, pilih metode pembayaran QRIS atau COD</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4 h-100">
                    <div class="step-number mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: white; border-radius: 20px; box-shadow: 0 8px 16px rgba(102,126,234,0.1);">
                        <span class="h3 fw-bold mb-0" style="color: #667eea;">4</span>
                    </div>
                    <h5 class="fw-bold mb-3">Serah Terima</h5>
                    <p class="small text-secondary">Ambil barang di Drop-off Point sekolah, transaksi selesai, legacy berlanjut</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Items -->
@if(isset($featured_items) && $featured_items->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="badge bg-light text-dark rounded-pill px-4 py-2 mb-3">FEATURED</span>
                <h2 class="fw-bold mb-0">Barang Populer</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-link text-decoration-none" style="color: #667eea;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featured_items as $item)
                <div class="col-lg-3 col-md-6">
                    <x-item-card :item="$item" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Legacy Message Spotlight -->
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-white text-dark rounded-pill px-4 py-2 mb-4">
                    <i class="bi bi-quote me-2" style="color: #667eea;"></i>Legacy Message
                </span>
                <h2 class="fw-bold text-white mb-4">"Lebih dari Sekadar Barang Bekas"</h2>
                <p class="text-white opacity-75 mb-4" style="font-size: 1.1rem;">
                    Setiap barang di WAKANDE memiliki cerita. Dari kakak kelas yang telah lulus,
                    untuk adik kelas yang baru memulai. Ini bukan transaksi biasa, ini estafet semangat.
                </p>
                <div class="legacy-message-spotlight p-4 rounded-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                    <i class="bi bi-quote fs-1 text-white opacity-50"></i>
                    <p class="text-white fst-italic mb-3" style="font-size: 1.1rem;">
                        "Semoga buku ini bisa membantu adik-adik belajar. Dulu aku juga dapat warisan dari kakak kelas,
                        sekarang giliranku untuk berbagi. Sukses selalu!"
                    </p>
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3" style="background: white; color: #667eea; width: 48px; height: 48px;">
                            A
                        </div>
                        <div>
                            <p class="fw-bold text-white mb-0">Andi, SMAN 1 Balikpapan</p>
                            <small class="text-white opacity-75">Alumni 2024</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stats-spotlight p-4 rounded-4 text-center" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <h3 class="fw-bold text-white mb-2">2.5 Ton</h3>
                            <p class="text-white opacity-75 small mb-0">Limbah Pendidikan Berkurang</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-spotlight p-4 rounded-4 text-center" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <h3 class="fw-bold text-white mb-2">Rp 150 Jt+</h3>
                            <p class="text-white opacity-75 small mb-0">Hemat Biaya Sekolah</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-spotlight p-4 rounded-4 text-center" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <h3 class="fw-bold text-white mb-2">15+</h3>
                            <p class="text-white opacity-75 small mb-0">Sekolah Berpartisipasi</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-spotlight p-4 rounded-4 text-center" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <h3 class="fw-bold text-white mb-2">1.000+</h3>
                            <p class="text-white opacity-75 small mb-0">Warisan Akademik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-light text-dark rounded-pill px-4 py-2 mb-3">TESTIMONIAL</span>
            <h2 class="fw-bold mb-3">Apa Kata Mereka?</h2>
            <p class="text-secondary" style="max-width: 600px; margin: 0 auto;">
                Ribuan siswa telah merasakan manfaat WAKANDE
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="testimonial-card p-4 rounded-4 h-100" style="background: white; box-shadow: 0 8px 24px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.02);">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            B
                        </div>
                        <div>
                            <p class="fw-bold mb-1">Budi Santoso</p>
                            <small class="text-secondary">SMAN 2 Balikpapan</small>
                        </div>
                    </div>
                    <i class="bi bi-quote text-secondary opacity-25 fs-1"></i>
                    <p class="text-secondary mb-0" style="font-style: italic;">
                        "Dapetin seragam gratis dari kakak kelas, kondisinya masih bagus banget.
                        Hemat uang jajan sebulan! Next mau upload barang juga."
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="testimonial-card p-4 rounded-4 h-100" style="background: white; box-shadow: 0 8px 24px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.02);">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            S
                        </div>
                        <div>
                            <p class="fw-bold mb-1">Siti Rahma</p>
                            <small class="text-secondary">MAN Balikpapan</small>
                        </div>
                    </div>
                    <i class="bi bi-quote text-secondary opacity-25 fs-1"></i>
                    <p class="text-secondary mb-0" style="font-style: italic;">
                        "Legacy message-nya bikin haru. Ada pesan dari kakak kelas buat terus semangat belajar.
                        Jadi makin termotivasi!"
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="testimonial-card p-4 rounded-4 h-100" style="background: white; box-shadow: 0 8px 24px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.02);">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            D
                        </div>
                        <div>
                            <p class="fw-bold mb-1">Dimas Prayoga</p>
                            <small class="text-secondary">SMKN 1 Balikpapan</small>
                        </div>
                    </div>
                    <i class="bi bi-quote text-secondary opacity-25 fs-1"></i>
                    <p class="text-secondary mb-0" style="font-style: italic;">
                        "Beli kalkulator ilmiah cuma 50rb, padahal baru harganya 300rb.
                        Makasih WAKANDE, sangat membantu anak kost kayak aku."
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="cta-card rounded-5 p-5 text-center" style="background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%); border: 1px solid rgba(102,126,234,0.1);">
            <h2 class="fw-bold mb-3">Siap Menjadi Bagian dari Warisan Akademik?</h2>
            <p class="text-secondary mb-4" style="max-width: 600px; margin: 0 auto;">
                Mulai dari upload barang pertama kamu atau temukan perlengkapan sekolah impianmu
            </p>
            <div class="d-flex justify-content-center gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-rounded px-5 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </a>
                @else
                    <a href="{{ route('items.create') }}" class="btn btn-primary btn-rounded px-5 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Barang
                    </a>
                @endguest
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-rounded px-5 py-3">
                    <i class="bi bi-grid me-2"></i>Jelajahi Katalog
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* Animations */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    .hero-section {
        position: relative;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 32px rgba(102,126,234,0.1);
    }

    .step-card {
        transition: transform 0.3s;
    }

    .step-card:hover {
        transform: translateY(-8px);
    }

    .testimonial-card {
        transition: all 0.3s;
    }

    .testimonial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.04) !important;
    }

    .stats-spotlight {
        transition: transform 0.3s;
    }

    .stats-spotlight:hover {
        transform: scale(1.05);
    }

    .cta-card {
        transition: all 0.3s;
    }

    .cta-card:hover {
        transform: scale(1.01);
        box-shadow: 0 24px 48px rgba(102,126,234,0.1);
    }

    /* Dark mode adjustments */
    [data-bs-theme="dark"] .category-card,
    [data-bs-theme="dark"] .step-number,
    [data-bs-theme="dark"] .testimonial-card,
    [data-bs-theme="dark"] .cta-card {
        background: rgba(26, 26, 44, 0.6) !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
    }

    [data-bs-theme="dark"] .testimonial-card {
        background: #1a1a2c !important;
    }

    [data-bs-theme="dark"] .badge.bg-light {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }
</style>
@endpush
@endsection
