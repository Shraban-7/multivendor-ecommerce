<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlashSaleSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        FlashSaleProduct::truncate();
        FlashSale::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $sellers = Seller::pluck('id')->toArray();
        if (empty($sellers)) return;

        $flashSales = [
            [
                'title' => 'Flash Sale',
                'description' => 'Limited time deals',
                'start_time' => now(),
                'end_time' => now()->addDays(7),
                'is_active' => true,
            ],
        ];

        foreach ($flashSales as $data) {
            $flashSale = FlashSale::create($data);

            $products = Product::active()->inRandomOrder()->limit(8)->get();
            foreach ($products as $product) {
                FlashSaleProduct::create([
                    'flash_sale_id' => $flashSale->id,
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id ?? $sellers[array_rand($sellers)],
                    'status' => FlashSaleProduct::STATUS_APPROVED,
                ]);
            }
        }
    }
}
