<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::select('id', 'selling_price')->with('product_attributes.options')->get();
        $variants = [];

        foreach ($products as $product) {
            $product_attributes = $product->product_attributes;

            for ($i = 0; $i < 5; $i++) {
                $attributeValues = $product_attributes->flatMap(function ($product_attribute) {
                    $option = $product_attribute->options->random();
                    return [$product_attribute->name => $option->value];
                })->toArray();

                $variants[] = [
                    'product_id' => $product->id,
                    'sku' => strtoupper(uniqid()),
                    'attributes' => json_encode($attributeValues),
                    'price' => $product->selling_price + rand(10, 100),
                    'stock' => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        ProductVariant::insert($variants);
    }
}
