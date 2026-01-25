<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all()->sortBy(function($p) {
            return is_numeric($p->level) ? (int)$p->level : 0;
        })->values();

        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:positions,name',
            'level' => 'nullable|string',
        ]);

        Position::create($request->only('name','level'));
        return redirect()->route('positions.index')->with('success','Jabatan berhasil ditambahkan');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|unique:positions,name,'.$position->id,
            'level' => 'nullable|string',
        ]);

        $position->update($request->only('name','level'));
        return redirect()->route('positions.index')->with('success','Jabatan diperbarui');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success','Jabatan dihapus');
    }
}
