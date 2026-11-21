<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class SettlementController extends Controller
{
    // Papar senarai settlement yang dah diluluskan Admin
    public function index()
    {
        $invoices = Invoice::where('vendor_id', auth()->id())
                    ->where('settlement_status', 'completed')
                    ->with('items') // TAMBAH NI SUPAYA MODAL PAGE SETTLEMENTS BOLEH PANGGIL ITEM
                    ->latest()
                    ->get();
                    
        return view('vendor.settlements', compact('invoices'));
    }

    // Generate & Download Resit PDF
    public function downloadReceipt($id)
    {
        $invoice = Invoice::where('id', $id)
                    ->where('vendor_id', auth()->id()) // Pastikan vendor tak boleh curi resit orang lain
                    ->where('settlement_status', 'completed')
                    ->with('items') // TAMBAH NI SUPAYA PDF BOLEH PANGGIL ITEM
                    ->firstOrFail(); // GUNA firstOrFail() BUKAN get()

        $pdf = Pdf::loadView('vendor.receipt_pdf', compact('invoice'));
        
        return $pdf->download('Resit_Settlement_'.$invoice->invoice_date.'.pdf');
    }
}