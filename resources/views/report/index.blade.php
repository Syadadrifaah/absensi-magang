@extends('layouts.index')

@section('judul', 'Laporan')

@section('content')

<style>
    .laporan-menu li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    border-radius: 8px;
    color: #6c757d;
    cursor: pointer;
    margin-bottom: 8px;
}

.laporan-menu li.active {
    background-color: #e9f2ff;
    color: #0d6efd;
    font-weight: 600;
}

.laporan-menu li:hover {
    background-color: #f1f1f1;
}

</style>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-semibold mb-1">Laporan</h4>
        <small class="text-muted">
            <a href="#">Dashboard</a> • Reports
        </small>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-semibold mb-4">Cetak Laporan</h5>

            <div class="row">

                {{-- SIDEBAR --}}
                <div class="col-md-3 border-end">
                    <ul class="list-unstyled laporan-menu">

                        <li class="active" data-target="pegawai">
                            <i class="fas fa-users"></i>
                            <span>Pegawai</span>
                        </li>

                        <li data-target="harian">
                            <i class="fas fa-calendar-check"></i>
                            <span>Absensi Harian</span>
                        </li>

                        <li data-target="bulanan">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Absensi Bulanan</span>
                        </li>

                    </ul>
                </div>

                {{-- FORM AREA --}}
                <div class="col-md-9 ps-4">

                    {{-- ================= PEGAWAI ================= --}}
                    <form method="GET"
                          action="{{ route('laporan.pegawai') }}"
                          class="laporan-form"
                          id="form-pegawai">

                        <h6 class="fw-semibold mb-3">Data Pegawai</h6>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">
                                Format <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <select name="format" class="form-select" required>
                                    <option value="">Pilih format</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary px-4">
                            Submit
                        </button>
                    </form>

                    {{-- ================= ABSENSI HARIAN ================= --}}
                    <form method="GET"
                          action="{{ route('laporan.absensi.harian') }}"
                          class="laporan-form d-none"
                          id="form-harian">

                        <h6 class="fw-semibold mb-3">Absensi Harian</h6>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="date"
                                       name="tanggal"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-3 col-form-label">
                                Format <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <select name="format" class="form-select" required>
                                    <option value="">Pilih format</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary px-4">
                            Submit
                        </button>
                    </form>

                    {{-- ================= ABSENSI BULANAN ================= --}}
                    <form method="GET"
                          action="{{ route('laporan.absensi.bulanan') }}"
                          class="laporan-form d-none"
                          id="form-bulanan">

                        <h6 class="fw-semibold mb-3">Absensi Bulanan</h6>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">
                                Bulan <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <select name="bulan" class="form-select" required>
                                    @for($i=1; $i<=12; $i++)
                                        <option value="{{ $i }}">
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">
                                Tahun <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <select name="tahun" class="form-select" required>
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-3 col-form-label">
                                Format <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <select name="format" class="form-select" required>
                                    <option value="">Pilih format</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary px-4">
                            Submit
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>


<script>
    const menuItems = document.querySelectorAll('.laporan-menu li');
    const forms = document.querySelectorAll('.laporan-form');

    menuItems.forEach(item => {
        item.addEventListener('click', () => {

            // aktif menu
            menuItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            // tampilkan form sesuai menu
            forms.forEach(f => f.classList.add('d-none'));
            document.getElementById('form-' + item.dataset.target)
                .classList.remove('d-none');
        });
    });
</script>

@endsection
