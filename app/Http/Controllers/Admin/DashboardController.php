<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorApplication;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Atas
        $totalVendors = User::where('role', 'vendor')->count();
        $pendingApps = VendorApplication::where('status', 'pending')->count();
        $todayCommission = Invoice::whereDate('created_at', today())
                                ->where('settlement_status', 'completed')
                                ->sum('commission_amount');

        // 2. REAL DATA: Monthly Commission (Ikut bulan 1 hingga 12)
        $rawMonthly = Invoice::selectRaw('MONTH(created_at) as month, SUM(commission_amount) as total')
                            ->where('settlement_status', 'completed')
                            ->whereYear('created_at', date('Y'))
                            ->groupBy('month')
                            ->pluck('total', 'month');

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            // Kalau bulan tu ada data, masukkan, kalau takde letak 0
            $monthlyData[] = isset($rawMonthly[$i]) ? round($rawMonthly[$i], 2) : 0;
        }

        // 3. REAL DATA: Top 5 Vendors (Panggil Nama Perniagaan)
        $topVendors = Invoice::selectRaw('vendor_id, SUM(commission_amount) as total_comm')
                            ->where('settlement_status', 'completed')
                            ->groupBy('vendor_id')
                            ->orderByDesc('total_comm')
                            ->take(5)
                            ->with('vendor.vendorApplication') // Eager load nama perniagaan
                            ->get();

        $topVendorLabels = $topVendors->map(function($inv) {
            // Kalau ada nama perniagaan, papar nama perniagaan, kalau takde papar nama owner
            if ($inv->vendor && $inv->vendor->vendorApplication) {
                return $inv->vendor->vendorApplication->business_name;
            }
            return $inv->vendor->name ?? 'Unknown Vendor';
        })->toArray();

        $topVendorData = $topVendors->map(function($inv) {
            return round($inv->total_comm, 2);
        })->toArray();

        // 4. Daily Summary
        $totalInvoicesSent = Invoice::whereDate('created_at', today())->count();
        $payoutToVendor = Invoice::whereDate('created_at', today())
                                ->where('settlement_status', 'completed')
                                ->sum('net_payout');
        $completedSettlements = Invoice::where('settlement_status', 'completed')->count();
        $totalActionSettlements = Invoice::where('status', 'settled')->count();
        $totalSettlements = $completedSettlements + $totalActionSettlements;
        $pendingActions = $totalActionSettlements;

        return view('admin.dashboard', compact(
            'totalVendors', 'pendingApps', 'todayCommission', 
            'monthlyData', 'topVendorLabels', 'topVendorData', 
            'totalInvoicesSent', 'payoutToVendor', 'completedSettlements', 
            'totalSettlements', 'pendingActions'
        ));
    }
}