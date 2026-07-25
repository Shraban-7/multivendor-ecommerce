<?php

namespace App\Domain\Order\Models;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public static function getCount($user_id = null): int
    {
        if (is_null($user_id)) {
            $user_id = Auth::id();
        }

        $cartIds = Cart::where('user_id', $user_id)->pluck('id');

        $totalCartCount = CartItem::whereIn('cart_id', $cartIds)->count();

        return (int) $totalCartCount;
    }
}
