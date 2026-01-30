@extends('layouts.index')

@section('judul','Data Absensi Pegawai')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h4 class="fw-semibold mb-1">Data Absensi</h4>
        <small class="text-muted">
            <a href="#">Dashboard</a> • Data Absensi
        </small>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-calendar-check me-1"></i> Data Absensi Pegawai
            </h5>
        </div>

        


        {{-- TABLE --}}
        <div class="card-body p-0">

            <form method="GET" action="{{ route('absensi.dataabsensi') }}" class="mb-3">
            <div class="row g-2 align-items-end justify-content-center p-3">

                {{-- SEARCH --}}
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama atau email..."
                        value="{{ request('search') }}">
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date"
                        name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}">
                </div>

                {{-- DATE TO --}}
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date"
                        name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}">
                </div>

                {{-- BUTTON --}}
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100">
                        Filter
                    </button>

                    <a href="{{ route('absensi.dataabsensi') }}"
                    class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Pegawai</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $i => $a)
                        <tr>
                            <td class="text-center">
                                {{ $absensis->firstItem() + $i }}
                            </td>

                            <td class="text-center">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $a->user->employee->name ?? $a->user->name }}
                                </div>
                                <small class="text-muted"><span class="badge bg-info text-white">{{ $a->user->email }}</span></small>
                            </td>

                            <td class="text-center">{{ $a->jam_masuk ?? '-' }}</td>
                            <td class="text-center">{{ $a->jam_pulang ?? '-' }}</td>

                            <td class="text-center">
                                <span class="badge fs-6 
                                    {{ $a->status == 'Hadir' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td>
                                @if($a->keterangan == 'Terlambat')
                                    <span class="badge bg-warning fs-6 text-dark">
                                        {{ $a->keterangan }}
                                    </span>
                                @elseif($a->keterangan == 'Tepat_Waktu')
                                    <span class="badge bg-success fs-6 ">
                                        Tepat Waktu     
                                    </span>
                                @elseif($a->keterangan == 'Pulang_Cepat')
                                    <span class="badge bg-danger fs-6 text-white ">
                                        Pulang Cepat     
                                    </span>
                                @elseif($a->keterangan == 'Terlambat_Pulang_Cepat')
                                    <span class="badge bg-danger fs-6 ">
                                        Terlambat & Pulang Cepat     
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif      

                            </td>

                            <td class="text-center">
                                @if(auth()->user()->hasRole('Admin'))
                                    <button class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $a->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                @endif
                                @if(auth()->user()->hasRole('Admin'))
                                    <button class="btn btn-sm btn-outline-danger btn-delete-absensi"
                                        data-id="{{ $a->id }}"
                                        data-nama="{{ $a->user->employee->name ?? $a->user->name }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}"
                                        data-action="{{ route('absensi.destroy', $a->id) }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteAbsensiModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif


                                <button class="btn btn-sm btn-outline-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $a->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                       
                        @empty  
                     <tr>
                            <td colspan="7"
                                class="text-center text-muted py-4">
                                Data absensi tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

         {{-- ================= MODAL EDIT ================= --}}
        @foreach ($absensis as $a)
            <div class="modal fade"
                id="modalEdit{{ $a->id }}"
                tabindex="-1"
                aria-labelledby="modalEditLabel{{ $a->id }}"
                aria-hidden="true">

                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        {{-- HEADER --}}
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalEditLabel{{ $a->id }}">
                                Edit Absensi
                            </h1>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>

                        {{-- FORM --}}
                        <form method="POST" action="{{ route('absensi.updatedataabsensi', $a->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- BODY --}}
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Jam Masuk</label>
                                    <input type="time"
                                        name="jam_masuk"
                                        value="{{ $a->jam_masuk }}"
                                        class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jam Pulang</label>
                                    <input type="time"
                                        name="jam_pulang"
                                        value="{{ $a->jam_pulang }}"
                                        class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Hadir" {{ $a->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="Izin" {{ $a->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="Alpha" {{ $a->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                        <option value="Sakit" {{ $a->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <select name="keterangan" id="" class="form-select">
                                        <option value="-" {{ $a->keterangan == '-' ? 'selected' : '' }}>-</option>
                                        <option value="Terlambat" {{ $a->keterangan == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                        <option value="Tepat_Waktu" {{ $a->keterangan == 'Tepat_Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                                        <option value="Pulang_Cepat" {{ $a->keterangan == 'Pulang_Cepat' ? 'selected' : '' }}>Pulang Cepat</option>
                                        <option value="Terlambat_Pulang_Cepat" {{ $a->keterangan == 'Terlambat_Pulang_Cepat' ? 'selected' : '' }}>Terlambat & Pulang Cepat</option>
                                    </select>
                                </div>

                            </div>

                            {{-- FOOTER --}}
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>

                                <button type="submit"
                                        class="btn btn-primary">
                                    Save changes
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($absensis as $a)
        {{-- ================= MODAL DETAIL ================= --}}
        <div class="modal fade" id="modalDetail{{ $a->id }}">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-semibold">
                            Detail Absensi
                        </h6>
                        <button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Pegawai:</strong>
                            {{ $a->user->employee->name ?? '-' }}</p>
                        <p><strong>Tanggal:</strong> {{ $a->tanggal }}</p>
                        <p><strong>Status:</strong> {{ $a->status }}</p>
                        <p><strong>Keterangan:</strong>
                            {{ $a->keterangan ?? '-' }}
                        </p>
                        <p><strong>Koordinat masuk:</strong>
                            {{ $a->koordinat_masuk ?? '-' }}
                        </p>
                        <p><strong>Koordinat pulang:</strong>
                            {{ $a->koordinat_pulang ?? '-' }}
                        </p>

                        <div class="row mt-3">
                            <div class="col-md-6 text-center">
                                <p class="fw-semibold">Foto Masuk</p>
                                @if($a->foto_masuk)
                                    <img src="{{ asset('storage/'.$a->foto_masuk) }}"
                                        class="img-fluid rounded shadow-sm">
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </div>

                            <div class="col-md-6 text-center">
                                <p class="fw-semibold">Foto Pulang</p>
                                @if($a->foto_pulang)
                                    <img src="{{ asset('storage/'.$a->foto_pulang) }}"
                                        class="img-fluid rounded shadow-sm">
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
            @endforeach 
        {{-- ================================================= --}}

        {{-- modal delete --}}

        {{-- ================= MODAL DELETE ABSENSI ================= --}}
        <div class="modal fade" id="deleteAbsensiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form id="deleteAbsensiForm" method="POST" action="">
                        @csrf
                        @method('DELETE')

                        {{-- HEADER --}}
                        <div class="modal-header border-0">
                            <button type="button"
                                    class="btn-close ms-auto"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        {{-- BODY --}}
                        <div class="modal-body text-center py-4">

                            {{-- ICON BULAT --}}
                            <div
                                class="d-inline-flex align-items-center justify-content-center
                                    rounded-circle shadow bg-light bg-opacity-10 text-danger mb-3"
                                style="width: 90px; height: 90px;">
                                <i class="fas fa-question fs-1"></i>
                            </div>

                            <h5 class="mb-1">Hapus Data Absensi?</h5>

                            <p class="text-muted mb-4">
                                Yakin ingin menghapus absensi
                                <strong id="delete-nama">—</strong>
                                <br>
                                pada tanggal <strong id="delete-tanggal">—</strong> ?
                            </p>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button type="submit"
                                        class="btn btn-danger">
                                    Ya, Hapus
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>


        {{-- PAGINATION --}}
        <div class="card-footer bg-white">
            {{ $absensis->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteAbsensiModal');
    const deleteForm  = document.getElementById('deleteAbsensiForm');
    const namaField   = document.getElementById('delete-nama');
    const tanggalField= document.getElementById('delete-tanggal');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        deleteForm.action = button.dataset.action;
        namaField.textContent = button.dataset.nama;
        tanggalField.textContent = button.dataset.tanggal;
    });
});
</script>

@endsection
