<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        $product_attributes = [
            [
                'name' => 'color',
                'values' => ['White', 'Black', 'Gray', 'Green']
            ],
            [
                'name' => 'size',
                'values' => ['Small', 'Medium', 'Large', 'XL']
            ],
            [
                'name' => 'ram',
                'values' => ['4GB', '8GB', '16GB', '32GB']
            ],
            [
                'name' => 'storage',
                'values' => ['64GB', '128GB', '256GB', '512GB', '1TB']
            ],
            [
                'name' => 'material',
                'values' => ['Cotton', 'Polyester', 'Leather', 'Metal', 'Plastic']
            ]
        ];

        $productIds = Product::pluck('id')->toArray();

        $productAttributeOptionsData = [];

        foreach ($productIds as $productId) {
            foreach ($product_attributes as $product_attribute) {
                $productAttribute = ProductAttribute::create([
                    'product_id' => $productId,
                    'name' => $product_attribute['name'],
                ]);

                foreach ($product_attribute['values'] as $value) {
                    $productAttributeOptionsData[] = [
                        'product_attribute_id' => $productAttribute->id,
                        'value' => $value,
                        'additional_price' => rand(10, 50),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        ProductAttributeOption::insert($productAttributeOptionsData);
    }
}
