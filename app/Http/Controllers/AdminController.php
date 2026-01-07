<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //

    public function index()
    {
        return view('admin.dashboard');
    }

    public function datalokasi(){

        $lokasis = lokasi::all();

        return view('admin.datalokasi', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'radius'      => 'required|integer|min:1',
        ]);

        Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'koordinat'   => $request->latitude . ',' . $request->longitude,
            'radius'      => $request->radius,
            'is_active'   => true,
        ]);


        return redirect()->back()->with('success', 'Lokasi absensi berhasil disimpan');
    }

    //  public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_lokasi' => 'required',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'radius' => 'required|numeric'
    //     ]);

    //     Lokasi::create($request->all());

    //     return back()->with('success', 'Lokasi berhasil ditambahkan');
    // }

    // public function update(Request $request, $id)
    // {
    //     Lokasi::findOrFail($id)->update($request->all());
    //     return back()->with('success', 'Lokasi berhasil diperbarui');
    // }

//     public function edit($id)
// {
//     $lokasi = Lokasi::findOrFail($id);
    
//     // Parsing koordinat string ke array
//     $koordinat = explode(',', $lokasi->koordinat);
//     $lokasi->latitude = $koordinat[0] ?? '';
//     $lokasi->longitude = $koordinat[1] ?? '';
    
//     return view('lokasi.edit', compact('lokasi'));
// }

public function update(Request $request, $id)
{
    $request->validate([
        'nama_lokasi' => 'required|string|max:255',
        'latitude'    => 'required|numeric',
        'longitude'   => 'required|numeric',
        'radius'      => 'required|integer|min:1',
        'is_active'   => 'boolean',
    ]);

    $lokasi = Lokasi::findOrFail($id);
    
    // Format koordinat
    $koordinat = $request->latitude . ',' . $request->longitude;
    
    $lokasi->update([
        'nama_lokasi' => $request->nama_lokasi,
        'koordinat'   => $koordinat,
        'radius'      => $request->radius,
        'is_active'   => $request->is_active ?? false,
    ]);

    return redirect()->back()->with('success', 'Lokasi berhasil diperbarui');
}
    

    public function destroy($id)
    {
        Lokasi::findOrFail($id)->delete();
        return back()->with('success', 'Lokasi berhasil dihapus');
    }

   public function toggle($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->is_active = !$lokasi->is_active;
        $lokasi->save();

        return back()->with('success', 'Status lokasi diperbarui');
    }


    
}
