<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class SettlementController extends Controller
{
    // Papar senarai settlement yang menunggu kelulusan
    public function index()
    {
        $invoices = Invoice::where('status', 'settled')
                    ->where('settlement_status', 'pending')
                    ->with('vendor')
                    ->get();
                    
        return view('admin.settlement', compact('invoices'));
    }

    // Approve settlement (Tukar status jadi completed & locked)
    public function approve($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->status = 'completed';

        $invoice->settlement_status = 'completed';
        $invoice->save();

        return redirect()->back()->with('success', 'Settlement approved successfully! The official receipt has been generated, and the record has been locked.');
    }
}