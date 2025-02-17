<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        // Example product attributes with names and values, including phone-specific attributes
        $product_attributes = [
            // General product attributes
            [
                'name' => 'Color',
                'value' => 'Red',
            ],
            [
                'name' => 'Color',
                'value' => 'Blue',
            ],
            [
                'name' => 'Size',
                'value' => 'M',
            ],
            [
                'name' => 'Size',
                'value' => 'L',
            ],
            [
                'name' => 'Material',
                'value' => 'Cotton',
            ],
            [
                'name' => 'Material',
                'value' => 'Leather',
            ],

            // Phone-specific attributes
            [
                'name' => 'RAM',
                'value' => '6GB',
            ],
            [
                'name' => 'RAM',
                'value' => '8GB',
            ],
            [
                'name' => 'RAM',
                'value' => '12GB',
            ],
            [
                'name' => 'ROM',
                'value' => '128GB',
            ],
            [
                'name' => 'ROM',
                'value' => '256GB',
            ],
            [
                'name' => 'ROM',
                'value' => '512GB',
            ],
            [
                'name' => 'Camera',
                'value' => '12MP',
            ],
            [
                'name' => 'Camera',
                'value' => '48MP',
            ],
            [
                'name' => 'Battery',
                'value' => '4000mAh',
            ],
            [
                'name' => 'Battery',
                'value' => '5000mAh',
            ],
            [
                'name' => 'Processor',
                'value' => 'Snapdragon 888',
            ],
            [
                'name' => 'Processor',
                'value' => 'Exynos 2100',
            ],
            [
                'name' => 'Screen Size',
                'value' => '6.1 inches',
            ],
            [
                'name' => 'Screen Size',
                'value' => '6.7 inches',
            ],
            // Add more phone-specific attributes as needed
        ];

        foreach ($product_attributes as $attribute) {
            DB::table('product_attributes')->insert([
                'name' => $attribute['name'],
                'value' => $attribute['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
