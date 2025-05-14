<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sustainable Environment | De-Je</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            margin-bottom: 0;
        }

        .hero-section {
            background: linear-gradient(135deg, #00796B, #00796B);
            color: white;
            padding: 70px 20px;
            border-bottom-right-radius: 10rem;
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
            background-color: #004D40;
            color: white;
            border-radius: 30%;
            padding: 8px;
            font-size: 1rem;
        }
    </style>
</head>

<body>

    @include('_front.navbar')

    <div class="hero-section">
        <div class="container ps-1">
            <h6 class="mb-2">Lingkungan Berkelanjutan</h6>
            <h1 class="fw-bold">SMKN 8 JEMBER</h1>
        </div>
    </div>

    <div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-4 ms-2 fw-bold">Berita Terbaru</h4>
            @foreach ($berita_terbaru as $berita)
                <a href="{{ url('/front/' . $berita->slug) }}" class="text-decoration-none text-dark">
                    <div class="card mb-4 p-4 card-news shadow">
                        <div class="row g-0">
                            <div class="col-md-9 col-8">
                                <h5 class="d-none d-md-block" style="font-size: 20px">{{ $berita->title }}</h5>
                                <h5 class="d-block d-md-none fw-semibold px-2" style="font-size: 13px">{{ $berita->title }}</h5>
                                <p class="text-muted small px-2 mb-0 mb-md-3">
                                    {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}
                                </p>
                                <div class="mb-3 d-md-block d-none">
                                    <span class="badge bg-primary">{{ $berita->category ?? 'Umum' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-4 text-right px-2 d-flex d-md-block flex-column justify-content-center">
                                <img src="{{ asset($berita->thumbnail_small ?? 'default.jpg') }}"
                                     class="img-fluid" style="border-radius: 10px;" width="120"
                                     alt="{{ $berita->title }}" />
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="col-lg-4 mt-5">
            <h4 class="mb-4 ms-2 fw-bold">Kategori Berita</h4>
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

    <section style="margin-top: 100px; background-color: #004D40">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12 text-white text-center">
                    <h6 class="mb-0"> Copyright 2025 | alanaptra_</h6>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>