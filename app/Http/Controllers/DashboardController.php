<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
     // ================= PIE CHART (HARI INI) =================
        $hadir = Absensi::where('status', 'Hadir')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $izin = Absensi::where('status', 'Izin')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $totalPegawai = Employee::count();

        $tidakHadir = $totalPegawai - ($hadir + $izin);

        // ================= BAR CHART (PER BULAN) =================
        $absensiBulanan = Absensi::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $barData = [];
        for ($i = 1; $i <= 12; $i++) {
            $barData[] = $absensiBulanan[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'hadir',
            'izin',
            'tidakHadir',
            'barData',
            'totalPegawai'
        ));

    }

    public function showData()
    {
        // ===== PIE CHART (HARI INI) =====
    $hadir = Absensi::where('status', 'Hadir')
        ->whereDate('created_at', today())
        ->distinct('employee_id')
        ->count('employee_id');

    $izin = Absensi::where('status', 'Izin')
        ->whereDate('created_at', today())
        ->distinct('employee_id')
        ->count('employee_id');

    $totalPegawai = Employee::count();

    $tidakHadir = $totalPegawai - ($hadir + $izin);

    // ===== BAR CHART (PER BULAN) =====
    $absensiBulanan = Absensi::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Isi data 12 bulan (biar ga bolong)
    $barData = [];
    for ($i = 1; $i <= 12; $i++) {
        $barData[] = $absensiBulanan[$i] ?? 0;
    }

    return view('admin.dashboard', compact(
        'hadir',
        'izin',
        'tidakHadir',
        'barData'
    ));

    }
}
