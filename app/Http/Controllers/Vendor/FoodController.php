<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorFood;

class FoodController extends Controller
{
    public function index()
    {
        $foods = VendorFood::where('vendor_id', auth()->id())->latest()->get();
        return view('vendor.manage_foods', compact('foods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images/foods'), $imageName);

        VendorFood::create([
            'vendor_id' => auth()->id(),
            'name' => $request->name,
            'price' => $request->price,
            'image_path' => 'images/foods/'.$imageName,
        ]);

        return redirect()->back()->with('success', 'Food item added successfully!');
    }

    public function destroy($id)
    {
        $food = VendorFood::where('id', $id)->where('vendor_id', auth()->id())->firstOrFail();
        
        // Padam gambar dari folder
        if(file_exists(public_path($food->image_path))) {
            unlink(public_path($food->image_path));
        }
        
        $food->delete();
        return redirect()->back()->with('success', 'Food item deleted successfully!');
    }
}