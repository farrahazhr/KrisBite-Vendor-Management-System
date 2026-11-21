<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;

class MenuController extends Controller
{
    // Papar page Manage Menu
    public function index()
    {
        // Tarik semua makanan yang ada invoice verified hari ni
        $menuItems = InvoiceItem::whereHas('invoice', function($query) {
                        $query->whereDate('invoice_date', today())
                              ->whereIn('status', ['verified', 'settled', 'completed']);
                     })
                     ->with('food.vendor.vendorApplication')
                     ->get();

        return view('worker.manage_menu', compact('menuItems'));
    }
}
