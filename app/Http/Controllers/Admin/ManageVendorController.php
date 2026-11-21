<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorApplication;
use App\Models\User;
use App\Models\Setting;

class ManageVendorController extends Controller
{
    // Papar senarai permohonan vendor
    public function index()
    {
        $applications = VendorApplication::with('user')->latest()->get();
        $slotsStatus = Setting::get('vendor_slots_status', 'closed');
        return view('admin.manage_vendor', compact('applications', 'slotsStatus'));
    }

    public function toggleSlots()
    {
        $current = Setting::get('vendor_slots_status', 'closed');
        $newStatus = $current == 'open' ? 'closed' : 'open';

        Setting::updateOrCreate(
            ['key' => 'vendor_slots_status'],
            ['value' => $newStatus]
        );

        return redirect()->back()->with('success', 'Vendor application slots is now ' . strtoupper($newStatus) . '.');
    }

    // Approve permohonan -> Auto upgrade role jadi vendor
    public function approve($id)
    {
        $application = VendorApplication::findOrFail($id);
        
        // Update status permohonan jadi approved
        $application->status = 'approved';
        $application->save();

        // Tukar role user jadi vendor
        $user = User::find($application->user_id);
        $user->role = 'vendor';
        $user->save();

        return redirect()->back()->with('success', 'Application approved! User is now a Vendor.');
    }

    // Reject permohonan
    public function reject($id)
    {
        $application = VendorApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return redirect()->back()->with('error', 'Your application is rejected.');
    }
}