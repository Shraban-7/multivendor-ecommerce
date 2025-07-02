<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductVariantOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VariantOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch top 3 attributes to use as example (Color, Size, Material etc.)
        $attributes = Option::with('options')->take(3)->get();

        if ($attributes->isEmpty()) {
            $this->command->warn('No attributes found. Seed product_attributes and options first.');
            return;
        }

        ProductVariant::chunk(50, function ($variants) use ($attributes) {
            foreach ($variants as $variant) {
                foreach ($attributes as $attribute) {
                    $option = $attribute->options->random();

                    ProductVariantOption::firstOrCreate([
                        'product_variant_id' => $variant->id,
                        'option_value_id'    => $option->id,
                    ]);
                }
            }
        });
    }
}
