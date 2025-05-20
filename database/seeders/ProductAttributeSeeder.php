<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $categoryAttributeMap = [
            'fashion'         => ['Color', 'Size', 'Material'],
            'electronics'     => ['Color', 'Storage'],
            'automotive'      => ['Color', 'Size', 'Material', 'Viscosity', 'Socket Type', 'Fragrance', 'Capacity', 'Type'],
            'home-appliances' => ['Color', 'Size', 'Capacity'],
        ];

        $attributeOptions = [
            'Color'        => ['Red', 'Blue', 'Black', 'White', 'Gray'],
            'Size'         => ['S', 'M', 'L', 'XL', '22 Inch', '24 Inch', '27 Inch'],
            'Storage'      => ['64GB', '128GB', '256GB'],
            'Material'     => ['Cotton', 'Leather', 'Polyester', 'Ceramic', 'Semi-metallic','Wool'],
            'Viscosity'    => ['5W-30', '10W-40', '15W-40'],
            'Socket Type'  => ['H1', 'H4', 'H7'],
            'Fragrance'    => ['Lemon', 'Lavender', 'Ocean Breeze'],
            'Capacity'     => ['12V 60Ah', '12V 70Ah', '12V 80Ah'],
            'Type'         => ['Gel', 'Spray', 'Card', 'Lead-Acid', 'AGM'],
        ];

        foreach ($categoryAttributeMap as $categorySlug => $attributes) {
            $category = \App\Models\Category::where('slug', $categorySlug)->first();
            if (!$category) continue;

            foreach ($attributes as $attrName) {
                $attribute = \App\Models\ProductAttribute::firstOrCreate([
                    'name' => $attrName,
                    'category_id' => $category->id,
                ]);

                if (!empty($attributeOptions[$attrName])) {
                    foreach ($attributeOptions[$attrName] as $value) {
                        \App\Models\ProductAttributeOption::firstOrCreate([
                            'product_attribute_id' => $attribute->id,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }
    }
}
