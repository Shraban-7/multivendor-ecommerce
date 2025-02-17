<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStockSeeder extends Seeder
{
    public function run()
    {
        $stocks = [
            [
                'user_id' => 1,
                'shop_id' => 1,
                'product_id' => 1,
                'quantity' => 50,
                'buying_price' => 300.00,
                'sub_total' => 300.00 * 50,
            ],
            [
                'user_id' => 2,
                'shop_id' => 2,
                'product_id' => 2,
                'quantity' => 30,
                'buying_price' => 150.00,
                'sub_total' => 150.00 * 30,
            ],
            [
                'user_id' => 3,
                'shop_id' => 1,
                'product_id' => 3,
                'quantity' => 20,
                'buying_price' => 500.00,
                'sub_total' => 500.00 * 20,
            ],
            [
                'user_id' => 4,
                'shop_id' => 3,
                'product_id' => 4,
                'quantity' => 100,
                'buying_price' => 800.00,
                'sub_total' => 800.00 * 100,
            ],
            [
                'user_id' => 5,
                'shop_id' => 2,
                'product_id' => 5,
                'quantity' => 15,
                'buying_price' => 200.00,
                'sub_total' => 200.00 * 15,
            ],
        ];

        foreach ($stocks as $stock) {
            DB::table('product_stocks')->insert([
                'user_id' => $stock['user_id'],
                'shop_id' => $stock['shop_id'],
                'product_id' => $stock['product_id'],
                'quantity' => $stock['quantity'],
                'buying_price' => $stock['buying_price'],
                'sub_total' => $stock['sub_total'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
