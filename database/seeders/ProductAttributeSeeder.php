<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        $products = Product::all();

        foreach ($products as $product) {
            foreach ($product_attributes as $attribute) {
                $productAttribute = ProductAttribute::create([
                    'product_id' => $product->id,
                    'name' => $attribute['name'],
                ]);

                foreach ($attribute['values'] as $value) {
                    ProductAttributeOption::create([
                        'product_attribute_id' => $productAttribute->id,
                        'value' => $value,
                        'additional_price' => rand(10,50)
                    ]);
                }
            }
        }

    }
}
