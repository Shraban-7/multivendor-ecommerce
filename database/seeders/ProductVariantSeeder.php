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
        $productIds = Product::pluck('id')->all();

        if (empty($productIds)) return;

        $productAttributes = ProductAttribute::with('options')
            ->whereIn('name', ['Color', 'Size', 'Storage', 'Material'])
            ->get()
            ->keyBy('name');

        $colors    = $productAttributes['Color']->options ?? collect();
        $sizes     = $productAttributes['Size']->options ?? collect();
        $storages  = $productAttributes['Storage']->options ?? collect();
        $materials = $productAttributes['Material']->options ?? collect();

        if ($colors->isEmpty() || $sizes->isEmpty() || $storages->isEmpty() || $materials->isEmpty()) return;

        $existingSkus = ProductVariant::pluck('sku')->toArray();
        $variantOptions = [];

        foreach ($productIds as $productId) {
            $variantCount = rand(3, 5);
            $usedCombinations = [];

            for ($i = 0; $i < $variantCount; $i++) {
                do {
                    $color    = $colors->random();
                    $size     = $sizes->random();
                    $storage  = $storages->random();
                    $material = $materials->random();

                    $combinationKey = implode('-', [$color->id, $size->id, $storage->id, $material->id]);
                } while (in_array($combinationKey, $usedCombinations));

                $usedCombinations[] = $combinationKey;

                do {
                    $sku = strtoupper(
                        substr($color->value, 0, 1) .
                            substr($size->value, 0, 1) .
                            substr($storage->value, 0, 1) .
                            substr($material->value, 0, 1)
                    ) . rand(1000, 9999);
                } while (in_array($sku, $existingSkus));
                $existingSkus[] = $sku;

                $variant = ProductVariant::create([
                    'product_id' => $productId,
                    'sku'        => $sku,
                    'price'      => rand(500, 1000),
                    'stock'      => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ([$color, $size, $storage, $material] as $option) {
                    $variantOptions[] = [
                        'product_variant_id' => $variant->id,
                        'product_attribute_option_id' => $option->id,
                        'additional_price' => rand(10, 50),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        $chunks = array_chunk($variantOptions, 1000);
        foreach ($chunks as $chunk) {
            ProductVariantProductAttributeOption::insert($chunk);
        }
    }
}
