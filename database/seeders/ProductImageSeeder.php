<?php

namespace Database\Seeders;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $images = [
            'images/products/light-deal-1-min.png',
            'images/products/light-deal-1-min.png',
            'images/products/light-deal-1-min.png',
            'images/products/light-deal-1-min.png',
            'images/products/light-deal-1-min.png',
            'images/products/light-deal-2-min.png',
            'images/products/light-deal-2-min.png',
            'images/products/light-deal-2-min.png',
            'images/products/light-deal-2-min.png',
            'images/products/light-deal-2-min.png',
            'images/products/light-deal-3-min.png',
            'images/products/light-deal-3-min.png',
            'images/products/light-deal-3-min.png',
            'images/products/light-deal-3-min.png',
            'images/products/light-deal-3-min.png',
            'images/products/light-deal-4-min.png',
            'images/products/light-deal-4-min.png',
            'images/products/light-deal-4-min.png',
            'images/products/light-deal-4-min.png',
            'images/products/light-deal-4-min.png',
            'images/products/light-deal-5-min.png',
            'images/products/light-deal-5-min.png',
            'images/products/light-deal-5-min.png',
            'images/products/light-deal-5-min.png',
            'images/products/light-deal-5-min.png',
            'images/products/int-pro-1-min.png',
            'images/products/int-pro-1-min.png',
            'images/products/int-pro-1-min.png',
            'images/products/int-pro-1-min.png',
            'images/products/int-pro-1-min.png',
            'images/products/int-pro-2-min.png',
            'images/products/int-pro-2-min.png',

        ];

        $products = Product::get();

        $productImages = [];

        for ($i = 1; $i <= 5; $i++) {
            foreach ($products as $product) {
                $productImages[] = [
                    'product_id' => $product->id,
                    'image' => $images[array_rand($images)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        ProductImage::insert($productImages);
    }
}
