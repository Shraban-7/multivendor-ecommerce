<?php

namespace App\Domain\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now());
    }

    public function products()
    {
        return $this->hasMany(FlashSaleProduct::class);
    }

    public function approveProducts()
    {
        return $this->hasMany(FlashSaleProduct::class)->approved();
    }
}
