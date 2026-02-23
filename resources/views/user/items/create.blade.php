<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Barang - WAKANDE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}"><span class="gradient-text">WAKANDE</span></a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('items.index') }}" class="nav-link">Barang Saya</a>
                <span class="text-muted small">{{ Auth::user()->name ?? '' }}</span>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Upload Barang</h2>
                    <p class="text-secondary">Wariskan perlengkapan sekolahmu untuk adik kelas</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Ada kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success rounded-3">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ url('/items') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-card p-4 mb-3 shadow-sm">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Dasar</h5>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Barang <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" placeholder="Contoh: Buku Matematika Kelas 10" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="category" class="form-label fw-semibold">Kategori <span
                                        class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">Pilih kategori</option>
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
                                <label for="condition" class="form-label fw-semibold">Kondisi <span
                                        class="text-danger">*</span></label>
                                <select name="condition" id="condition" class="form-select" required>
                                    <option value="">Pilih kondisi</option>
                                    <option value="baru" {{ old('condition') == 'baru' ? 'selected' : '' }}>🆕 Baru
                                    </option>
                                    <option value="sangat_baik"
                                        {{ old('condition') == 'sangat_baik' ? 'selected' : '' }}>✨ Sangat Baik
                                    </option>
                                    <option value="baik" {{ old('condition') == 'baik' ? 'selected' : '' }}>👍 Baik
                                    </option>
                                    <option value="cukup" {{ old('condition') == 'cukup' ? 'selected' : '' }}>🆗 Cukup
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi <span
                                    class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="3"
                                placeholder="Jelaskan detail barangmu (min. 20 karakter)" required>{{ old('description') }}</textarea>
                            <small class="text-muted">Minimal 20 karakter</small>
                        </div>
                    </div>

                    <div class="form-card p-4 mb-3 shadow-sm">
                        <h5 class="fw-bold mb-3"><i class="bi bi-camera me-2"></i>Foto Barang</h5>
                        <input type="file" name="images[]" id="images" class="form-control" multiple
                            accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Opsional. Maks 5 foto, masing-masing maks 2MB</small>
                    </div>

                    <div class="form-card p-4 mb-3 shadow-sm">
                        <h5 class="fw-bold mb-3"><i class="bi bi-tag me-2"></i>Tipe & Harga</h5>
                        <div class="d-flex gap-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeGift"
                                    value="gift" {{ old('type', 'gift') == 'gift' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeGift">🎁 Hibah (Gratis)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeSale"
                                    value="sale" {{ old('type') == 'sale' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeSale">💰 Dijual</label>
                            </div>
                        </div>
                        <div id="priceField" style="{{ old('type') == 'sale' ? '' : 'display:none' }}">
                            <label for="price" class="form-label fw-semibold">Harga (Rp)</label>
                            <input type="number" name="price" id="price" class="form-control"
                                value="{{ old('price', 0) }}" placeholder="10000">
                        </div>
                    </div>

                    <div class="form-card p-4 mb-3 shadow-sm">
                        <h5 class="fw-bold mb-3"><i class="bi bi-chat-heart me-2"></i>Legacy Message</h5>
                        <textarea name="legacy_message" id="legacy_message" class="form-control" rows="2"
                            placeholder="Pesan untuk adik kelas... (min. 10 karakter)" required>{{ old('legacy_message') }}</textarea>
                        <small class="text-muted">Minimal 10 karakter</small>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Sekarang
                        </button>
                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                    </div>
                </form>
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
