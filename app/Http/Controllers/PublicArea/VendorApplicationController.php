<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorApplication;
use App\Models\Setting;

class VendorApplicationController extends Controller
{
    public function create()
    {
        $slotsStatus = Setting::get('vendor_slots_status', 'closed');
        return view('public.apply_vendor', compact('slotsStatus'));
    }

    public function store(Request $request)
    {
                // BLOCK KALAU SLOT TUTUP
        if (Setting::get('vendor_slots_status', 'closed') == 'closed') {
            return redirect()->route('public.apply.vendor')->with('error', 'Sorry, currently the vendor slots is not open and full.');
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'business_address' => 'required|string',
            'typhoid_status' => 'required|string',
            'typhoid_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        // Upload gambar ke folder public/images/typhoid_certs
        $imageName = time().'.'.$request->typhoid_image->extension();
        $request->typhoid_image->move(public_path('images/typhoid_certs'), $imageName);

        VendorApplication::create([
            'user_id' => auth()->id(),
            'business_name' => $request->business_name,
            'owner_name' => $request->owner_name,
            'contact_info' => $request->contact_info,
            'business_address' => $request->business_address,
            'typhoid_status' => $request->typhoid_status,
            'typhoid_image_path' => 'images/typhoid_certs/'.$imageName, // Simpan path gambar
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your vendor application has been submitted successfully! Please wait for admin approval.');
    }
}