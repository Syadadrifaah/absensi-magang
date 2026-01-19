@extends('layouts.index')

@section('judul','Kategori Pegawai')

@section('content')
<div class="container">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between">
            <h5 class="fw-semibold mb-0">Kategori Pegawai</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body p-0">
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

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="modalEdit{{ $k->id }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('kategori.employee.update', $k->id) }}" class="modal-content">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h6 class="modal-title">Edit Kategori</h6>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label>Nama Kategori</label>
                                    <input name="nama_kategori" value="{{ $k->nama_kategori }}" class="form-control" required>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-success btn-sm">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- MODAL DELETE --}}
                    <div class="modal fade" id="modalDelete{{ $k->id }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('kategori.employee.delete', $k->id) }}" class="modal-content">
                                @csrf
                                @method('DELETE')

                                <div class="modal-header">
                                    <h6 class="modal-title">Hapus Kategori</h6>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    Yakin ingin menghapus kategori
                                    <strong>{{ $k->nama_kategori }}</strong>?
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>

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

        <div class="card-footer bg-white">
            {{ $kategoriEmployees->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

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
