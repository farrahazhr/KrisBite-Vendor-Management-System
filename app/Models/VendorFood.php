<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorFood extends Model
{
    protected $table = 'vendor_foods';
    protected $fillable = ['vendor_id', 'name', 'price', 'image_path'];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}