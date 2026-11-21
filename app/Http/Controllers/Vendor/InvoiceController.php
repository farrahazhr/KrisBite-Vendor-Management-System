<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\VendorFood;

class InvoiceController extends Controller
{
    public function create()
    {
        // get registered food
        $foods = VendorFood::where('vendor_id', auth()->id())->get();
        $invoices = Invoice::where('vendor_id', auth()->id())->with('items')->latest()->get();

        return view('vendor.create_invoice', compact('foods', 'invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'items' => 'required|array',
            'items.*.food_id' => 'required|exists:vendor_foods,id',
            'items.*.declared_qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'vendor_id' => auth()->id(),
            'invoice_date' => $request->invoice_date, // vendor choose date
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            $food = VendorFood::find($item['food_id']);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'food_id' => $food->id, // Save food_id
                'name' => $food->name, // Save for historical record
                'price' => $item['price'],
                'declared_qty' => $item['declared_qty'],
                'actual_qty' => null,
                'menu_status' => 'available',
            ]);
        }

        return redirect()->back()->with('success', 'Daily invoice submitted successfully!');
    }
}