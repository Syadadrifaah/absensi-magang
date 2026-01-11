<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Absensi;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('admin.absensi', [
            'absensis' => Absensi::orderBy('tanggal', 'desc')->get(),
            'logbooks' => Logbook::orderBy('tanggal', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'tipe' => 'required|in:masuk,pulang',
                'foto' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $userId = 1; // DUMMY USER
            $tanggal = now()->toDateString();
            $waktu   = now()->format('H:i:s');

            /* ================= VALIDASI LOKASI ================= */
            $lokasi = Lokasi::where('is_active', 1)->first();
            if (!$lokasi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lokasi absensi tidak aktif'
                ], 403);
            }

            /* ================= VALIDASI RADIUS ================= */
            $jarak = $this->haversine(
                $request->latitude,
                $request->longitude,
                $lokasi->latitude,
                $lokasi->longitude
            );

            if ($jarak > $lokasi->radius) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda berada di luar radius absensi'
                ], 403);
            }

            /* ================= AMBIL DATA ABSENSI HARI INI ================= */
            $absensi = Absensi::where('user_id', $userId)
                ->where('tanggal', $tanggal)
                ->first();

            /* ================= ABSEN MASUK ================= */
            if ($request->tipe === 'masuk') {

                if ($absensi) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Anda sudah absen masuk hari ini'
                    ], 422);
                }

                $jamMasukKantor = Carbon::createFromTime(00, 0);
                $keterangan = now()->gt($jamMasukKantor)
                    ? 'Terlambat'
                    : 'Tepat_Waktu';

                $fotoMasuk = $this->saveBase64Image(
                    $request->foto,
                    'absensi/masuk'
                );

                Absensi::create([
                    'user_id' => $userId,
                    'lokasi_id' => $lokasi->id,
                    'tanggal' => $tanggal,
                    'jam_masuk' => $waktu,
                    'status' => 'Hadir',
                    'keterangan' => $keterangan,
                    'foto_masuk' => $fotoMasuk,
                    'koordinat_masuk' => $request->latitude . ',' . $request->longitude,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Absen masuk berhasil'
                ]);
            }

            /* ================= ABSEN PULANG ================= */
            if ($request->tipe === 'pulang') {

                if (!$absensi) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Anda belum absen masuk'
                    ], 422);
                }

                if ($absensi->jam_pulang) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Anda sudah absen pulang'
                    ], 422);
                }

                $jamPulangStatis = Carbon::createFromTime(02, 0);

                // if (now()->lt($jamPulangStatis)) {
                //     $absensi->keterangan = 'Pulang_Cepat';
                // }
                $keterangan = $absensi->keterangan;

                if(now()->lt($jamPulangStatis)) {
                    if($keterangan === 'Terlambat'){
                        $absensi->keterangan = 'Terlambat & Pulang_Cepat';
                    }
                    $absensi->keterangan = 'Pulang_Cepat';
                }
                

                $fotoPulang = $this->saveBase64Image(
                    $request->foto,
                    'absensi/pulang'
                );

                $absensi->update([
                    'jam_pulang' => $waktu,
                    'foto_pulang' => $fotoPulang,
                    'koordinat_pulang' => $request->latitude . ',' . $request->longitude,
                    // 'keterangan' => $keterangan,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Absen pulang berhasil'
                ]);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* ================= HAVERSINE ================= */
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    /* ================= SIMPAN FOTO ================= */
    private function saveBase64Image($base64, $folder)
    {
        $image = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
        $image = base64_decode($image);

        $fileName = $folder . '/' . uniqid() . '.jpg';
        Storage::disk('public')->put($fileName, $image);

        return $fileName;
    }



    public function destroy($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return back()->with('success', 'Absensi berhasil dihapus');
    }
}
