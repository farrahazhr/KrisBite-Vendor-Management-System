<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Kotak Atas
        $todayInvoices = Invoice::whereDate('created_at', today())->count();
        $pendingTally = Invoice::where('status', 'pending')->count();
        
        $todaySettlements = Invoice::whereDate('created_at', today())
                                ->whereIn('status', ['settled', 'completed'])->count();
                                
        $pendingStockIn = InvoiceItem::whereNull('actual_qty')
                                ->whereHas('invoice', function($q) {
                                    $q->where('status', 'pending');
                                })->count();

        // 2. Recent Activity (5 invoice terkini yang dah siap tally atau settle)
        $recentActivities = Invoice::with('vendor')
                                ->whereIn('status', ['verified', 'settled', 'completed'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('worker.dashboard', compact(
            'todayInvoices', 
            'pendingTally', 
            'todaySettlements', 
            'pendingStockIn', 
            'recentActivities'
        ));
    }
}