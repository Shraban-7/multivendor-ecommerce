<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $productImages = [
            // For Samsung Galaxy S21
            [
                'product_id' => 1,
                'image' => 'images/products/electronic-prod-1.png'
            ],
            [
                'product_id' => 1,
                'image' => 'images/products/electronic-prod-2.png'
            ],
            // For Apple iPhone 13
            [
                'product_id' => 2,
                'image' => 'images/products/electronic-prod-3.png'
            ],
            [
                'product_id' => 2,
                'image' => 'images/products/electronic-prod-4.png'
            ],
            // For Sony WH-1000XM4 Headphones
            [
                'product_id' => 3,
                'image' => 'images/products/electronic-prod-5.png'
            ],
            [
                'product_id' => 3,
                'image' => 'images/products/electronic-prod-6.png'
            ],
            // For Nike Air Max 90 Shoes
            [
                'product_id' => 4,
                'image' => 'images/products/feature-product-1.png'
            ],
            [
                'product_id' => 4,
                'image' => 'images/products/feature-product-2.png'
            ],
            // For Adidas Ultraboost 21
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
            DB::table('product_images')->insert([
                'product_id' => $image['product_id'],
                'image' => $image['image'], // Path to the image
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
