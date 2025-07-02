<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Enums\DiscountType;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductVariantProductAttributeOption;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('seller_id',5)->get();

        foreach ($products as $product) {
            $variantCount = rand(1, 3);

            for ($i = 1; $i <= $variantCount; $i++) {
                $costPrice    = rand(100, 500);
                $markup       = rand(20, 100);
                $sellingPrice = $costPrice + $markup;

                $discountType = fake()->randomElement([null, DiscountType::FLAT, DiscountType::PERCENTAGE]);
                $discountValue = null;
                $discountAmount = null;

                if ($discountType === DiscountType::PERCENTAGE) {
                    $discountValue  = rand(5, 30);
                    $discountAmount = ($sellingPrice * $discountValue) / 100;
                } elseif ($discountType === DiscountType::FLAT) {
                    $discountValue  = rand(10, 50);
                    $discountAmount = $discountValue;
                }

                ProductVariant::create([
                    'product_id'          => $product->id,
                    'sku'                 => strtoupper(Str::random(8)),
                    'image'               => null,
                    'cost_price'          => $costPrice,
                    'selling_price'       => $sellingPrice,
                    'discount_type'       => $discountType?->value,
                    'discount_value'      => $discountValue,
                    'discount_amount'     => $discountAmount,
                    'discounted_price'     => $discountType !== null && $discountAmount !== null ? max(round($sellingPrice - $discountAmount, 2), 0) : null,
                    'stock_in'            => rand(10, 100),
                    'stock_out'           => rand(0, 10),
                    'low_stock_quantity'  => 5,
                ]);
            }
        }
    }
}
