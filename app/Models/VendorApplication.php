<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorApplication extends Model
{
    protected $fillable = [
        'user_id', 'business_name', 'owner_name', 'contact_info', 'business_address', 'typhoid_status','typhoid_image_path', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
