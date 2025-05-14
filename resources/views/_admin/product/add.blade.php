<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                @include('_admin._layout.components.form-header', ['type' => "Tambah"])

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi kesalahan pada proses input data:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ url('admin/product/add') }}" enctype="multipart/form-data" navigate-form>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ old('name') }}" required>
                        <div class="invalid-feedback">Nama produk wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" name="price" id="price"
                            value="{{ old('price') }}" min="0" required>
                        <div class="invalid-feedback">Harga wajib diisi dan harus angka.</div>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Produk</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">- Pilih Kategori Produk -</option>
                            @foreach ($productCategories as $b)
                                <option value="{{ $b->id }}" {{ old('category_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kategori produk wajib dipilih.</div>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stock" id="stock"
                            value="{{ old('stock', 0) }}" min="0" required>
                        <div class="invalid-feedback">Stok wajib diisi dan harus angka.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" id="description" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary bg-gradient">
                        <b>Simpan Data</b>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
