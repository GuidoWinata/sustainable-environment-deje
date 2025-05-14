<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">Detail <b>{{ $page['title'] ?? 'Produk' }}</b></h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ base_url($page['route'] . '/') }}" class="btn btn-outline-indigo btn-sm fw-bold">
                            ← <b>Kembali</b>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="mb-3">
                    <label>Nama Produk</label>
                    <p class="fs-5 fw-bold mb-0">{{ $data->name ?? '-' }}</p>
                </div>
                <hr class="dotted">
                <div class="mb-3">
                    <label>Kategori Produk</label>
                    <p class="fs-5 fw-semibold mb-0">
                        {{ $data->category_name ?? '-' }}
                    </p>
                </div>
                <div class="mb-3">
                    <label>Harga</label>
                    <p class="fs-5 fw-bold mb-0">
                        Rp {{ number_format($data->price ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="mb-3">
                    <label>Stok</label>
                    <p class="fs-5 fw-bold mb-0">{{ $data->stock ?? 0 }} item</p>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <p class="fs-5 fw-bold mb-0">{{ $data->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                <div class="mb-3">
                    <label>Dibuat</label>
                    <p class="fs-5 fw-bold mb-0">
                        {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="mb-3">
                    <label>Diperbarui</label>
                    <p class="fs-5 fw-bold mb-0">
                        {{ \Carbon\Carbon::parse($data->updated_at)->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
