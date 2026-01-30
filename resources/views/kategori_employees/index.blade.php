@extends('layouts.index')

@section('judul','Kategori Pegawai')

@section('content')
<div class="container">
        <div class="mb-2">
            <h4 class="fw-semibold mb-1">Data Kategori Pegawai</h4>
            <small class="text-muted">
                <a href="#">Dashboard</a> • Kategori Pegawai
            </small>
        </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between">
            <h5 class="fw-semibold mb-0">Kategori Pegawai</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama Kategori</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoriEmployees as $i => $k)
                        <tr>
                            <td>{{ $kategoriEmployees->firstItem() + $i }}</td>
                            <td>{{ $k->nama_kategori }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $k->id }}">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDelete{{ $k->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        

                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Data kategori belum tersedia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white">
            {{ $kategoriEmployees->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@foreach ($kategoriEmployees as $k)
    
<div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('kategori.employee.update', $k->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h6 class="modal-title">Edit Kategori</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input
                            type="text"
                            name="nama_kategori"
                            value="{{ $k->nama_kategori }}"
                            class="form-control"
                            required
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success btn-sm">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach

@foreach ($kategoriEmployees as $k)

<div class="modal fade" id="modalDelete{{ $k->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('kategori.employee.delete', $k->id) }}">
                @csrf
                @method('DELETE')

                <div class="modal-header border-0">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <!-- ICON BULAT -->
                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle shadow bg-light bg-opacity-10 text-warning mb-3"
                        style="width: 90px; height: 90px;">
                        <i class="fas fa-question fs-1"></i>
                    </div>

                    <h5 class="mb-2">Hapus Kategori?</h5>

                    <p class="text-muted mb-0">
                        Apakah Anda yakin ingin menghapus kategori
                        <strong>{{ $k->nama_kategori }}</strong>?
                        <br>
                        Tindakan ini membuat data tidak dapat dikembalikan.
                    </p>
                </div>

                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        Ya, Hapus
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

    
@endforeach


{{-- MODAL CREATE --}}
<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('kategori.employee.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h6 class="modal-title">Tambah Kategori</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label>Nama Kategori</label>
                <input name="nama_kategori" class="form-control" required>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success btn-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
