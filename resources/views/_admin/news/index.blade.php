<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="fw-bolder mt-2">Berita</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="text-md-end text-start">
                            <a href="{{ url('admin/news/add') }}" class="btn btn-primary btn fw-bold bg-gradient"
                                navigate>
                                @include('_admin._layout.icons.plus')
                                <b>Tambah Data</b>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive-sm mt-5">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="table-header" style="--width: 15%">Sampul</th>
                                <th class="table-header" style="--width: 70%">Judul / Kategori</th>
                                <th class="table-header text-center" style="--width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                                <tr>
                                    <td>
                                        @if ($item->thumbnail_small)
                                            <img src="{{ asset($item->thumbnail_small) }}" class="img-fluid rounded"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ url('admin/news/detail/' . $item->id) }}"
                                                class="fw-bold text-primary" style="text-decoration: none;">
                                                {{ $item->title }}
                                            </a>
                                        </div>
                                        <div class="badge bg-light text-dark mt-1">Berita Nasional</div>
                                        <div class="text-muted small mt-1">
                                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-light btn-sm shadow-sm border-1 border-primary-subtle"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-dots">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/news/update/' . $item->id) }}"
                                                        navigate>Edit</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger"
                                                        href="{{ url('admin/news/delete/' . $item->id) }}"
                                                        confirm-message="Hapus data ini?" navigate-api-confirm>Hapus</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (!count($data))
                    @include('_admin._layout.components.empty-data', ['title' => 'Berita'])
                @endif

                <div class="mt-3">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('admin-ui') }}/assets/js/paginate.js"></script>
