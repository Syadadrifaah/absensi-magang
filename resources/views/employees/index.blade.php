@extends('layouts.index')

@section('judul','Data Pegawai')

@section('content')

<?php $positions = \App\Models\Position::orderBy('level')->get(); ?>

<style>
    .pagination .page-link {
        border-radius: 0 !important;
        color: #0d6efd;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
    }

</style>


<div class="container">

    <div class="card shadow-md border-0">
        {{-- HEADER --}}
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-semibold mb-0">Data Pegawai</h5>
                {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fas fa-plus me-1"></i> Tambah Pegawai
                </button> --}}
            </div>

            <form method="GET" class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            {{-- 🔍 SEARCH (KIRI) --}}
            <div class="d-flex" style="max-width: 320px; width:100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Cari nama / NIP">
                </div>
            </div>

            {{-- KANAN : FILTER + TAMBAH --}}
            <div class="d-flex align-items-center gap-2">

                {{--  FILTER ROLE --}}
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i>
                        {{ $roles->firstWhere('id', request('role_id'))->name ?? 'Filter Role' }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('employees.index') }}">
                                Semua Role
                            </a>
                        </li>
                        @foreach($roles as $role)
                            <li>
                                <a class="dropdown-item"
                                href="{{ route('employees.index', array_merge(request()->except('page'), ['role_id' => $role->id])) }}">
                                    {{ $role->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- ➕ TAMBAH --}}
                <button type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCreate">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>

            </div>

        </form>
        </div>

        {{-- TABLE --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Departemen</th>
                            <th>Kategori</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $i => $e)
                        <tr>
                            <td>{{ $employees->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold">{{ $e->name }}</div>
                                <small class="text-muted">{{ $e->email }}</small><br>
                                <span class="badge badge-warning">
                                    {{ $e->position->name ?? '-' }}
                                </span>
                                @if($e->user)
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $e->user->email }}
                                    </span>
                                    <br>
                                    {{-- <small class="text-muted">{{ $e->user->role->name ?? '-' }}</small> --}}
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        Belum Memiliki Akun
                                    </span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $e->nip }}</td>
                            <td>{{ $e->department }}</td>
                            <td>
                                @if($e->kategori)
                                    <span class="badge badge-lg bg-info">
                                        {{ $e->kategori->nama_kategori }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        Tidak Ada Kategori
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-success">
                                    {{ $e->user->role->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">

                                <button class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $e->id }}">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDelete{{ $e->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>                                   
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Data pegawai tidak ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
             @if ($employees->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-0 p-2">

                    {{-- INFO --}}
                    <div class="text-muted small">
                        Showing
                        {{ $employees->firstItem() }}
                        to
                        {{ $employees->lastItem() }}
                        of
                        {{ $employees->total() }}
                        entries
                    </div>

                    {{-- PAGINATION --}}
                    <nav>
                        <ul class="pagination mb-0">

                            {{-- Previous --}}
                            <li class="page-item {{ $employees->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                href="{{ $employees->previousPageUrl() ?? '#' }}">
                                    Previous
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $employees->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Next --}}
                            <li class="page-item {{ $employees->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link"
                                href="{{ $employees->nextPageUrl() ?? '#' }}">
                                    Next
                                </a>
                            </li>

                        </ul>
                    </nav>

                </div>
            @endif
        </div>
     





    </div>
</div>


<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="/employees/store" class="modal-content">
            @csrf

            <div class="modal-header">
                <h6 class="modal-title fw-semibold">
                    <i class="fas fa-user-plus me-1"></i> Tambah Pegawai
                </h6>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-2">

                {{-- NIP --}}
                <div class="col-md-6">
                    <label>NIP</label>
                    <input name="nip"
                           value="{{ old('nip') }}"
                           class="form-control @error('nip') is-invalid @enderror" required>

                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="col-md-6">
                    <label>Nama</label>
                    <input name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror" required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Posisi --}}
                <div class="col-md-6">
                    <label>Posisi</label>
                    <select name="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Posisi --</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                                {{ str_repeat('— ', max(0, ($pos->level ?? 0) - 1)) }}{{ $pos->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('position_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Departemen --}}
                <div class="col-md-6">
                    <label>Departemen</label>
                    <input name="department"
                           value="{{ old('department') }}"
                           class="form-control @error('department') is-invalid @enderror" required>

                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="col-md-6">
                    <label>Telepon</label>
                    <input name="phone"
                           value="{{ old('phone') }}"
                           class="form-control @error('phone') is-invalid @enderror" required>

                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- User --}}
                <div class="col-md-6">
                    <label>User</label>
                    <select name="user_id"
                            class="form-select @error('user_id') is-invalid @enderror">
                        <option value="">-- Tidak ada --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}"
                                {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->email }}
                            </option>
                        @endforeach
                    </select>

                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="col-md-6">
                    <label>Kategori</label>
                    <select name="kategori_id"
                            class="form-select @error('kategori_id') is-invalid @enderror">
                        <option value="">-- Tidak ada --</option>
                        @foreach($catogories as $c)
                            <option value="{{ $c->id }}"
                                {{ old('kategori_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success btn-sm" type="submit">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </div>

        </form>
    </div>
</div>


{{-- ================= MODAL EDIT ================= --}}
@foreach ($employees as $e)
    <div class="modal fade" id="modalEdit{{ $e->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold">
                        <i class="fas fa-user-edit me-1"></i> Edit Pegawai
                    </h6>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>

            <form method="POST" action="{{ route('employees.update', $e->id) }}" >
                @csrf
                @method('PUT')

                <div class="modal-body row g-2">

                    {{-- NIP --}}
                    <div class="col-md-6">
                        <label>NIP</label>
                        <input name="nip"
                            value="{{ old('nip', $e->nip) }}"
                            class="form-control @error('nip') is-invalid @enderror">

                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label>Nama</label>
                        <input name="name"
                            value="{{ old('name', $e->name) }}"
                            class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div class="col-md-6">
                        <label>Jabatan</label>
                        <select name="position_id" class="form-select @error('position_id') is-invalid @enderror">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ old('position_id', $e->position_id) == $pos->id ? 'selected' : '' }}>
                                    {{ str_repeat('— ', max(0, ($pos->level ?? 0) - 1)) }}{{ $pos->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Departemen --}}
                    <div class="col-md-6">
                        <label>Departemen</label>
                        <input name="department"
                            value="{{ old('department', $e->department) }}"
                            class="form-control @error('department') is-invalid @enderror">

                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email"
                            name="email"
                            value="{{ old('email', $e->email) }}"
                            class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div class="col-md-6">
                        <label>Telepon</label>
                        <input name="phone"
                            value="{{ old('phone', $e->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror">

                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- User --}}
                    <div class="col-md-6">
                        <label>User</label>
                        <select name="user_id"
                                class="form-select @error('user_id') is-invalid @enderror">
                            <option value="">-- Tidak ada --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ old('user_id', $e->user_id) == $u->id ? 'selected' : '' }}>
                                    {{ $u->email }}
                                </option>
                            @endforeach
                        </select>

                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror">
                            <option value="">-- Tidak ada --</option>
                            @foreach($catogories as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('kategori_id', $e->kategori_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama_kategori }}
                                </option>
                            @endforeach
                        </select>

                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger btn-sm" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-warning btn-sm" type="submit">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>

            </form>
        </div>
        </div>
    </div>    

@endforeach

{{-- ================= MODAL DELETE ================= --}}
@foreach ($employees as $e)
<div class="modal fade" id="modalDelete{{ $e->id }}">
    <div class="modal-dialog modal-dialog-centered">
        

            
            <form method="POST" action="/employees/delete/{{ $e->id }}" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-header">
                <h6 class="modal-title fw-semibold">
                    <i class="fas fa-trash me-1"></i> Hapus Pegawai
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Yakin ingin menghapus Pegawai dengan nama <strong>{{ $e->name }}</strong>?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
