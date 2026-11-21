<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'food_id', 'name', 'declared_qty', 'actual_qty', 'unsold_qty', 'item_total_sales', 'price', 'menu_status'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function food()
    {
        return $this->belongsTo(VendorFood::class, 'food_id');
    }
}
