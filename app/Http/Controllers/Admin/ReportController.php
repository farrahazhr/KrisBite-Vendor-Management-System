<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Papar halaman laporan & filter
    public function index(Request $request)
    {
        // Ambil senarai vendor untuk dropdown
        $vendors = User::where('role', 'vendor')->orderBy('name')->get();

        // Mula query
        $query = Invoice::where('settlement_status', 'completed')->with('vendor', 'items');

        // Filter ikut Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }

        // Filter ikut Vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $invoices = $query->latest()->get();

        return view('admin.reports', compact('invoices', 'vendors'));
    }

    // Export laporan ke PDF
    public function exportPdf(Request $request)
    {
        $query = Invoice::where('settlement_status', 'completed')->with('vendor', 'items');

        // Apply same filters for PDF
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $invoices = $query->latest()->get();
        
        $pdf = Pdf::loadView('admin.report_pdf', compact('invoices'));
        
        return $pdf->download('Laporan_Settlement_KrisBite.pdf');
    }
}