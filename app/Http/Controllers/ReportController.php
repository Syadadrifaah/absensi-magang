<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\absensi;
use Illuminate\Http\Request;
use App\Exports\PegawaiExport;
use App\Exports\AbsensiHarianExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiBulananExport;

class ReportController extends Controller
{
    //

    public function index()
    {
        return view('report.index', [
            'absensi'   => absensi::all(),
            'user'      => User::all(),
        ]);
    }

   public function pegawai(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel',
        ]);

        return Excel::download(
            new PegawaiExport(),
            'laporan-pegawai.xlsx'
        );
    }

    /**
     * ======================
     * ABSENSI HARIAN
     * ======================
     */
    public function absensiHarian(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'format'  => 'required|in:excel',
        ]);

        return Excel::download(
            new AbsensiHarianExport($request->tanggal),
            'laporan-absensi-harian.xlsx'
        );
    }

    /**
     * ======================
     * ABSENSI BULANAN
     * ======================
     */
    public function absensiBulanan(Request $request)
    {
        $request->validate([
            'bulan'  => 'required',
            'tahun'  => 'required',
            'format' => 'required|in:excel',
        ]);

        return Excel::download(
            new AbsensiBulananExport($request->bulan, $request->tahun),
            'laporan-absensi-bulanan.xlsx'
        );
    }
}
