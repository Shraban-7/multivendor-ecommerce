<?php

namespace Database\Seeders;

use App\Models\ProductStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStockSeeder extends Seeder
{
    public function run()
    {
        $stocks = [
            [
                'seller_id' => 1,
                'product_id' => 1,
                'quantity' => 50,
                'buying_price' => 300.00,
                'sub_total' => 300.00 * 50,
            ],
            [
                'seller_id' => 1,
                'product_id' => 2,
                'quantity' => 30,
                'buying_price' => 150.00,
                'sub_total' => 150.00 * 30,
            ],
            [
                'seller_id' => 1,
                'product_id' => 3,
                'quantity' => 20,
                'buying_price' => 500.00,
                'sub_total' => 500.00 * 20,
            ],
            [
                'seller_id' => 1,
                'product_id' => 4,
                'quantity' => 100,
                'buying_price' => 800.00,
                'sub_total' => 800.00 * 100,
            ],
            [
                'seller_id' => 1,
                'product_id' => 5,
                'quantity' => 15,
                'buying_price' => 200.00,
                'sub_total' => 200.00 * 15,
            ],
        ];

        foreach ($stocks as $stock) {
           ProductStock::insert([
                'seller_id' => $stock['seller_id'],
                'product_id' => $stock['product_id'],
                'quantity' => $stock['quantity'],
                'buying_price' => $stock['buying_price'],
                'sub_total' => $stock['sub_total'],
            ]);
        }
    }
}
