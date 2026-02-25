@extends('layouts.app')

@section('title', 'WAKANDE - Ekosistem Sirkular Perlengkapan Sekolah')

@section('content')
{{-- HERO SECTION MINI --}}
<section class="py-4">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                {{-- Badge --}}
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-5 mb-3"
                     style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2);">
                    <i class="bi bi-gem" style="color: #22c55e; font-size: 0.7rem;"></i>
                    <span class="small fw-semibold" style="color: #22c55e; font-size: 0.7rem;">#WarisanAkademik</span>
                </div>

                {{-- Headline --}}
                <h2 class="fw-bold mb-3" style="color: var(--bs-heading-color); font-size: 2rem; line-height: 1.2;">
                    Warisan Akademik untuk
                    <span style="color: #22c55e; position: relative;">
                        Generasi Berikutnya
                        <span style="position: absolute; bottom: 5px; left: 0; width: 100%; height: 6px; background: rgba(34, 197, 94, 0.2); z-index: -1;"></span>
                    </span>
                </h2>

                {{-- Subheadline --}}
                <p class="mb-4" style="color: #64748B; font-size: 0.9rem; max-width: 90%; line-height: 1.6;">
                    Ekosistem sirkular pertama di Balikpapan yang menghubungkan kakak kelas dengan adik kelas untuk mewariskan perlengkapan sekolah. Berbagi itu berkah, mewarisi itu bijak.
                </p>

                {{-- CTA Buttons --}}
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('catalog.index') }}" class="btn btn-sm rounded-4 px-4 py-2 d-flex align-items-center"
                       style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                        <i class="bi bi-search me-1"></i>Jelajahi Katalog
                        <i class="bi bi-arrow-right ms-1" style="font-size: 0.7rem;"></i>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-sm rounded-4 px-4 py-2 d-flex align-items-center"
                           style="background: white; border: 1px solid #E2E8F0; color: #1E293B; font-size: 0.8rem;">
                            <i class="bi bi-gift me-1"></i>Mulai Berbagi
                        </a>
                    @else
                        <a href="{{ route('items.create') }}" class="btn btn-sm rounded-4 px-4 py-2 d-flex align-items-center"
                           style="background: white; border: 1px solid #E2E8F0; color: #1E293B; font-size: 0.8rem;">
                            <i class="bi bi-cloud-upload me-1"></i>Upload Barang
                        </a>
                    @endguest
                </div>

                {{-- Stats --}}
                <div class="row g-2">
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="fw-bold mb-0" style="color: #22c55e; font-size: 1.2rem;">{{ $stats['total_items'] ?? 0 }}+</span>
                            <span class="small" style="color: #94A3B8; font-size: 0.6rem;">Barang Tersedia</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="fw-bold mb-0" style="color: #22c55e; font-size: 1.2rem;">{{ $stats['total_transactions'] ?? 0 }}+</span>
                            <span class="small" style="color: #94A3B8; font-size: 0.6rem;">Transaksi Sukses</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="fw-bold mb-0" style="color: #22c55e; font-size: 1.2rem;">{{ $stats['total_students'] ?? 0 }}+</span>
                            <span class="small" style="color: #94A3B8; font-size: 0.6rem;">Siswa Terbantu</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                {{-- Ilustrasi sederhana --}}
                <div class="position-relative">
                    <div class="rounded-circle mx-auto" style="width: 300px; height: 300px; background: radial-gradient(circle, rgba(34,197,94,0.1) 0%, rgba(255,255,255,0) 70%);"></div>
                    <i class="bi bi-box-seam" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 6rem; color: #22c55e; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES SECTION MINI --}}
<section class="py-4" style="background: #F8FAFC;">
    <div class="container">
        <div class="text-center mb-4">
            <h5 class="fw-bold mb-1" style="color: #0F172A;">Temukan Kebutuhan Akademikmu</h5>
            <p class="small mx-auto" style="color: #64748B; max-width: 500px; font-size: 0.8rem;">
                Dari buku hingga alat praktikum, semua bisa kamu dapatkan secara gratis atau harga terjangkau
            </p>
        </div>

        <div class="row g-3">
            @php
                $categories = [
                    ['name' => 'Buku', 'icon' => 'bi-journal-bookmark-fill', 'route' => 'buku', 'count' => $categories['buku'] ?? 0],
                    ['name' => 'Seragam', 'icon' => 'bi-bag-check-fill', 'route' => 'seragam', 'count' => $categories['seragam'] ?? 0],
                    ['name' => 'Alat Praktikum', 'icon' => 'bi-tools', 'route' => 'alat_praktikum', 'count' => $categories['alat_praktikum'] ?? 0],
                    ['name' => 'Lainnya', 'icon' => 'bi-grid-3x3-gap-fill', 'route' => 'lainnya', 'count' => $categories['lainnya'] ?? 0]
                ];
            @endphp

            @foreach($categories as $cat)
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('catalog.index', ['category' => $cat['route']]) }}" class="text-decoration-none">
                        <div class="category-card p-3 rounded-4 text-center h-100" style="background: white; border: 1px solid #E2E8F0;">
                            <div class="category-icon-wrapper mx-auto mb-2 d-flex align-items-center justify-content-center"
                                 style="width: 64px; height: 64px; background: #F1F5F9; border-radius: 20px;">
                                <i class="bi {{ $cat['icon'] }} fs-3" style="color: #22c55e;"></i>
                            </div>
                            <h6 class="fw-semibold mb-1" style="color: #0F172A;">{{ $cat['name'] }}</h6>
                            <p class="small mb-2" style="color: #64748B; font-size: 0.65rem;">{{ $cat['count'] }}+ barang</p>
                            <span class="small" style="color: #22c55e; font-size: 0.65rem;">
                                Jelajahi <i class="bi bi-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS SECTION MINI --}}
<section class="py-4">
    <div class="container">
        <div class="text-center mb-4">
            <h5 class="fw-bold mb-1" style="color: #0F172A;">Gampang, Cepat, Berkah</h5>
            <p class="small mx-auto" style="color: #64748B; max-width: 500px; font-size: 0.8rem;">
                Hanya 4 langkah sederhana untuk mulai mewariskan atau mendapatkan perlengkapan sekolah
            </p>
        </div>

        <div class="row g-3">
            @php
                $steps = [
                    ['icon' => 'bi-cloud-upload', 'title' => 'Upload Barang', 'desc' => 'Foto barang, tulis deskripsi, tambahkan Legacy Message'],
                    ['icon' => 'bi-shield-check', 'title' => 'Moderasi Admin', 'desc' => 'Admin verifikasi dalam 1x24 jam'],
                    ['icon' => 'bi-credit-card', 'title' => 'Temukan & Transaksi', 'desc' => 'Cari barang, pilih metode pembayaran'],
                    ['icon' => 'bi-box-seam', 'title' => 'Serah Terima', 'desc' => 'Ambil di Drop-off Point sekolah']
                ];
            @endphp

            @foreach($steps as $index => $step)
                <div class="col-lg-3 col-md-6">
                    <div class="step-card p-3 rounded-4 text-center h-100" style="background: white; border: 1px solid #E2E8F0;">
                        <div class="step-icon-wrapper mx-auto mb-2 d-flex align-items-center justify-content-center"
                             style="width: 56px; height: 56px; background: #F1F5F9; border-radius: 16px;">
                            <i class="bi {{ $step['icon'] }} fs-4" style="color: #22c55e;"></i>
                        </div>
                        <span class="d-block fw-semibold mb-1" style="color: #22c55e; font-size: 0.7rem;">Langkah {{ $index + 1 }}</span>
                        <h6 class="fw-semibold mb-1" style="color: #0F172A; font-size: 0.85rem;">{{ $step['title'] }}</h6>
                        <p class="small mb-0" style="color: #64748B; font-size: 0.65rem;">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS MINI --}}
<section class="py-4" style="background: #F8FAFC;">
    <div class="container">
        <div class="text-center mb-4">
            <h5 class="fw-bold mb-1" style="color: #0F172A;">Apa Kata Mereka?</h5>
            <p class="small mx-auto" style="color: #64748B; max-width: 500px; font-size: 0.8rem;">
                Ribuan siswa telah merasakan manfaat WAKANDE
            </p>
        </div>

        <div class="row g-3">
            @php
                $testimonials = [
                    ['initial' => 'B', 'name' => 'Budi Santoso', 'school' => 'SMAN 2 Balikpapan', 'message' => 'Dapetin seragam gratis dari kakak kelas, kondisinya masih bagus banget. Hemat uang jajan sebulan!'],
                    ['initial' => 'S', 'name' => 'Siti Rahma', 'school' => 'MAN Balikpapan', 'message' => 'Legacy message-nya bikin haru. Ada pesan dari kakak kelas buat terus semangat belajar.'],
                    ['initial' => 'D', 'name' => 'Dimas Prayoga', 'school' => 'SMKN 1 Balikpapan', 'message' => 'Beli kalkulator ilmiah cuma 50rb, padahal baru harganya 300rb. Sangat membantu anak kost!']
                ];
            @endphp

            @foreach($testimonials as $testimonial)
                <div class="col-lg-4">
                    <div class="testimonial-card p-3 rounded-4 h-100" style="background: white; border: 1px solid #E2E8F0;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                                 style="width: 40px; height: 40px; background: #F1F5F9; color: #22c55e;">
                                {{ $testimonial['initial'] }}
                            </div>
                            <div>
                                <p class="fw-semibold mb-0 small" style="color: #0F172A;">{{ $testimonial['name'] }}</p>
                                <small class="small" style="color: #64748B; font-size: 0.6rem;">{{ $testimonial['school'] }}</small>
                            </div>
                        </div>
                        <i class="bi bi-quote mb-1" style="color: #22c55e; opacity: 0.3; font-size: 1rem;"></i>
                        <p class="small mb-0" style="color: #475569; font-size: 0.7rem;">{{ $testimonial['message'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA SECTION MINI --}}
<section class="py-4">
    <div class="container">
        <div class="rounded-4 p-4 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
            <h5 class="fw-bold mb-2" style="color: #0F172A;">Siap Menjadi Bagian dari Warisan Akademik?</h5>
            <p class="small mb-3" style="color: #64748B; max-width: 500px; margin: 0 auto; font-size: 0.8rem;">
                Mulai dari upload barang pertama kamu atau temukan perlengkapan sekolah impianmu
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-sm rounded-4 px-3 py-1" style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                        <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                    </a>
                @else
                    <a href="{{ route('items.create') }}" class="btn btn-sm rounded-4 px-3 py-1" style="background: #22c55e; color: white; border: none; font-size: 0.8rem;">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Barang
                    </a>
                @endguest
                <a href="{{ route('catalog.index') }}" class="btn btn-sm rounded-4 px-3 py-1" style="background: white; border: 1px solid #E2E8F0; color: #0F172A; font-size: 0.8rem;">
                    <i class="bi bi-grid me-1"></i>Jelajahi Katalog
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .category-card {
        transition: all 0.2s;
        background: white;
        border: 1px solid #E2E8F0;
    }

    .category-card:hover {
        transform: translateY(-4px);
        border-color: #22c55e !important;
        box-shadow: 0 6px 12px rgba(34, 197, 94, 0.05);
    }

    .step-card {
        transition: all 0.2s;
        background: white;
        border: 1px solid #E2E8F0;
    }

    .step-card:hover {
        transform: translateY(-2px);
        border-color: #22c55e !important;
    }

    .testimonial-card {
        transition: all 0.2s;
        background: white;
        border: 1px solid #E2E8F0;
    }

    .testimonial-card:hover {
        transform: translateY(-2px);
        border-color: #22c55e !important;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.05);
    }

    /* DARK MODE - warna solid, bukan variable */
    [data-bs-theme="dark"] body {
        background-color: #0F172A;
    }

    [data-bs-theme="dark"] .category-card,
    [data-bs-theme="dark"] .step-card,
    [data-bs-theme="dark"] .testimonial-card,
    [data-bs-theme="dark"] [style*="background: white"] {
        background: #1E293B !important;
        border-color: #334155 !important;
    }

    [data-bs-theme="dark"] [style*="background: #F8FAFC"] {
        background: #0F172A !important;
    }

    [data-bs-theme="dark"] [style*="background: #F1F5F9"] {
        background: #334155 !important;
    }

    [data-bs-theme="dark"] [style*="color: #0F172A"] {
        color: #F1F5F9 !important;
    }

    [data-bs-theme="dark"] [style*="color: #64748B"] {
        color: #94A3B8 !important;
    }

    [data-bs-theme="dark"] [style*="color: #475569"] {
        color: #CBD5E1 !important;
    }

    [data-bs-theme="dark"] [style*="border-color: #E2E8F0"] {
        border-color: #334155 !important;
    }

    [data-bs-theme="dark"] .btn-outline-secondary,
    [data-bs-theme="dark"] [style*="background: white; border: 1px solid #E2E8F0"] {
        background: #1E293B !important;
        border-color: #334155 !important;
        color: #F1F5F9 !important;
    }

    [data-bs-theme="dark"] .btn-outline-secondary:hover {
        background: #334155 !important;
    }
</style>
@endpush
