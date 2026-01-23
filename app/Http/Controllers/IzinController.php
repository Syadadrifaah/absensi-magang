<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Izin;
use App\Models\User;
use App\Models\absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    //
    public function index()
    {
        $izins = Izin::with('user')->latest()->get();
        $users = User::orderBy('name')->get();

        return view('izin.index', compact('izins','users'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'jenis' => 'required',
    //         'surat' => 'nullable|mimes:pdf|max:2048'
    //     ]);

    //     DB::transaction(function () use ($request) {

    //         $mulai = Carbon::parse($request->tanggal_mulai);
    //         $selesai = Carbon::parse($request->tanggal_selesai);
    //         $jumlahHari = $mulai->diffInDays($selesai) + 1;

    //         $surat = null;
    //         if ($jumlahHari > 3 && $request->hasFile('surat')) {
    //             $surat = $request->file('surat')->store('surat-izin','public');
    //         }

    //         $izin = Izin::create([
    //             'user_id' => $request->user_id,
    //             'tanggal_mulai' => $mulai,
    //             'tanggal_selesai' => $selesai,
    //             'jenis' => $request->jenis,
    //             'keterangan' => $request->keterangan,
    //             'surat' => $surat
    //         ]);

    //         // UPDATE / CREATE ABSENSI
    //         for ($i = 0; $i < $jumlahHari; $i++) {
    //             $tanggal = $mulai->copy()->addDays($i)->toDateString();

    //             absensi::updateOrCreate(
    //                 [
    //                     'user_id' => $request->user_id,
    //                     'tanggal' => $tanggal
    //                 ],
    //                 [
    //                     'status' => $request->jenis,
    //                     // 'keterangan' => 'Izin',
    //                     'jam_masuk' => null,
    //                     'jam_pulang' => null
    //                 ]
    //             );
    //         }
    //     });

    //     return back()->with('success','Izin berhasil disimpan');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis' => 'required',
            'surat' => 'nullable|mimes:pdf|max:2048'
        ]);

        DB::transaction(function () use ($request) {

            $mulai = Carbon::parse($request->tanggal_mulai);
            $selesai = Carbon::parse($request->tanggal_selesai);
            $jumlahHari = $mulai->diffInDays($selesai) + 1;

            $surat = null;
            if ($jumlahHari > 3 && $request->hasFile('surat')) {
                $surat = $request->file('surat')->store('surat-izin','public');
            }

            $izin = Izin::create([
                'user_id' => $request->user_id,
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $selesai,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
                'surat' => $surat
            ]);

            // UPDATE / CREATE ABSENSI
            for ($i = 0; $i < $jumlahHari; $i++) {
                $tanggal = $mulai->copy()->addDays($i)->toDateString();

                absensi::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'tanggal' => $tanggal
                    ],
                    [
                        'status' => $request->jenis,
                        'keterangan' => 'Tepat_Waktu',
                        'jam_masuk' => null,
                        'jam_pulang' => null
                    ]
                );
            }
        });

        return back()->with('success','Izin berhasil disimpan');
    }

    public function update(Request $request, $id)
{
    $izin = Izin::findOrFail($id);

    $request->validate([
        'tanggal_mulai'   => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'jenis'           => 'required',
        'keterangan'      => 'nullable',
        'surat'           => 'nullable|mimes:pdf|max:2048'
    ]);

    DB::transaction(function () use ($request, $izin) {

        // DATA LAMA
        $oldMulai   = Carbon::parse($izin->tanggal_mulai);
        $oldSelesai = Carbon::parse($izin->tanggal_selesai);
        $oldDays    = $oldMulai->diffInDays($oldSelesai) + 1;

        // DATA BARU
        $newMulai   = Carbon::parse($request->tanggal_mulai);
        $newSelesai = Carbon::parse($request->tanggal_selesai);
        $newDays    = $newMulai->diffInDays($newSelesai) + 1;

        // FILE SURAT (OPTIONAL)
        if ($request->hasFile('surat')) {
            if ($izin->surat && Storage::disk('public')->exists($izin->surat)) {
                Storage::disk('public')->delete($izin->surat);
            }

            $izin->surat = $request->file('surat')->store('surat-izin', 'public');
        }

        // UPDATE DATA IZIN
        $izin->update([
            'tanggal_mulai'   => $newMulai,
            'tanggal_selesai' => $newSelesai,
            'jenis'           => $request->jenis,
            'keterangan'      => $request->keterangan,
        ]);

        // 🔹 KASUS 1 HARI → UPDATE SAJA
        if ($oldDays == 1 && $newDays == 1) {

            Absensi::where('user_id', $izin->user_id)
                ->whereDate('tanggal', $oldMulai->toDateString())
                ->update([
                    'tanggal'   => $newMulai->toDateString(),
                    'status'    => $request->jenis,
                    'jam_masuk' => null,
                    'jam_pulang'=> null,
                ]);

        } else {
            // 🔥 KASUS > 1 HARI → HAPUS & BUAT ULANG

            Absensi::where('user_id', $izin->user_id)
                ->whereBetween('tanggal', [
                    $oldMulai->toDateString(),
                    $oldSelesai->toDateString()
                ])
                ->where('status', $izin->jenis)
                ->delete();

            for ($i = 0; $i < $newDays; $i++) {
                $tanggal = $newMulai->copy()->addDays($i)->toDateString();

                Absensi::updateOrCreate(
                    [
                        'user_id' => $izin->user_id,
                        'tanggal' => $tanggal
                    ],
                    [
                        'status'     => $request->jenis,
                        'jam_masuk'  => null,
                        'jam_pulang' => null,
                    ]
                );
            }
        }

    });

    return back()->with('success', 'Data izin & absensi berhasil diperbarui');
}

   public function destroy($id)
{
    $izin = Izin::findOrFail($id);

    DB::transaction(function () use ($izin) {

        $mulai   = Carbon::parse($izin->tanggal_mulai);
        $selesai = Carbon::parse($izin->tanggal_selesai);
        $days    = $mulai->diffInDays($selesai) + 1;

        // 🔹 JIKA 1 HARI → KEMBALIKAN HADIR
        if ($days == 1) {

            Absensi::where('user_id', $izin->user_id)
                ->whereDate('tanggal', $mulai->toDateString())
                ->update([
                    'status'     => 'Hadir',
                    'jam_masuk'  => now()->format('H:i'),
                    'jam_pulang' => null
                ]);

        } else {
            // 🔥 JIKA > 1 HARI → HAPUS ABSENSI IZIN
            Absensi::where('user_id', $izin->user_id)
                ->whereBetween('tanggal', [
                    $mulai->toDateString(),
                    $selesai->toDateString()
                ])
                ->where('status', $izin->jenis)
                ->delete();
        }

        // HAPUS FILE SURAT
        if ($izin->surat && Storage::disk('public')->exists($izin->surat)) {
            Storage::disk('public')->delete($izin->surat);
        }

        $izin->delete();
    });

    return back()->with('success', 'Data izin & absensi berhasil dihapus');
}

}
