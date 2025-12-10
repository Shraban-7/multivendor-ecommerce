<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function products()
    {
        return $this->hasMany(FlashSaleProduct::class);
    }
}
