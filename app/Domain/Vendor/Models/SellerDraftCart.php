<?php

namespace App\Domain\Vendor\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDraftCart extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'cart_data' => 'array',
    ];
}
