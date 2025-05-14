<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-body">
                @include('_admin._layout.components.form-header', ['type' => 'Edit'])
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <strong>Terjadi kesalahan saat menyimpan data:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ url('admin/' . $page['route'] . '/update/' . $data->id) }}" navigate-form>
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $data->name) }}"
                               placeholder="Masukkan nama kategori"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4 d-flex justify-content-start"> 
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save"></i> <strong>Simpan Perubahan</strong>
                        </button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
