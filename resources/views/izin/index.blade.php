@extends('layouts.index')

@section('judul', 'Data Izin Pegawai')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-0">Data Izin Pegawai</h4>
            <small class="text-muted">Kelola data izin & surat keterangan</small>
        </div>
        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalIzin">
            <i class="fas fa-plus me-1"></i> Tambah Izin
        </button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- CARD TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="50">#</th>
                        <th>Pegawai</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Surat</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izins as $i => $izin)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>

                        <td>
                            <div class="fw-semibold">{{ $izin->user->name }}</div>
                            <small class="text-muted">{{ $izin->user->email }}</small>
                        </td>

                        <td class="text-center">
                            {{ $izin->tanggal_mulai }}
                            <div class="text-muted small">s/d</div>
                            {{ $izin->tanggal_selesai }}
                        </td>

                        <td class="text-center">
                            <span class="badge bg-warning text-dark px-3 py-2">
                                {{ $izin->jenis }}
                            </span>
                        </td>

                        <td>{{ $izin->keterangan ?? '-' }}</td>

                        <td class="text-center">
                            @if($izin->surat)
                                <a href="{{ asset('storage/'.$izin->surat) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $izin->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('izin.delete', $izin->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Yakin hapus izin?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada data izin
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= MODAL INPUT IZIN ================= --}}
<div class="modal fade"
     id="modalIzin"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST"
              action="{{ route('izin.store') }}"
              enctype="multipart/form-data"
              class="modal-content">

            @csrf

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title">Tambah Izin Pegawai</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Pegawai</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Pilih Pegawai</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date"
                               id="tglMulai"
                               name="tanggal_mulai"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date"
                               id="tglSelesai"
                               name="tanggal_selesai"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Izin</label>
                        <select name="jenis" class="form-select">
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Keterangan</label>
                        <input type="text"
                               name="keterangan"
                               class="form-control"
                               placeholder="Opsional">
                    </div>

                    <div class="col-md-12 d-none" id="suratArea">
                        <label class="form-label">Surat Keterangan (PDF)</label>
                        <input type="file"
                               name="surat"
                               class="form-control"
                               accept="application/pdf" required>
                        <small class="text-muted">
                            Wajib jika izin lebih dari 3 hari
                        </small>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit"
                        class="btn btn-primary">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- modal edit izin --}}
@foreach ($izins as $izin)
    <div class="modal fade"
     id="modalEdit{{ $izin->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Izin</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <form method="POST"
                  action="{{ route('izin.update', $izin->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date"
                                   name="tanggal_mulai"
                                   value="{{ $izin->tanggal_mulai }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date"
                                   name="tanggal_selesai"
                                   value="{{ $izin->tanggal_selesai }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Izin</label>
                            <select name="jenis" class="form-select">
                                <option value="Izin" {{ $izin->jenis=='Izin'?'selected':'' }}>Izin</option>
                                <option value="Sakit" {{ $izin->jenis=='Sakit'?'selected':'' }}>Sakit</option>
                                <option value="Cuti" {{ $izin->jenis=='Cuti'?'selected':'' }}>Cuti</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Keterangan</label>
                            <input type="text"
                                   name="keterangan"
                                   value="{{ $izin->keterangan }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Surat (PDF)</label>
                            <input type="file"
                                   name="surat"
                                   class="form-control"
                                   accept="application/pdf">
                        </div>

                        @if($izin->surat)
                        <div class="col-md-12">
                            <a href="{{ asset('storage/'.$izin->surat) }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-pdf"></i> Lihat Surat
                            </a>
                        </div>
                        @endif
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endforeach




<script>
    function cekDurasiIzin() {
        let mulaiInput   = document.getElementById('tglMulai').value;
        let selesaiInput = document.getElementById('tglSelesai').value;
        let suratArea    = document.getElementById('suratArea');

        if (!mulaiInput || !selesaiInput) {
            suratArea.classList.add('d-none');
            return;
        }

        let mulai   = new Date(mulaiInput);
        let selesai = new Date(selesaiInput);

        let hari = Math.floor((selesai - mulai) / (1000 * 60 * 60 * 24)) + 1;

        // 👉 TAMPIL JIKA LEBIH DARI 3 HARI
        if (hari > 3) {
            suratArea.classList.remove('d-none');
        } else {
            suratArea.classList.add('d-none');
        }
    }

    document.getElementById('tglMulai').addEventListener('change', cekDurasiIzin);
    document.getElementById('tglSelesai').addEventListener('change', cekDurasiIzin);
</script>



@endsection