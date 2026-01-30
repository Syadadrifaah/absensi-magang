<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PengaturanAbsensi;

class PengaturanAbsensiController extends Controller
{
    //
    public function index()
    {
        $data = PengaturanAbsensi::orderBy('id', 'desc')->get();
        return view('pengaturanabsen.index', compact('data'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:100',

    //         'jam_masuk_mulai'   => 'required|date_format:H:i',
    //         'jam_masuk_selesai' => 'required|date_format:H:i|after:jam_masuk_mulai',

    //         'jam_pulang_mulai'   => 'required|date_format:H:i',
    //         'jam_pulang_selesai' => 'required|date_format:H:i|after:jam_pulang_mulai',
    //     ]);

    //     PengaturanAbsensi::create([
    //         'nama' => $request->nama,

    //         'jam_masuk_mulai'   => $request->jam_masuk_mulai,
    //         'jam_masuk_selesai' => $request->jam_masuk_selesai,

    //         'jam_pulang_mulai'   => $request->jam_pulang_mulai,
    //         'jam_pulang_selesai' => $request->jam_pulang_selesai,

    //         'aktif' => false,
    //     ]);

    //     return redirect()->back()->with('success', 'Jam absensi berhasil ditambahkan');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',

            'jam_masuk_mulai'   => 'required|date_format:H:i',
            'jam_masuk_selesai' => 'required|date_format:H:i|after:jam_masuk_mulai',

            'jam_pulang_mulai'   => 'required|date_format:H:i',
            'jam_pulang_selesai' => 'required|date_format:H:i|after:jam_pulang_mulai',
        ]);

        PengaturanAbsensi::create($request->only([
            'nama',
            'jam_masuk_mulai',
            'jam_masuk_selesai',
            'jam_pulang_mulai',
            'jam_pulang_selesai',
        ]));

        return back()->with('success', 'Jam absensi berhasil ditambahkan');
    }

    // public function update(Request $request, $id)
    // {
    //     // dd($request, $id);
    //     $request->validate([
    //         'nama' => 'required|string|max:100',

    //         'jam_masuk_mulai'   => 'required|date_format:H:i',
    //         'jam_masuk_selesai' => 'required|date_format:H:i|after:jam_masuk_mulai',

    //         'jam_pulang_mulai'   => 'required|date_format:H:i',
    //         'jam_pulang_selesai' => 'required|date_format:H:i|after:jam_pulang_mulai',
    //     ]);

    //     $data = PengaturanAbsensi::findOrFail($id);

    //     $data->update([
    //         'nama' => $request->nama,
    //         'jam_masuk_mulai'   => $request->jam_masuk_mulai,
    //         'jam_masuk_selesai' => $request->jam_masuk_selesai,
    //         'jam_pulang_mulai'  => $request->jam_pulang_mulai,
    //         'jam_pulang_selesai'=> $request->jam_pulang_selesai,
    //     ]);

    //     return back()->with('success', 'Jam absensi berhasil diperbarui');
    // }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:100',

                'jam_masuk_mulai'   => 'required|date_format:H:i',
                'jam_masuk_selesai' => 'required|date_format:H:i|after:jam_masuk_mulai',

                'jam_pulang_mulai'   => 'required|date_format:H:i',
                'jam_pulang_selesai' => 'required|date_format:H:i|after:jam_pulang_mulai',
            ]);

            $data = PengaturanAbsensi::findOrFail($id);

            $data->update([
                'nama' => $request->nama,
                'jam_masuk_mulai'   => $request->jam_masuk_mulai,
                'jam_masuk_selesai' => $request->jam_masuk_selesai,
                'jam_pulang_mulai'  => $request->jam_pulang_mulai,
                'jam_pulang_selesai'=> $request->jam_pulang_selesai,
            ]);

            return back()->with('success', 'Jam absensi berhasil diperbarui');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        PengaturanAbsensi::destroy($id);
        return back()->with('success', 'Jam absensi berhasil dihapus');
    }

    // 🔥 TOGGLE AKTIF / NONAKTIF
    public function toggle($id)
    {
        $data = PengaturanAbsensi::findOrFail($id);

        // Jika ingin AKTIFKAN
        if (!$data->aktif) {

            // Nonaktifkan semua yang lain
            PengaturanAbsensi::where('aktif', true)
                ->where('id', '!=', $id)
                ->update(['aktif' => false]);

            $data->aktif = true;
        }
        // Jika ingin NONAKTIFKAN
        else {
            $data->aktif = false;
        }

        $data->save();

        return back()->with('success', 'Status jam absensi diperbarui');
    }

}
