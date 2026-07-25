<?php

namespace App\Domain\Order\Services;

use App\Domain\Auth\Models\Customer;
use App\Domain\Order\Models\PosCart;
use App\Domain\Order\Models\PosCartItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PosCartService
{
    /**
     * Safe parameterized customer search (replaces raw SQL concatenation).
     */
    public function searchCustomers(string $term, int $limit = 10): Collection
    {
        $term = trim($term);
        if ($term === '') {
            return collect();
        }

        return Customer::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('phone', 'like', '%'.$term.'%');
            })
            ->take($limit)
            ->get();
    }

    public function clearCart(PosCart $cart): void
    {
        DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->update(['customer_id' => null, 'customer_name' => null]);
        });
    }

    public function addItem(PosCart $cart, array $attributes): PosCartItem
    {
        return $cart->items()->create($attributes);
    }
}
