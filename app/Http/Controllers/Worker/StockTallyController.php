<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class StockTallyController extends Controller
{
    // Papar senarai invoice yang belum verify (Step 1) & History
    public function index()
    {
        // Ambil yang belum verify (Pending)
        $pendingInvoices = Invoice::where('status', 'pending')->with('vendor', 'items')->latest()->get();
        
        // Ambil yang dah lepas pending (Verified, Settled, Completed) untuk History
        $historyInvoices = Invoice::whereIn('status', ['verified', 'settled', 'completed'])->with('vendor', 'items')->latest()->get();

        return view('worker.stock_tally', compact('pendingInvoices', 'historyInvoices'));
    }

    // Worker masukkan Actual Qty
    public function verify(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        foreach ($request->actual_qty as $itemId => $qty) {
            $item = InvoiceItem::find($itemId);
            $item->actual_qty = $qty;
            $item->save();
        }

        $invoice->status = 'verified';
        $invoice->save();

        return redirect()->back()->with('success', 'Stock has been successfully tallied and verified!');
    }

    // Worker set menu jadi Sold Out (TAK BOLEH BALIK AVAILABLE)
    public function toggleStatus($itemId)
    {
        $item = InvoiceItem::findOrFail($itemId);
        
        // Jika dah sold out, halang dia dari tukar balik
        if ($item->menu_status == 'sold_out') {
            return redirect()->back()->with('error', 'Food items marked as Sold Out cannot be changed back to Available.');
        }
        
        // Kalau available, tukar jadi sold out
        $item->menu_status = 'sold_out';
        $item->save();

        return redirect()->back()->with('success', 'Menu status has been successfully updated to Sold Out!');
    }
}