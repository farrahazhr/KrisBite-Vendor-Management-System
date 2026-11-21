<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class SettlementController extends Controller
{
    // Display list of invoices that are pending settlement and those that are ready (Log)
    public function index()
    {
        // Fetch those that have verified stock but are not settled yet
        $pendingSettlements = Invoice::where('status', 'verified')
                            ->where('settlement_status', 'pending')
                            ->with('vendor', 'items')
                            ->latest()
                            ->get();

        // Ambil yang dah selesai kira (Log)
        $settlementLogs = Invoice::whereIn('status', ['settled', 'completed'])
                            ->with('vendor', 'items')
                            ->latest()
                            ->get();
                            
        return view('worker.settlement', compact('pendingSettlements', 'settlementLogs'));
    }

    // Simpan jualan & auto-kira komisen
    public function calculate(Request $request, $invoiceId)
    {
        $request->validate([
            'total_sales' => 'required|array',
            'total_sales.*' => 'required|numeric|min:0',
            'unsold' => 'required|array',
            'unsold.*' => 'required|integer|min:0',
        ]);

        $invoice = Invoice::findOrFail($invoiceId);

        $grandTotalSales = 0;

        // Simpan per-item
        foreach ($request->total_sales as $itemId => $salesAmount) {
            $item = InvoiceItem::find($itemId);
            if ($item) {
                $item->unsold_qty = $request->unsold[$itemId] ?? 0;
                $item->item_total_sales = $salesAmount;
                $item->save();

                $grandTotalSales += $salesAmount;
            }
        }

        $commission = $grandTotalSales * 0.20;
        $netPayout = $grandTotalSales - $commission;

        $invoice->total_sales = $grandTotalSales;
        $invoice->commission_amount = $commission;
        $invoice->net_payout = $netPayout;
        $invoice->status = 'settled';
        $invoice->save();

        return redirect()->back()->with('success', 'Sales have been successfully recorded. The commission (20%) and Vendor Payment have been calculated automatically. Awaiting Admin approval.');
    }
}