<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        for ($i = 1; $i <= 3; ++$i) {
            foreach ($products as $product) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'attribute_id' => ProductAttribute::all()->random()->id,
                    'additional_price' => rand(10, 100),
                ]);
            }
        }
    }
}
