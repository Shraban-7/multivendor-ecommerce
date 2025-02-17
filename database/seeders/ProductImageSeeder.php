<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $productImages = [
            [
                'product_id' => 1,
                'image' => 'images/products/electronic-prod-1.png'
            ],
            [
                'product_id' => 1,
                'image' => 'images/products/electronic-prod-2.png'
            ],
            [
                'product_id' => 2,
                'image' => 'images/products/electronic-prod-3.png'
            ],
            [
                'product_id' => 2,
                'image' => 'images/products/electronic-prod-4.png'
            ],
            [
                'product_id' => 3,
                'image' => 'images/products/electronic-prod-5.png'
            ],
            [
                'product_id' => 3,
                'image' => 'images/products/electronic-prod-6.png'
            ],
            [
                'product_id' => 4,
                'image' => 'images/products/feature-product-1.png'
            ],
            [
                'product_id' => 4,
                'image' => 'images/products/feature-product-2.png'
            ],
            [
                'product_id' => 5,
                'image' => 'images/products/feature-product-3.png'
            ],
            [
                'product_id' => 5,
                'image' => 'images/products/feature-product-4.png'
            ]
        ];

        foreach ($productImages as $image) {
            ProductImage::insert([
                'product_id' => $image['product_id'],
                'image' => $image['image'],
            ]);
        }
    }
}
