<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\logbook;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'kegiatan' => 'required',
        ]);

        logbook::create([
            'tanggal'  => $request->tanggal,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->back()->with('success', 'Logbook berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
        ]);

        $logbook = Logbook::findOrFail($id);
        $logbook->update([
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
        ]);

        return back()->with('success', 'Logbook berhasil diperbarui');
    }

    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);
        $logbook->delete();

        return back()->with('success', 'Logbook berhasil dihapus');
    }

}
