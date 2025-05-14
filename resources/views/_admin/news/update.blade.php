<div class="row">
    <div class="col-12">
        @include('_admin._layout.components.form-header', ['type' => 'Edit'])
    </div>

    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ url('admin/news/update', $data->id) }}" enctype="multipart/form-data" navigate-form>
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="title" id="title"
                                value="{{ old('title', $data->title) }}" required>
                            <div class="invalid-feedback">Judul berita wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Isi</label>
                            <textarea name="content" id="content-editor" class="form-control" id="content" rows="10" required>{{ old('content', $data->content) }}</textarea>
                            <div class="invalid-feedback">Isi berita wajib diisi.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">- Pilih Kategori -</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $data->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Kategori berita wajib dipilih.</div>
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail_small" class="form-label">Gambar Cover</label>
                            <input type="file" class="form-control" name="thumbnail_small" id="thumbnail_small"
                                accept="image/*" onchange="previewImage(event)">
                            @if (!empty($data->thumbnail_small))
                                <div class="mt-2" id="currentImageContainer">
                                    <img src="{{ asset($data->thumbnail_small) }}" class="img-fluid rounded border">
                                </div>
                            @endif
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary bg-gradient"><b>Simpan Perubahan</b></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const output = document.getElementById('imagePreview');
        const fileInfo = document.getElementById('fileInfo');

        if (event.target.files[0]) {
            reader.onload = function(e) {
                output.src = e.target.result;
                output.style.display = 'block';
                fileInfo.textContent = event.target.files[0].name;
            };
            reader.readAsDataURL(event.target.files[0]);
        } else {
            output.style.display = 'none';
            fileInfo.textContent = 'Tidak ada file yang dipilih';
        }
    }
</script>

<script>
    $('#content-editor').summernote({
        tabsize: 6,
        height: 520,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>
