<?php
namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'images/products/variant/automotive1.jpg',
            'images/products/variant/automotive2.jpg',
            'images/products/variant/automotive3.jpg',
            'images/products/variant/automotive4.jpg',
            'images/products/variant/automotive5.jpg',
            'images/products/variant/automotive6.jpg',
            'images/products/variant/automotive7.jpg',
            'images/products/variant/automotive8.jpg',
            'images/products/variant/automotive9.jpg',
            'images/products/variant/automotive10.jpg',
            'images/products/variant/automotive11.jpg',
            'images/products/variant/automotive12.jpg',
            'images/products/variant/automotive13.jpg',
            'images/products/variant/automotive14.jpg',
            'images/products/variant/automotive15.jpg',
            'images/products/variant/automotive16.jpg',
            'images/products/variant/automotive17.jpg',
            'images/products/variant/automotive18.jpg',
            'images/products/variant/automotive19.jpg',
            'images/products/variant/automotive20.jpg',
            'images/products/variant/automotive21.jpg',
            'images/products/variant/automotive22.jpg',
            'images/products/variant/automotive23.jpg',
            'images/products/variant/automotive24.jpg',
            'images/products/variant/appliance1.jpg',
            'images/products/variant/appliance2.jpg',
            'images/products/variant/appliance3.jpg',
            'images/products/variant/appliance4.jpg',
            'images/products/variant/appliance5.jpg',
            'images/products/variant/appliance6.jpg',
            'images/products/variant/appliance7.jpg',
            'images/products/variant/appliance8.jpg',
            'images/products/variant/appliance9.jpg',
            'images/products/variant/appliance10.jpg',
            'images/products/variant/appliance11.jpg',
            'images/products/variant/appliance12.jpg',
            'images/products/variant/appliance13.jpg',
            'images/products/variant/appliance14.jpg',
            'images/products/variant/appliance15.jpg',
            'images/products/variant/appliance16.jpg',
            'images/products/variant/appliance17.jpg',
            'images/products/variant/appliance18.jpg',
            'images/products/variant/appliance19.jpg',
            'images/products/variant/appliance20.jpg',
            'images/products/variant/appliance21.jpg',
            'images/products/variant/appliance22.jpg',
            'images/products/variant/appliance23.jpg',
            'images/products/variant/appliance24.jpg',
            'images/products/variant/fashion-1.jpg',
            'images/products/variant/fashion-2.jpg',
            'images/products/variant/fashion-3.jpg',
            'images/products/variant/fashion-4.jpg',
            'images/products/variant/fashion-5.jpg',
            'images/products/variant/fashion-6.jpg',
            'images/products/variant/fashion-7.jpg',
            'images/products/variant/fashion-8.jpg',
            'images/products/variant/fashion-9.jpg',
            'images/products/variant/fashion-10.jpg',
            'images/products/variant/fashion-11.jpg',
            'images/products/variant/fashion-12.jpg',
            'images/products/variant/fashion-13.jpg',
            'images/products/variant/fashion-14.jpg',
            'images/products/variant/fashion-15.jpg',
            'images/products/variant/fashion-16.jpg',
            'images/products/variant/fashion-17.jpg',
            'images/products/variant/fashion-18.jpg',
            'images/products/variant/fashion-19.jpg',
            'images/products/variant/fashion-20.jpg',
            'images/products/variant/fashion-21.jpg',
            'images/products/variant/fashion-22.jpg',
            'images/products/variant/fashion-23.jpg',
            'images/products/variant/fashion-24.jpg',
            'images/products/variant/electronics-1.jpg',
            'images/products/variant/electronics-2.jpg',
            'images/products/variant/electronics-3.jpg',
            'images/products/variant/electronics-4.jpg',
            'images/products/variant/electronics-5.jpg',
            'images/products/variant/electronics-6.jpg',
            'images/products/variant/electronics-7.jpg',
            'images/products/variant/electronics-8.jpg',
            'images/products/variant/electronics-9.jpg',
            'images/products/variant/electronics-10.jpg',
            'images/products/variant/electronics-11.jpg',
            'images/products/variant/electronics-12.jpg',
            'images/products/variant/electronics-13.jpg',
            'images/products/variant/electronics-14.jpg',
            'images/products/variant/electronics-15.jpg',
        ];

        $imageIndex = 0;

        $products = Product::where('seller_id', 5)->get();

        foreach ($products as $product) {
            $variantCount = rand(1, 3);
            $variantIds   = [];

            for ($i = 1; $i <= $variantCount; $i++) {
                $costPrice    = rand(100, 500);
                $markup       = rand(20, 100);
                $sellingPrice = $costPrice + $markup;

                $discountType    = fake()->randomElement([null, DiscountType::FLAT->value, DiscountType::PERCENTAGE->value]);
                $discountValue   = null;
                $discountAmount  = null;
                $discountedPrice = null;

                if ($discountType === DiscountType::PERCENTAGE->value) {
                    $discountValue   = rand(5, 30);
                    $discountAmount  = ($sellingPrice * $discountValue) / 100;
                    $discountedPrice = max(round($sellingPrice - $discountAmount, 2), 0);
                } elseif ($discountType === DiscountType::FLAT->value) {
                    $discountValue   = rand(10, 50);
                    $discountAmount  = $discountValue;
                    $discountedPrice = max(round($sellingPrice - $discountAmount, 2), 0);
                }

                $imagePath = $images[$imageIndex] ?? null;
                $imageIndex++;

                $variant = ProductVariant::create([
                    'product_id'         => $product->id,
                    'sku'                => strtoupper(Str::random(8)),
                    'image'              => $imagePath,
                    'buying_price'       => $costPrice,
                    'selling_price'      => $sellingPrice,
                    'discount_type'      => $discountType,
                    'discount_value'     => $discountValue,
                    'discount_amount'    => $discountAmount,
                    'discounted_price'   => $discountedPrice,
                    'stock_in'           => rand(10, 100),
                    'stock_out'          => rand(0, 10),
                    'low_stock_quantity' => 5,
                    'is_default'         => false,
                ]);

                $variantIds[] = $variant->id;
            }

            if (! empty($variantIds)) {
                $defaultVariantId = collect($variantIds)->random();
                ProductVariant::where('id', $defaultVariantId)->update(['is_default' => true]);
            }
        }
    }
}
