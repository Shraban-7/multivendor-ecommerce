<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = [
            // Variant for Samsung Galaxy S21 (Color: Black)
            [
                'product_id' => 1,  // Samsung Galaxy S21
                'attribute_id' => 1, // Color attribute (Black)
                'additional_price' => 0.00,
                'description' => 'Samsung Galaxy S21 in Black color',
            ],
            // Variant for Samsung Galaxy S21 (Color: White)
            [
                'product_id' => 1,  // Samsung Galaxy S21
                'attribute_id' => 1, // Color attribute (White)
                'additional_price' => 50.00, // Additional price for White variant
                'description' => 'Samsung Galaxy S21 in White color',
            ],
            // Variant for iPhone 13 (Size: 256GB)
            [
                'product_id' => 2,  // iPhone 13
                'attribute_id' => 2, // Size attribute (256GB)
                'additional_price' => 100.00, // Additional price for 256GB
                'description' => 'iPhone 13 with 256GB storage',
            ],
            // Variant for Nike Air Max 90 (Size: 12)
            [
                'product_id' => 4,  // Nike Air Max 90
                'attribute_id' => 2, // Size attribute (Size 12)
                'additional_price' => 20.00, // Additional price for Size 12
                'description' => 'Nike Air Max 90 in Size 12',
            ],
            // Variant for Adidas Ultraboost 21 (Color: Black)
            [
                'product_id' => 5,  // Adidas Ultraboost 21
                'attribute_id' => 1, // Color attribute (Black)
                'additional_price' => 0.00,
                'description' => 'Adidas Ultraboost 21 in Black color',
            ],
        ];

        foreach ($variants as $variant) {
            DB::table('product_variants')->insert([
                'product_id' => $variant['product_id'],
                'attribute_id' => $variant['attribute_id'],
                'additional_price' => $variant['additional_price'],
                'description' => $variant['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
