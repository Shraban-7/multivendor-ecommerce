<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        $attributeOptions = [
            'Size'         => ['S', 'M', 'L', 'XL'],
        ];

        foreach ($attributeOptions as $attrName => $options) {
            $option = Option::firstOrCreate(['name' => $attrName]);

            foreach ($options as $value) {
                OptionValue::firstOrCreate([
                    'option_id' => $option->id,
                    'value' => $value,
                ]);
            }
        }
    }

    public function run_old(): void
    {
        $attributeOptions = [
            'Color'        => ['Red', 'Blue', 'Black', 'White', 'Gray'],
            'Size'         => ['S', 'M', 'L', 'XL', '22 Inch', '24 Inch', '27 Inch'],
            'Storage'      => ['64GB', '128GB', '256GB'],
            'Material'     => ['Cotton', 'Leather', 'Polyester', 'Ceramic', 'Semi-metallic', 'Wool'],
            'Viscosity'    => ['5W-30', '10W-40', '15W-40'],
            'Socket Type'  => ['H1', 'H4', 'H7'],
            'Fragrance'    => ['Lemon', 'Lavender', 'Ocean Breeze'],
            'Capacity'     => ['12V 60Ah', '12V 70Ah', '12V 80Ah'],
            'Type'         => ['Gel', 'Spray', 'Card', 'Lead-Acid', 'AGM'],
        ];

        foreach ($attributeOptions as $attrName => $options) {
            $option = Option::firstOrCreate(['name' => $attrName]);

            foreach ($options as $value) {
                OptionValue::firstOrCreate([
                    'option_id' => $option->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
