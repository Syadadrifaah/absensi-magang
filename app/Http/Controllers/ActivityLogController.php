<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->search;

        $query = ActivityLog::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('nip', 'like', "%$search%");
                });
            })
            ->orderByDesc('created_at');

        // pagination ringan
        $logs = $query->simplePaginate(10)->withQueryString();

        // total data (hanya count, masih aman)
        $total = $query->count();

        return view('activity_logs.index', compact('logs', 'total'));
    }


    public function update(Request $request, ActivityLog $log)
    {
        $request->validate([
            'description' => 'nullable|string'
        ]);

        $log->update([
            'description' => $request->description
        ]);

        return back()->with('success', 'Deskripsi log berhasil diperbarui');
    }
}
