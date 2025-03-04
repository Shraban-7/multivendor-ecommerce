<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Status
    const PENDING = 1;
    const SHIPPED = 2;
    const CANCELLED = 3;
    const DELIVERED = 4;

    // Delivery Status

    const ORDER_PLACED = 1;
    const PACKAGING = 2;
    const ON_THE_ROAD = 3;


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

}
