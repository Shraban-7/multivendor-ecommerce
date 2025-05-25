<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function cart_items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
