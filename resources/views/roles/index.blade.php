@extends('layouts.index')

@section('judul'.'Role')

@section('content')
<div class="container">
    <div class="mb-3">
            <h4 class="fw-semibold mb-1">Manajemen Role</h4>
            <small class="text-muted">
                <a href="#">Dashboard</a> • Role
            </small>
        </div>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Role</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                + Tambah Role
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Role</th>
                            <th>Deskripsi</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $role->id }}">
                                    Edit
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $role->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- //modal edit --}}
@foreach($roles as $role)
{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal{{ $role->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Role</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ $role->name }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea
                            name="description"
                            class="form-control"
                            rows="3"
                        >{{ $role->description }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endforeach


@foreach ($roles as $role)
{{-- MODAL DELETE --}}
<div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header border-0">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center py-4">

                    {{-- ICON BULAT --}}
                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle shadow bg-light bg-opacity-10 text-warning mb-3"
                        style="width: 90px; height: 90px;">
                        <i class="fas fa-question fs-1"></i>
                    </div>

                    <h5 class="mb-1">Hapus Role?</h5>
                    <p class="text-muted mb-4">
                        Apakah Anda yakin ingin menghapus role
                        <strong>{{ $role->name }}</strong>?
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            Ya, Hapus
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>

    
@endforeach

{{-- MODAL CREATE --}}
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Role</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
