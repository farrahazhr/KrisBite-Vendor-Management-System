<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = auth()->id();

        // 1. Statistik Kotak Atas
        $todayInvoices = Invoice::where('vendor_id', $vendorId)
                            ->whereDate('created_at', today())
                            ->count();
                            
        $todaySales = Invoice::where('vendor_id', $vendorId)
                            ->whereDate('created_at', today())
                            ->sum('total_sales');
                            
        $totalCommission = Invoice::where('vendor_id', $vendorId)
                            ->where('settlement_status', 'completed')
                            ->sum('commission_amount');
                            
        $pendingSettlements = Invoice::where('vendor_id', $vendorId)
                            ->where('settlement_status', 'pending')
                            ->count();

        // 2. Today's Invoice Status (Ambil invoice hari ini besama dengan items)
        $todayInvoiceData = Invoice::with('items')
                            ->where('vendor_id', $vendorId)
                            ->whereDate('created_at', today())
                            ->get();

        // 3. Financial Summary (Keseluruhan)
        $totalSalesOverall = Invoice::where('vendor_id', $vendorId)->where('settlement_status', 'completed')->sum('total_sales');
        $totalPayoutOverall = Invoice::where('vendor_id', $vendorId)->where('settlement_status', 'completed')->sum('net_payout');

        // 4. Data Carta Bulanan (Jualan ikut bulan)
        $rawMonthly = Invoice::selectRaw('MONTH(created_at) as month, SUM(total_sales) as total')
                            ->where('vendor_id', $vendorId)
                            ->whereYear('created_at', date('Y'))
                            ->where('settlement_status', 'completed')
                            ->groupBy('month')
                            ->pluck('total', 'month');

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = isset($rawMonthly[$i]) ? round($rawMonthly[$i], 2) : 0;
        }

        // 5. REVENUE SECTION (Pending & History)
        $pendingRevenues = Invoice::where('vendor_id', $vendorId)
                            ->where('status', 'settled')
                            ->where('settlement_status', 'pending')
                            ->latest()
                            ->get();

        $historyRevenues = Invoice::where('vendor_id', $vendorId)
                            ->where('settlement_status', 'completed')
                            ->with('items')
                            ->latest()
                            ->get();

        return view('vendor.dashboard', compact(
            'todayInvoices', 'todaySales', 'totalCommission', 'pendingSettlements', 
            'todayInvoiceData', 'monthlyData', 'totalSalesOverall', 'totalPayoutOverall',
            'pendingRevenues', 'historyRevenues' // TAMBAH NI
        ));

        return view('vendor.dashboard', compact(
            'todayInvoices', 'todaySales', 'totalCommission', 'pendingSettlements', 
            'todayInvoiceData', 'monthlyData', 'totalSalesOverall', 'totalPayoutOverall'
        ));
    }
}