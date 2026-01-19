<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriEmployee;

class KategoriEmployeeController extends Controller
{
    //
    public function index()
    {
        $kategoriEmployees = KategoriEmployee::orderBy('nama_kategori')
            ->paginate(10);

        return view('kategori_employees.index', compact('kategoriEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        KategoriEmployee::create($request->all());

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        KategoriEmployee::findOrFail($id)->update($request->all());

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        KategoriEmployee::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
