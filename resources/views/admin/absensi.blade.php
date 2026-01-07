@extends('layouts.index')

@section('judul', 'Absensi Karyawan')

@section('content')
<div class="container">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- ================= HEADER ACTION ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Absensi & Logbook</h5>

        <div class="d-flex gap-2">
            {{-- FORM ABSENSI --}}
            <form action="{{ route('absensi.store') }}" method="POST" id="formAbsensi">
                @csrf

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="lokasi_id" value="{{ $lokasiAktif->id ?? '' }}">

                <button class="btn btn-success">
                    <i class="bi bi-fingerprint fs-5"></i> Absensi
                </button>
            </form>


            {{-- BUTTON LOGBOOK --}}
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLogbook">
                + Logbook
            </button>
        </div>
    </div>

    {{-- ================= TABLE ABSENSI ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Data Absensi</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Koordinat</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($absensis as $absen)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $absen->tanggal }}</td>
                            <td>{{ $absen->jam }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </td>
                            <td>{{ $absen->koordinat_user }}</td>
                            <td>
                            
                            <form action="{{ route('absensi.destroy', $absen->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus absensi ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>

                        </tr>
                        
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada data absensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= TABLE LOGBOOK ================= --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">Data Logbook</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($logbooks as $logbook)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $logbook->tanggal }}</td>
                            <td>{{ $logbook->kegiatan }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditLogbook{{ $logbook->id }}">
                                        <i class="fa fa-pen"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('logbook.destroy', $logbook->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus logbook ini?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                Belum ada data logbook
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@foreach($logbooks as $logbook)
<div class="modal fade" id="modalEditLogbook{{ $logbook->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('logbook.update', $logbook->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Logbook</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $logbook->tanggal }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kegiatan</label>
                        <textarea name="kegiatan" rows="4" class="form-control" required>{{ $logbook->kegiatan }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach


{{-- ================= MODAL LOGBOOK ================= --}}
<div class="modal fade" id="modalLogbook" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('logbook.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Buat Logbook</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kegiatan</label>
                        <textarea name="kegiatan" rows="4" class="form-control" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ================= SCRIPT GPS ================= --}}
<script>
document.getElementById('formAbsensi').addEventListener('submit', function (e) {
    e.preventDefault();

    if (!navigator.geolocation) {
        alert('Browser tidak mendukung GPS');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (pos) {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
            e.target.submit();
        },
        function () {
            alert('Gagal mengambil lokasi. Izinkan akses lokasi.');
        }
    );
});

navigator.geolocation.getCurrentPosition(
    function(pos){
        document.getElementById('latitude').value = pos.coords.latitude;
        document.getElementById('longitude').value = pos.coords.longitude;
    },
    function(){
        alert('Lokasi tidak diizinkan');
    }
);
</script>
@endsection
