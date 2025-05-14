<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                @include('_admin._layout.components.form-header', ['type' => "Edit"])
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <b>Terjadi kesalahan pada proses input data</b> <br>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ url('admin/product/update/' . $data->id) }}" enctype="multipart/form-data" navigate-form>
                    @csrf                   
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="name" id="name"
                               value="{{ old('name', $data->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" name="price" id="price"
                               value="{{ old('price', $data->price) }}" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stock" id="stock"
                               value="{{ old('stock', $data->stock) }}" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Produk</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">- Pilih Kategori Produk -</option>
                            @foreach ($productCategories as $d)
                                <option value="{{ $d->id }}" @selected($data->category_id == $d->id)>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" rows="3">{{ old('description', $data->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary bg-gradient"><b>Simpan Perubahan</b></button>
                </form>
            </div>
        </div>
    </div>
</div>
