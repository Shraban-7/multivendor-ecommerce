<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    public function run()
    {
        $wishlists = [
            [
                'user_id' => 1, // Assuming user 1 exists
                'product_id' => 1, // Assuming product 1 exists
            ],
            [
                'user_id' => 1,
                'product_id' => 3, // Assuming product 3 exists
            ],
            [
                'user_id' => 2,
                'product_id' => 4, // Assuming product 4 exists
            ],
            [
                'user_id' => 2,
                'product_id' => 5, // Assuming product 5 exists
            ],
            [
                'user_id' => 3,
                'product_id' => 2, // Assuming product 2 exists
            ],
            [
                'user_id' => 3,
                'product_id' => 5, // Assuming product 6 exists
            ],
        ];

        foreach ($wishlists as $wishlist) {
            DB::table('wishlists')->insert([
                'user_id' => $wishlist['user_id'],
                'product_id' => $wishlist['product_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
