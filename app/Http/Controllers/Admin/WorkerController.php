<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class WorkerController extends Controller
{
    // Papar senarai permohonan worker yang menunggu approval
    public function index()
    {
        $pendingWorkers = User::where('role', 'worker')
                            ->where('status', 'pending')
                            ->latest()
                            ->get();

        return view('admin.manage_workers', compact('pendingWorkers'));
    }

    // Approve akaun worker
    public function approve($id)
    {
        $worker = User::findOrFail($id);
        $worker->status = 'approved';
        $worker->save();

        return redirect()->back()->with('success', 'Worker account approved successfully. User now can login to the system and access worker functions.');
    }
}