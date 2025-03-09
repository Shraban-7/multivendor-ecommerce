<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $productImages = [
            'images/products/light-deal-1.png',
            'images/products/light-deal-1.png',
            'images/products/light-deal-1.png',
            'images/products/light-deal-1.png',
            'images/products/light-deal-1.png',
            'images/products/light-deal-2.png',
            'images/products/light-deal-2.png',
            'images/products/light-deal-2.png',
            'images/products/light-deal-2.png',
            'images/products/light-deal-2.png',
            'images/products/light-deal-3.png',
            'images/products/light-deal-3.png',
            'images/products/light-deal-3.png',
            'images/products/light-deal-3.png',
            'images/products/light-deal-3.png',
            'images/products/light-deal-4.png',
            'images/products/light-deal-4.png',
            'images/products/light-deal-4.png',
            'images/products/light-deal-4.png',
            'images/products/light-deal-4.png',
            'images/products/light-deal-5.png',
            'images/products/light-deal-5.png',
            'images/products/light-deal-5.png',
            'images/products/light-deal-5.png',
            'images/products/light-deal-5.png',
            'images/products/int-pro-1.png',
            'images/products/int-pro-1.png',
            'images/products/int-pro-1.png',
            'images/products/int-pro-1.png',
            'images/products/int-pro-1.png',
            'images/products/int-pro-2.png',
            'images/products/int-pro-2.png',

        ];

        $products = Product::all();

        for($i=1;$i<=5;++$i)
        {
            foreach ($products as $product) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $productImages[array_rand($productImages)],
                ]);
            }
        }
    }
}
