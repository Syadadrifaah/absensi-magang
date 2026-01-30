@extends('layouts.index')

@section('judul', 'Pengaturan Jam Absensi')

@section('content')

<style>
    .switch-lg .form-check-input {
        width: 3.5rem;
        height: 1.75rem;
        cursor: pointer;
    }

    .switch-lg .form-check-input:checked {
        background-color: #1fa5fe;
        border-color: #1fa5fe;
    }

    .switch-lg .form-check-input:focus {
        box-shadow: none;
    }
</style>


<div class="container mt-4">

    <div class="mb-3">
        <h4 class="fw-semibold mb-1">Pengaturan Absensi</h4>
        <small class="text-muted">
            <a href="#">Dashboard</a> • Pengaturan Jam Absensi
        </small>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-end align-items-center mb-3">
                {{-- <h4 class="fw-bold">Pengaturan Jam Absensi</h4> --}}
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    + Tambah Jam Absensi
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center table-responsive">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th></th>
                            <th width="260" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>
                                {{ $row->jam_masuk_mulai }} - {{ $row->jam_masuk_selesai }}
                            </td>
                            <td>
                                {{ $row->jam_pulang_mulai }} - {{ $row->jam_pulang_selesai }}
                            </td>
                            <td>
                                @if($row->aktif)
                                    <span class="badge bg-success px-3 py-2">AKTIF</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">NON AKTIF</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                {{-- Toggle Aktif --}}

                                <form action="{{ route('pengaturan.absensi.toggle', $row->id) }}"
                                    method="POST"
                                    class="d-flex justify-content-center">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-check form-switch switch-lg">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            onchange="this.form.submit()"
                                            {{ $row->aktif ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center justify-content-center">
                                {{-- Edit --}}
                                <button class="btn btn-info btn-sm text-white"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $row->id }}">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm text-white"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete{{ $row->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        

                        <div class="modal fade" id="modalDelete{{ $row->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <form action="{{ route('pengaturan.absensi.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Hapus Jam Absensi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p>Yakin ingin menghapus jam absensi:</p>
                                            <strong>{{ $row->nama }}</strong> ?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-muted">Belum ada data jam absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('pengaturan.absensi.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jam Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jam</label>
                        <input type="text" name="nama" class="form-control"
                               placeholder="Contoh: Jam Kerja Normal" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk Mulai</label>
                            <input type="time" name="jam_masuk_mulai" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk Selesai</label>
                            <input type="time" name="jam_masuk_selesai" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang Mulai</label>
                            <input type="time" name="jam_pulang_mulai" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang Selesai</label>
                            <input type="time" name="jam_pulang_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

@foreach ($data as $row)
    {{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('pengaturan.absensi.update', $row->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Jam Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jam</label>
                        <input type="text" name="nama" class="form-control"
                            value="{{ $row->nama }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk Mulai</label>
                            <input type="time" name="jam_masuk_mulai" class="form-control"
                                value="{{ $row->jam_masuk_mulai }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk Selesai</label>
                            <input type="time" name="jam_masuk_selesai" class="form-control"
                                value="{{ $row->jam_masuk_selesai }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang Mulai</label>
                            <input type="time" name="jam_pulang_mulai" class="form-control"
                                value="{{ $row->jam_pulang_mulai }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang Selesai</label>
                            <input type="time" name="jam_pulang_selesai" class="form-control"
                                value="{{ $row->jam_pulang_selesai }}" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>    
@endforeach

@endsection
