<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 - Halaman Tidak Ditemukan | WAKANDE</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 3rem 2rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #22c55e 0%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 10px 20px rgba(34, 197, 94, 0.2);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        p {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .illustration {
            margin: 2rem auto;
            width: 200px;
            height: 200px;
            position: relative;
        }

        .book-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 8rem;
            color: #22c55e;
            opacity: 0.1;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .search-icon {
            position: absolute;
            top: 30%;
            left: 60%;
            font-size: 3rem;
            color: #22c55e;
            opacity: 0.2;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 100px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: #22c55e;
            color: white;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:hover {
            background: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #22c55e;
            border: 2px solid #22c55e;
        }

        .btn-outline:hover {
            background: rgba(34, 197, 94, 0.05);
            transform: translateY(-2px);
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            }

            .error-container {
                background: rgba(30, 41, 59, 0.9);
                border-color: rgba(255, 255, 255, 0.05);
            }

            h1 {
                color: #f1f5f9;
            }

            p {
                color: #94a3b8;
            }

            .btn-outline {
                color: #4ade80;
                border-color: #4ade80;
            }
        }

        @media (max-width: 640px) {
            .error-code {
                font-size: 6rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            p {
                font-size: 1rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>

        <div class="illustration">
            <i class="bi bi-book book-icon"></i>
            <i class="bi bi-search search-icon"></i>
        </div>

        <h1>Halaman Tidak Ditemukan</h1>

        <p>
            Maaf, halaman yang kamu cari tidak ditemukan atau telah dipindahkan.<br>
            Yuk, kembali ke beranda dan jelajahi koleksi barang lainnya!
        </p>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="bi bi-house-door"></i>
                Kembali ke Beranda
            </a>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline">
                <i class="bi bi-grid"></i>
                Jelajahi Katalog
            </a>
        </div>

        <div style="margin-top: 2rem; font-size: 0.9rem; color: #94a3b8;">
            <i class="bi bi-arrow-left-right me-1"></i>
            WAKANDE - Ekosistem Sirkular Perlengkapan Sekolah
        </div>
    </div>

    <script>
        // Smooth scroll ke atas
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href && !href.startsWith('#')) {
                    window.location.href = href;
                }
            });
        });
    </script>
</body>
</html>
