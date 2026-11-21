<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['vendor_id', 'invoice_date', 'status', 'total_sales', 'commission_amount', 'net_payout', 'settlement_status'];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
