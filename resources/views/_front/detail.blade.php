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

        .news-others {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .news-others:hover {
            background-color: #f8f9fa;
        }

        .news-others h5 {
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .news-others span {
            font-size: 0.8rem;
            color: #175ca9;
        }
    </style>
</head>

<body>
    @include('_front.navbar')

    <section class="py-5 bg-light container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <span class="badge bg-success text-uppercase px-4 py-2 fs-6 rounded-pill">
                            {{ $news->category_name ?? 'Kategori Tidak Diketahui' }}
                        </span>
                    </div>
                    <h1 class="mb-4 fw-bold">
                        {{ $news->title ?? 'Judul Tidak Ditemukan' }}
                    </h1>
                    <p class="mb-2" style="font-size: 13px;">
                        Ditulis oleh <strong>{{ $news->author_name ?? 'Admin' }}</strong>
                    </p>
                    <p class="news-time">
                        {{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="pb-5 container">
            <div class="row">
                <div class="col-lg-8">
                    @if(!empty($news->thumbnail_small))
                        <div class="text-center mb-4">
                            <img src="{{ asset($news->thumbnail_small) }}" class="img-fluid rounded"
                                alt="Gambar berita">
                        </div>
                    @endif
                    <div class="fs-5 d-none d-md-block" style="line-height: 1.8;">
                        {!! $news->content ?? '<p>Konten tidak tersedia.</p>' !!}
                    </div>
                     <div class="d-block d-md-none mx-2" style="line-height: 1.8; font-size: 17px">
                        {!! $news->content ?? '<p>Konten tidak tersedia.</p>' !!}
                    </div>
                </div>


                <div class="col-lg-4">
                    <h4 class="pb-2">Berita Lainnya</h4>

                    <div class="news-others mb-3"
                        onclick="location.href='https://news.detik.com/berita/d-7906659/habemus-papam-paus-leo-xiv-serukan-pesan-perdamaian';">
                        <h5><strong>Habemus Papam! Paus Leo XIV Serukan Pesan Perdamaian</strong></h5>
                        <span>Berita Nasional</span>
                    </div>

                    <div class="news-others mb-3"
                        onclick="location.href='https://www.detik.com/sulsel/makassar/d-7906477/sosok-mahasiswi-berprestasi-fk-unhas-jadi-joki-utbk-dengan-tarif-rp-2-juta?mtype=mpt.ctr-boxccxmpcxmp-modelB';">
                        <h5><strong>Sosok Mahasiswi Berprestasi FK Unhas Jadi Joki UTBK dengan Tarif Rp 2 Juta</strong>
                        </h5>
                        <span>Berita Nasional</span>
                    </div>

                    <div class="news-others mb-3"
                        onclick="location.href='https://news.detik.com/berita/d-7906830/penyidik-kpk-sindir-febri-diansyah-pernah-ikut-ekspose-tapi-kini-bela-hasto';">
                        <h5><strong>Penyidik KPK Sindir Febri Diansyah Pernah Ikut Ekspose tapi Kini Bela Hasto</strong>
                        </h5>
                        <span>Berita Nasional</span>
                    </div>
                </div>
            </div>
    </section>

    <section class="bg-dark mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12 text-white text-center">
                    <h6 class="mb-0">Copyright 2025 | alanaptra_</h6>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" crossorigin="anonymous"></script>
</body>

</html>