<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Info Jatim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #1b63b6, #14509b);
            color: white;
            padding: 70px 20px;
            border-bottom-left-radius: 3rem;
            border-bottom-right-radius: 3rem;
        }

        .card-news {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease;
        }

        .card-news:hover {
            transform: translateY(-5px);
        }

        .kategori-item {
            background-color: #f1f5f9;
            border-radius: 50px;
            padding: 12px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s ease;
            cursor: pointer;
        }

        .kategori-item:hover {
            background-color: #e2e8f0;
        }

        .kategori-icon {
            background-color: #175ca9;
            color: white;
            border-radius: 50%;
            padding: 8px;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #16559e;">
        <div class="container">
            <a href="https://infojatim.id/">
                <img src="https://infojatim.id/assets/images/logo.png?v=2" class="img-fluid my-3" width="200">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Berita Nasional</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Viral</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Bencana Alam</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kriminal</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container ps-4">
            <h6 class="mb-2">Portal Informasi Berita</h6>
            <h1 class="fw-bold">Jawa Timur</h1>
        </div>
    </div>

    <div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-4">Berita Terbaru</h4>
            @foreach ($berita_terbaru as $berita)
                <a href="{{ url('/front/' . $berita->slug) }}" class="text-decoration-none text-dark">
                    <div class="card mb-4 card-news shadow">
                        <div class="row g-0">
                            <div class="col-md-8 p-3">
                                <h5 class="card-title">{{ $berita->title }}</h5>
                                <p class="text-muted small">
                                    {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}
                                </p>
                                <div class="mb-3">
                                    <span class="badge bg-primary">{{ $berita->category ?? 'Umum' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <img src="{{ asset($berita->thumbnail_small ?? 'default.jpg') }}"
                                     class="img-fluid rounded-end" style="max-height: 150px; object-fit: cover;"
                                     alt="{{ $berita->title }}" />
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="col-lg-4">
            <h4 class="mb-4">Kategori Berita</h4>
            <div class="kategori-item mb-3">
                <div class="kategori-icon"><i class="bi bi-globe"></i></div>Berita Nasional
            </div>
            <div class="kategori-item mb-3">
                <div class="kategori-icon"><i class="bi bi-lightning-charge-fill"></i></div>Viral
            </div>
            <div class="kategori-item mb-3">
                <div class="kategori-icon"><i class="bi bi-cloud-drizzle-fill"></i></div>Bencana Alam
            </div>
            <div class="kategori-item mb-3">
                <div class="kategori-icon"><i class="bi bi-shield-exclamation"></i></div>Kriminal
            </div>
        </div>
    </div>
</div>

    <section class="bg-dark" style="margin-top: 100px;">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12 text-white text-center">
                    <h6 class="mb-0"> Copyright 2025 | Infojatim.id</h6>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>