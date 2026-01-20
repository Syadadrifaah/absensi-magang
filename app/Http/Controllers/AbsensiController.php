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

            $lokasi = Lokasi::where('is_active', true)->first();
            if (!$lokasi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lokasi absensi tidak aktif'
                ], 403);
            }

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

            $absensi = Absensi::where('user_id', $userId)
                ->where('tanggal', $tanggal)
                ->first();
            //validasi sudah absen   
            
            if($absensi && in_array($absensi->status, ['Izin', 'Sakit', 'Cuti'])){
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak dapat melakukan absensi karena status absensi adalah ' . $absensi->status
                ], 422);
            }


            //masuk
            if ($request->tipe === 'masuk') {

                if ($absensi) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Anda sudah absen masuk hari ini'
                    ], 422);
                }

                $jamMasukKantor = Carbon::createFromTime(8, 0);
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

            //pulang
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

                $jamPulangStatis = Carbon::createFromTime(17, 0);

                $keterangan = $absensi->keterangan;

                if (now()->lt($jamPulangStatis)) {
                    if ($keterangan === 'Terlambat') {
                        $keterangan = 'Terlambat_Pulang_Cepat';
                    } else {
                        $keterangan = 'Pulang_Cepat';
                    }
                }
                
                if($absensi->status == 'Izin' || $absensi->status == 'Sakit' || $absensi->status == 'Cuti'){
                    return response()->json([
                        'status' => false,
                        'message' => 'Anda tidak dapat absen pulang karena status absensi adalah ' . $absensi->status
                    ], 422);
                }

                $fotoPulang = $this->saveBase64Image(
                    $request->foto,
                    'absensi/pulang'
                );

                $absensi->update([
                    'jam_pulang' => $waktu,
                    'foto_pulang' => $fotoPulang,
                    'koordinat_pulang' => $request->latitude . ',' . $request->longitude,
                    'keterangan' => $keterangan,
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

    public function dataabsensi(Request $request)
    {
         $query = Absensi::with(['user']);

        // 🔍 SEARCH: nama & email user
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

      
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $absensis = $query
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();


        return view('data_absensi.index', compact('absensis'));
    }

    public function updatedataabsensi(Request $request, $id)
    {
        $request->validate([
            'jam_masuk' => 'nullable',
            'jam_pulang' => 'nullable',
            'status' => 'required',
            'keterangan' => 'nullable'
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update($request->only([
            'jam_masuk',
            'jam_pulang',
            'status',
            'keterangan'
        ]));

        return back()->with('success', 'Data absensi berhasil diperbarui');
    }
}
