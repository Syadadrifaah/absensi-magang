<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    //
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(10);

        $total = ActivityLog::count();

        return view('activity_logs.index', compact('logs', 'total'));
    }

    public function update($id)
    {
        $log = ActivityLog::findOrFail($id);

        request()->validate([
            'activity' => 'required|string'
        ]);

        $log->update([
            'activity' => request('activity'),
        ]);

        return back()->with('success', 'Deskripsi log diperbarui');
    }
}
