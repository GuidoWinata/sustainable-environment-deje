<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="fw-bolder mb-4">{{ $page['title'] }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="text-md-end text-start">
                            <a href="{{ base_url($page['route'] . '/add') }}"
                                class="btn btn-primary btn fw-bold bg-gradient" navigate>
                                @include('_admin._layout.icons.plus')
                                <b>Tambah Data</b>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive-sm">
                    <table class="table table-bordered table-hover mt-3 table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="table-header" style="--width: 80%">NAMA</th>
                                <th class="table-header text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $d)
                                <tr>
                                    <td>{{ $d->name }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-light btn-sm shadow-sm border-1 border-primary-subtle"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
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
                                                        href="{{ base_url($page['route'] . "/update/{$d->id}") }}"
                                                        navigate>Edit</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger"
                                                        href="{{ base_url($page['route'] . "/delete/{$d->id}") }}"
                                                        confirm-message="Apakah kamu yakin menghapus {{ $d->name }}?"
                                                        navigate-api-confirm>Hapus</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        @include('_admin._layout.components.empty-data', ['title' => $page['title']])
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-3">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ url('admin-ui') }}/assets/js/paginate.js"></script>
