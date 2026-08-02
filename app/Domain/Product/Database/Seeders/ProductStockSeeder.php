<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\ProductStock;
use Illuminate\Database\Seeder;

class ProductStockSeeder extends Seeder
{
    public function run()
    {
        ProductStock::truncate();
        
        $products = \App\Domain\Product\Models\Product::all();
        foreach ($products as $product) {
            $qty = rand(20, 100);
            $cost = $product->cost_price ?: max(1, round($product->price * 0.7, 2));
            ProductStock::create([
                'seller_id' => $product->seller_id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'cost_price' => $cost,
                'sub_total' => round($cost * $qty, 2),
            ]);
        }
    }
}
