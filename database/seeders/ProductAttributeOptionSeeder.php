<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAttributeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Color' => ['Red', 'Blue', 'Black'],
            'Size' => ['M', 'L', 'XL'],
            'Storage' => ['64GB', '128GB', '256GB'],
            'Material' => ['Cotton', 'Leather', 'Polyester'],
        ];

        foreach ($data as $attributeName => $values) {
            $attribute = ProductAttribute::where('name', $attributeName)->first();
            foreach ($values as $value) {
                ProductAttributeOption::firstOrCreate([
                    'product_attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
