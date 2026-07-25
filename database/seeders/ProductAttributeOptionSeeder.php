<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductAttributeOptionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Color' => ['Red', 'Blue', 'Black', 'White', 'Gray'],
            'Size' => ['S', 'M', 'L', 'XL', '22 Inch', '24 Inch', '27 Inch'],
            'Storage' => ['64GB', '128GB', '256GB'],
            'Material' => ['Cotton', 'Leather', 'Polyester', 'Ceramic', 'Semi-metallic'],
            'Viscosity' => ['5W-30', '10W-40', '15W-40'],
            'Socket Type' => ['H1', 'H4', 'H7'],
            'Fragrance' => ['Lemon', 'Lavender', 'Ocean Breeze'],
            'Capacity' => ['12V 60Ah', '12V 70Ah', '12V 80Ah'],
            'Type' => ['Gel', 'Spray', 'Card', 'Lead-Acid', 'AGM'],
        ];

        foreach ($data as $attributeName => $values) {
            $attribute = ProductAttribute::where('name', $attributeName)->first();

            if (! $attribute) {
                continue;
            }

            foreach ($values as $value) {
                ProductAttributeOption::firstOrCreate([
                    'product_attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
