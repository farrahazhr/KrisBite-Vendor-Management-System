<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;

class MenuController extends Controller
{
    // Papar menu hari ini berdasarkan invoice vendor yang dah di-verify
    public function index()
    {
        // Cari makanan yang ada invoice hari ini DAN status invoice dah 'verified' atau 'settled'
        $menuItems = InvoiceItem::whereHas('invoice', function($query) {
                        $query->whereDate('invoice_date', today())
                              ->whereIn('status', ['verified', 'settled', 'completed']);
                     })
                     ->with('food.vendor.vendorApplication')
                     ->get();

        return view('public.menu', compact('menuItems'));
    }
}