<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Barang - WAKANDE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }

        .gradient-text {
            color: #22c55e;
            font-weight: 800;
        }

        .form-control,
        .form-select,
        textarea {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            transition: all 0.2s;
            font-size: 0.95rem;
            background-color: #f9fafb;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            background-color: #ffffff;
            border-color: #4ade80;
            box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.15);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .main-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            padding: 2.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #22c55e;
            font-size: 1.25rem;
        }

        .form-check-input:checked {
            background-color: #22c55e;
            border-color: #22c55e;
        }

        .btn-success {
            background-color: #22c55e;
            border-color: #22c55e;
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-success:hover {
            background-color: #16a34a;
            border-color: #16a34a;
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 10px;
            color: #4b5563;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            border-radius: 12px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #f3f4f6;
            color: #111827;
        }

        hr {
            border-color: #e5e7eb;
            margin: 2rem 0;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .main-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('items.index') }}" class="back-btn me-3">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <div>
                        <h2 class="fw-bold mb-0">Upload Barang</h2>
                        <p class="text-secondary mb-0 small">Wariskan perlengkapan sekolahmu untuk adik kelas</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 border-0 bg-danger bg-opacity-10 text-danger mb-4">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-circle-fill me-3 fs-5"></i>
                            <div>
                                <strong class="d-block mb-1">Ada kesalahan pengisian form:</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-3 border-0 bg-danger bg-opacity-10 text-danger mb-4">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success rounded-3 border-0 bg-success bg-opacity-10 text-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Main Form Card -->
                <div class="main-card">
                    <form action="{{ url('/items') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="section-title">
                            <i class="bi bi-box-seam"></i> Informasi Pakaian/Barang
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Barang <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" placeholder="Misal: Seragam Batik SMA Ukuran L" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="category" class="form-label">Kategori <span
                                        class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="" hidden>Pilih kategori...</option>
                                    <option value="buku" {{ old('category') == 'buku' ? 'selected' : '' }}>📚 Buku
                                    </option>
                                    <option value="seragam" {{ old('category') == 'seragam' ? 'selected' : '' }}>👕
                                        Seragam</option>
                                    <option value="alat_praktikum"
                                        {{ old('category') == 'alat_praktikum' ? 'selected' : '' }}>🔬 Alat Praktikum
                                    </option>
                                    <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>📦
                                        Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="condition" class="form-label">Kondisi Barang <span
                                        class="text-danger">*</span></label>
                                <select name="condition" id="condition" class="form-select" required>
                                    <option value="" hidden>Tentukan kondisi...</option>
                                    <option value="baru" {{ old('condition') == 'baru' ? 'selected' : '' }}>🆕 Baru /
                                        Tidak pernah dipakai</option>
                                    <option value="sangat_baik"
                                        {{ old('condition') == 'sangat_baik' ? 'selected' : '' }}>✨ Sangat Baik (Mulus)
                                    </option>
                                    <option value="baik" {{ old('condition') == 'baik' ? 'selected' : '' }}>👍 Baik
                                        (Ada sedikit bekas pemakaian)</option>
                                    <option value="cukup" {{ old('condition') == 'cukup' ? 'selected' : '' }}>🆗 Cukup
                                        (Terlihat sering dipakai)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="description" class="form-label">Deskripsi Tambahan <span
                                    class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="3"
                                placeholder="Sebutkan detail, kekurangan (jika ada), atau alasan diwariskan..." required>{{ old('description') }}</textarea>
                            <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">Jelaskan sejelas-jelasnya.
                                Minimal 20 karakter.</small>
                        </div>

                        <hr>

                        <!-- Upload File -->
                        <div class="section-title">
                            <i class="bi bi-images"></i> Foto Barang
                        </div>

                        <div class="mb-2">
                            <label for="images" class="form-label">Upload Foto <span
                                    class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="file" name="images[]" id="images" class="form-control bg-white" multiple
                                accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">Dapat mengupload beberapa
                                foto sekaligus. Maksimum pemakaian: 5 foto, masing-masing 2MB.</small>
                        </div>

                        <hr>

                        <!-- Transaksi -->
                        <div class="section-title">
                            <i class="bi bi-tag"></i> Sistem Perolehan
                        </div>

                        <div class="p-3 border rounded-3 bg-light mb-4">
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeGift"
                                        value="gift" {{ old('type', 'gift') == 'gift' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="typeGift">
                                        🎁 Dihibahkan (Gratis)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeSale"
                                        value="sale" {{ old('type') == 'sale' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="typeSale">
                                        💰 Dijual
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="priceField" class="mb-4"
                            style="{{ old('type') == 'sale' ? '' : 'display:none' }}">
                            <label for="price" class="form-label">Harga Barang (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"
                                    style="border-radius: 8px 0 0 8px; border-color: #d1d5db;">Rp</span>
                                <input type="number" name="price" id="price"
                                    class="form-control border-start-0 ps-0" style="border-radius: 0 8px 8px 0;"
                                    value="{{ old('price', 0) }}" placeholder="Contoh: 50000">
                            </div>
                        </div>

                        <!-- Pesan Motivasi -->
                        <div class="section-title mt-4">
                            <i class="bi bi-chat-quote"></i> Pesan Warisan (Legacy Message)
                        </div>

                        <div class="mb-4">
                            <label for="legacy_message" class="form-label">Tinggalkan pesan untuk pemilik selanjutnya
                                <span class="text-danger">*</span></label>
                            <textarea name="legacy_message" id="legacy_message" class="form-control" rows="2"
                                placeholder="Semoga buku ini bermanfaat untuk mengejar cita-citamu! Semangat! 💪" required>{{ old('legacy_message') }}</textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex flex-column flex-sm-row gap-3 pt-3 mt-4 border-top">
                            <button type="submit" class="btn btn-success flex-grow-1 py-2">
                                <i class="bi bi-cloud-upload me-2"></i> Upload Barang
                            </button>
                            <a href="{{ route('items.index') }}" class="btn btn-outline-secondary py-2 text-center">
                                Batal
                            </a>
                        </div>

                    </form>
                </div> <!-- End Main Form Card -->

            </div>
        </div>
    </div>

    <script>
        document.getElementById('typeGift')?.addEventListener('change', function() {
            document.getElementById('priceField').style.display = 'none';
        });
        document.getElementById('typeSale')?.addEventListener('change', function() {
            document.getElementById('priceField').style.display = 'block';
        });
    </script>
</body>

</html>
