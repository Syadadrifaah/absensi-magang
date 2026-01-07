<?php

namespace App\Http\Controllers;

use App\Models\absensi;
use App\Models\logbook;
use auth;
use Illuminate\Http\Request;
use Symfony\Component\Clock\now;

class AbsensiController extends Controller
{
    //
    public function index()
    {
        return view('admin.absensi', [
            'absensis' => absensi::orderBy('tanggal','desc')->get(),
            'logbooks' => logbook::orderBy('tanggal','desc')->get(),
        ]);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'lokasi_id' => 'nullable|exists:tbl_lokasi,id',
    //         'latitude'  => 'required',
    //         'longitude' => 'required',
    //     ]);

    //     Absensi::create([
    //         'user_id'        => null, // 🔴 TIDAK PAKAI AUTH
    //         'lokasi_id'      => $request->lokasi_id ?? null,
    //         'tanggal'        => now()->toDateString(),
    //         'jam'            => now()->toTimeString(),
    //         'status'         => 'hadir',
    //         'koordinat_user' => "{$request->latitude},{$request->longitude}",
    //     ]);

    //     return back()->with('success', 'Absensi berhasil');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'lokasi_id' => 'required|exists:tbl_lokasi,id',
    //         'latitude'  => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //     ]);

    //     // Ambil lokasi kantor
    //     $lokasi = \App\Models\lokasi::where('id', $request->lokasi_id)
    //                 ->where('is_active', true)
    //                 ->first();

    //     if (!$lokasi) {
    //         return back()->with('error', 'Lokasi absensi tidak ditemukan');
    //     }

    //     // Hitung jarak user ke lokasi kantor
    //     $jarak = $this->hitungJarak(
    //         $request->latitude,
    //         $request->longitude,
    //         $lokasi->latitude,
    //         $lokasi->longitude
    //     );

    //     // Validasi radius
    //     if ($jarak > $lokasi->radius) {
    //         return back()->with('error', 'Anda berada di luar lokasi kantor, silakan absen di lokasi kantor');
    //     }

    //     // SIMPAN ABSENSI
    //     Absensi::create([
    //         'user_id'        => null, // TANPA AUTH
    //         'lokasi_id'      => $lokasi->id,
    //         'tanggal'        => now()->toDateString(),
    //         'jam'            => now()->toTimeString(),
    //         'status'         => 'hadir',
    //         'koordinat_user' => "{$request->latitude},{$request->longitude}",
    //     ]);

    //     return back()->with('success', 'Absensi berhasil');
    // }


    // private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    // {
    //     $earthRadius = 6371000; // meter

    //     $dLat = deg2rad($lat2 - $lat1);
    //     $dLon = deg2rad($lon2 - $lon1);

    //     $a = sin($dLat / 2) * sin($dLat / 2) +
    //         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
    //         sin($dLon / 2) * sin($dLon / 2);

    //     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    //     return $earthRadius * $c;
    // }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'latitude'  => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //     ]);

    //     $lokasi = \App\Models\lokasi::where('is_active', 1)->first();

    //     if (!$lokasi) {
    //         return back()->with('error', 'Lokasi absensi tidak tersedia');
    //     }

    //     $jarak = $this->hitungJarak(
    //         $request->latitude,
    //         $request->longitude,
    //         $lokasi->latitude,
    //         $lokasi->longitude
    //     );

    //     if ($jarak > $lokasi->radius) {
    //         return back()->with('error', 'Anda berada di luar lokasi kantor');
    //     }

    //     absensi::create([
    //         'user_id'        => 1, // sementara
    //         'lokasi_id'      => $lokasi->id,
    //         'tanggal'        => now()->format('Y-m-d'),
    //         'jam'            => now()->format('H:i:s'),
    //         'status'         => 'hadir',
    //         'koordinat_user' => $request->latitude . ',' . $request->longitude,
    //     ]);

    //     return back()->with('success', 'Absensi berhasil');
    // }


public function store(Request $request)
{
    // 1. Validasi input GPS
    $request->validate([
        'latitude'  => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    // 2. Ambil lokasi kantor aktif
    $lokasi = \App\Models\Lokasi::where('is_active', 1)->first();

    if (!$lokasi) {
        return back()->with('error', 'Lokasi absensi tidak tersedia.');
    }

    // 3. Hitung jarak user ke lokasi
    $jarak = $this->hitungJarak(
        $request->latitude,
        $request->longitude,
        $lokasi->latitude,
        $lokasi->longitude
    );

    // 4. Validasi apakah user berada di dalam radius
    if ($jarak > $lokasi->radius) {
        return back()->with('error', 'Anda berada di luar lokasi kantor.');
    }

    // 5. Simpan data absensi (sementara user_id = 1)
    \App\Models\Absensi::create([
        'user_id'        => null, // sementara
        'lokasi_id'      => $lokasi->id,
        'tanggal'        => now()->format('Y-m-d'),
        'jam'            => now()->format('H:i:s'),
        'status'         => 'hadir',
        'koordinat_user' => $request->latitude . ',' . $request->longitude,
    ]);

    return back()->with('success', 'Absensi berhasil.');
}

/**
 * Fungsi untuk menghitung jarak antara dua titik koordinat (dalam meter)
 */
private function hitungJarak($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // meter
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // jarak dalam meter
}

public function destroy($id)
{
    $absen = Absensi::findOrFail($id);
    $absen->delete();

    return back()->with('success', 'Absensi berhasil dihapus');
}





}
