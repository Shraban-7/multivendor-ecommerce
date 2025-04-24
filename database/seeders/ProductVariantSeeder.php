<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\ProductVariantProductAttributeOption;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = Product::pluck('id')->all(); // Just product IDs

        if (empty($productIds)) return;

        // Load attribute options in one query
        $attributes = ProductAttribute::with('options')->whereIn('name', ['Color', 'Size', 'Storage', 'Material'])->get()->keyBy('name');

        $colors    = $attributes['Color']->options ?? collect();
        $sizes     = $attributes['Size']->options ?? collect();
        $storages  = $attributes['Storage']->options ?? collect();
        $materials = $attributes['Material']->options ?? collect();

        // Make all combinations in memory (Cartesian Product)
        $combinations = collect($colors)->crossJoin($sizes, $storages, $materials);

        $existingSkus = ProductVariant::pluck('sku')->toArray();
        $newVariants = [];
        $variantOptions = [];

        foreach ($productIds as $productId) {
            foreach ($combinations as $combo) {
                [$color, $size, $storage, $material] = $combo;

                // Unique SKU logic
                do {
                    $sku = strtoupper(
                        substr($color->value, 0, 1) .
                            substr($size->value, 0, 1) .
                            substr($storage->value, 0, 1) .
                            substr($material->value, 0, 1)
                    ) . rand(1000, 9999);
                } while (in_array($sku, $existingSkus));
                $existingSkus[] = $sku;

                // Create variant data row
                $newVariants[] = [
                    'product_id' => $productId,
                    'sku'        => $sku,
                    'price'      => rand(500, 1000),
                    'stock'      => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Bulk insert variants
        $chunks = array_chunk($newVariants, 500); // To avoid memory overload
        foreach ($chunks as $chunk) {
            ProductVariant::insert($chunk);
        }

        // Fetch just inserted variants
        $variants = ProductVariant::latest()->take(count($newVariants))->get();

        // Map attribute options to those variants
        $index = 0;
        foreach ($productIds as $productId) {
            foreach ($combinations as $combo) {
                [$color, $size, $storage, $material] = $combo;

                $variant = $variants[$index++] ?? null;
                if (!$variant) continue;

                foreach ([$color, $size, $storage, $material] as $option) {
                    $variantOptions[] = [
                        'product_variant_id' => $variant->id,
                        'product_attribute_option_id' => $option->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Bulk insert pivot table
        $optionChunks = array_chunk($variantOptions, 1000);
        foreach ($optionChunks as $chunk) {
            ProductVariantProductAttributeOption::insert($chunk);
        }
    }
}
