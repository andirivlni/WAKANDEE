@php
    // Completely standalone form - NO layout, NO Vite, NO JS
    // This bypasses ALL potential interference
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Test Upload Simpel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }

        input,
        select,
        textarea,
        button {
            display: block;
            width: 100%;
            margin: 8px 0 16px;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-size: 16px;
        }

        .error {
            background: #ffdddd;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid red;
        }

        .success {
            background: #ddffdd;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid green;
        }
    </style>
</head>

<body>
    <h1>Test Upload Barang (Simpel)</h1>

    @if ($errors->any())
        <div class="error">
            <strong>Error:</strong>
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <p>Login sebagai: <strong>{{ Auth::user()->name ?? 'TIDAK LOGIN' }}</strong></p>

    <form method="POST" action="{{ url('/test-store-item') }}" enctype="multipart/form-data">
        @csrf

        <label>Nama Barang:</label>
        <input type="text" name="name" value="{{ old('name', 'Test Buku Upload') }}">

        <label>Kategori:</label>
        <select name="category">
            <option value="buku">Buku</option>
            <option value="seragam">Seragam</option>
            <option value="lainnya">Lainnya</option>
        </select>

        <label>Kondisi:</label>
        <select name="condition">
            <option value="baik">Baik</option>
            <option value="baru">Baru</option>
            <option value="cukup">Cukup</option>
        </select>

        <label>Deskripsi (min 20 karakter):</label>
        <textarea name="description">{{ old('description', 'Ini adalah deskripsi buku yang cukup panjang untuk memenuhi validasi') }}</textarea>

        <label>Tipe:</label>
        <select name="type">
            <option value="gift">Hibah (Gratis)</option>
            <option value="sale">Dijual</option>
        </select>

        <label>Pesan Legacy (min 10 karakter):</label>
        <textarea name="legacy_message">{{ old('legacy_message', 'Semoga bermanfaat untuk adik kelas') }}</textarea>

        <label>Foto (opsional):</label>
        <input type="file" name="images[]" multiple accept="image/*">

        <button type="submit">SUBMIT SEKARANG</button>
    </form>

    <hr>
    <p><small>Form action: {{ url('/test-store-item') }}</small></p>
    <p><small>CSRF: {{ csrf_token() }}</small></p>
</body>

</html>
