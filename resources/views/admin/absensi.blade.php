@extends('layouts.index')

@section('judul', 'Absensi Karyawan')

@section('content')

<style>
    .nav-pills .nav-link {
        color: #005fbe;
        font-weight: 500;
        transition: all .2s ease-in-out;
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(0, 88, 219, 0.1);
        color: #0d6efd;
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }
</style>


<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Absensi & Logbook</h5>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            {{-- TAB NAV --}}
            <ul class="nav nav-pills mb-4" id="absensiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-absen"
                            type="button">
                        Absensi
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-data-absensi"
                            type="button">
                        Data Absensi
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-logbook"
                            type="button">
                        Logbook
                    </button>
                </li>
            </ul>

            {{-- TAB CONTENT --}}
            <div class="tab-content">

                {{-- ================= TAB 1 : ABSENSI ================= --}}
                <div class="tab-pane fade show active" id="tab-absen">
                    <div class="text-center py-5">
                        <h5 class="mb-4">Silakan lakukan absensi hari ini</h5>

                        <div class="d-flex justify-content-center gap-3">
                           <button class="btn btn-success btn-lg px-5 py-4 mb-3 w-100 d-flex align-items-center justify-content-center gap-3"
                                    style="font-size:1.2rem"
                                    data-bs-toggle="modal"
                                    data-bs-target="#absensiModal">

                                <i class="fas fa-fingerprint fa-3x"></i>
                                <span><h1>Absensi</h1></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ================= TAB 2 : DATA ABSENSI ================= --}}
                <div class="tab-pane fade" id="tab-data-absensi">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                            <span>Data Absensi</span>
                            <span class="text-muted small">Total: {{ $absensis->count() }}</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-start">Pegawai</th>
                                        <th>Tanggal</th>
                                        <th>Masuk</th>
                                        <th>Pulang</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center">
                                    @forelse($absensis as $absen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td class="text-start">
                                            <div class="fw-semibold">{{ $absen->user->name }}</div>
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $absen->jam_masuk ?? '—' }}</td>
                                        <td>{{ $absen->jam_pulang ?? '—' }}</td>

                                        <td>
                                            <span class="badge bg-success">
                                                {{ $absen->status }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ str_replace('_', ' ', $absen->keterangan) }}
                                            </span>
                                        </td>

                                        <td>
                                            <form action="{{ route('absensi.destroy', $absen->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus data ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-muted py-4">
                                            Belum ada data absensi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ================= TAB 3 : LOGBOOK ================= --}}
                <div class="tab-pane fade" id="tab-logbook">
                    <div class="card shadow-sm border-0">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Data Logbook</span>
                            <button class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalLogbook">
                                Tambah Logbook
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light text-center">
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
                                        <td class="text-start">{{ $logbook->kegiatan }}</td>
                                        <td>
                                            <form action="{{ route('logbook.destroy', $logbook->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-muted py-4">
                                            Belum ada logbook
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
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

    <!-- MODAL KAMERA-->
    <div class="modal fade" id="absensiModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <video id="video" class="w-100 rounded" autoplay></video>
                    <canvas id="canvas" class="d-none"></canvas>

                    <div class="mt-3">
                        <label>Tanggal</label>
                        <input type="text" class="form-control"
                            value="{{ now()->format('d-m-Y') }}" disabled>
                    </div>

                    <div class="mt-2">
                        <label>Koordinat</label>
                        <input type="text" id="koordinat" class="form-control" disabled>
                    </div>

                </div>

                <div class="modal-footer text-center justify-content-center gap-3">
                    <button onclick="absen('masuk')" class="btn btn-success btn-lg">
                        Absen Masuk
                    </button>
                    <button onclick="absen('pulang')" class="btn btn-danger btn-lg">
                        Absen Pulang
                    </button>
                </div>

            </div>
        </div>
    </div>

<script>
    let video, canvas, ctx, stream;

    const modal = document.getElementById('absensiModal');

    modal.addEventListener('shown.bs.modal', async () => {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        ctx = canvas.getContext('2d');

        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
    });

    modal.addEventListener('hidden.bs.modal', () => {
        if (stream) stream.getTracks().forEach(t => t.stop());
    });

    function absen(tipe) {
        navigator.geolocation.getCurrentPosition(pos => {

            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            document.getElementById('koordinat').value = lat + ',' + lng;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            const foto = canvas.toDataURL('image/jpeg');

            const formData = new FormData();
            formData.append('tipe', tipe);
            formData.append('foto', foto);
            formData.append('latitude', lat);
            formData.append('longitude', lng);

            fetch("{{ route('absensi.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
               showToast(res.message, res.status ? 'success' : 'error');

                // showToast(res.message, res.status ? 'success' : 'error');

                if (res.status) {
                    setTimeout(() => location.reload(), 1500);
                }


            })
            .catch(() => alert('Gagal terhubung ke server'));

        }, () => alert('Izin lokasi ditolak'));
    }
</script>



@endsection
