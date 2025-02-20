<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    public function run()
    {
        $wishlists = [
            [
                'user_id' => 1,
                'product_id' => 1,
            ],
            [
                'user_id' => 1,
                'product_id' => 3,
            ],
            [
                'user_id' => 1,
                'product_id' => 4,
            ],
            [
                'user_id' => 1,
                'product_id' => 5,
            ],
            [
                'user_id' => 1,
                'product_id' => 2,
            ],
            [
                'user_id' => 1,
                'product_id' => 5,
            ],
        ];

        foreach ($wishlists as $wishlist) {
            Wishlist::insert([
                'user_id' => $wishlist['user_id'],
                'product_id' => $wishlist['product_id'],
            ]);
        }
    }
}
