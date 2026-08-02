<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Database\Seeders\Support\ProductImageResolver;
use App\Domain\Product\Models\Color;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/products.json');
        $jsonByName = collect();

        if (File::exists($jsonPath)) {
            $data = json_decode(File::get($jsonPath), true);
            $jsonByName = collect($data['products'] ?? [])
                ->keyBy(fn (array $p) => Str::lower(trim($p['name'] ?? '')));
        }

        $colors = Color::query()->get()->keyBy(fn (Color $c) => Str::lower($c->name));
        $sizes = Size::query()->get()->keyBy(fn (Size $s) => Str::lower($s->name));

        $products = Product::query()
            ->with(['seller:id,name', 'category:id,name', 'subcategory:id,name'])
            ->orderBy('id')
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No products found. Run ProductSeeder first.');

            return;
        }

        $now = now();
        $rows = [];
        $perSeller = [];

        foreach ($products as $product) {
            $json = $jsonByName->get(Str::lower(trim($product->name)));
            $thumbnail = $product->thumbnail
                ?: ($json['thumbnail'] ?? null)
                ?: ProductImageResolver::primary($product->name, $product->category?->name);
            $variantRows = $this->buildVariantsForProduct($product, $json, $colors, $sizes, $thumbnail, $now);

            foreach ($variantRows as $row) {
                $rows[] = $row;
            }

            $sellerName = $product->seller?->name ?? 'Unknown';
            $perSeller[$sellerName] = ($perSeller[$sellerName] ?? 0) + count($variantRows);
        }

        DB::transaction(function () use ($rows) {
            ProductVariant::query()->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                ProductVariant::insert($chunk);
            }

            $totals = ProductVariant::query()
                ->selectRaw('product_id, SUM(stock_in) as stock_in, SUM(stock_out) as stock_out')
                ->groupBy('product_id')
                ->get();

            foreach ($totals as $total) {
                Product::where('id', $total->product_id)->update([
                    'stock_in' => (int) $total->stock_in,
                    'stock_out' => (int) $total->stock_out,
                ]);
            }
        });

        foreach ($perSeller as $seller => $count) {
            $this->command?->info(sprintf('  %-18s %3d variants', $seller, $count));
        }

        $this->command?->info('Created '.count($rows).' variants for '.$products->count().' products.');
    }

    private function buildVariantsForProduct(
        Product $product,
        ?array $json,
        $colors,
        $sizes,
        ?string $thumbnail,
        $now
    ): array {
        $jsonVariants = $json['variants'] ?? [];
        $options = $json['options'] ?? [];

        if ($jsonVariants !== []) {
            $sizeValueMap = [];
            $colorValueMap = [];

            foreach ($options as $optionData) {
                $optionName = Str::lower($optionData['name'] ?? '');
                foreach ($optionData['values'] ?? [] as $value) {
                    if ($optionName === 'size') {
                        $sizeValueMap[$value] = $this->resolveSize($sizes, $value)->id;
                    }
                    if ($optionName === 'color') {
                        $colorValueMap[$value] = $this->resolveColor($colors, $value)->id;
                    }
                }
            }

            $rows = [];
            foreach ($jsonVariants as $index => $variantData) {
                $sizeId = null;
                $colorId = null;
                foreach ($variantData['value_ids'] ?? [] as $value) {
                    $sizeId ??= $sizeValueMap[$value] ?? null;
                    $colorId ??= $colorValueMap[$value] ?? null;
                }

                $price = (float) ($variantData['price'] ?? $product->price);
                $cost = (float) ($variantData['cost_price'] ?? $product->cost_price);
                if ($cost <= 0) {
                    $cost = max(1, round($price * 0.7, 2));
                }

                $compare = $variantData['compare_price'] ?? $product->compare_price;
                if ($compare !== null && (float) $compare >= $price) {
                    $compare = null;
                }

                $rows[] = $this->variantRow(
                    product: $product,
                    thumbnail: $thumbnail,
                    cost: $cost,
                    price: $price,
                    compare: $compare,
                    now: $now,
                    colorId: $colorId,
                    sizeId: $sizeId,
                    stockIn: rand(12, 40),
                );
            }

            return $rows;
        }

        // Fashion / apparel → size options (shown on details page).
        if ($this->isFashionProduct($product)) {
            $rows = [];
            foreach (['M' => 0, 'L' => 40, 'XL' => 80] as $sizeName => $bump) {
                [$price, $cost, $compare] = $this->pricedVariant($product, (float) $bump);
                $rows[] = $this->variantRow(
                    product: $product,
                    thumbnail: $thumbnail,
                    cost: $cost,
                    price: $price,
                    compare: $compare,
                    now: $now,
                    sizeId: $sizes->get(Str::lower($sizeName))?->id,
                    stockIn: rand(10, 35),
                );
            }

            return $rows;
        }

        // All other products → color options so the details page always has a variant picker.
        $rows = [];
        foreach (['Black' => 0, 'White' => 25, 'Blue' => 50] as $colorName => $bump) {
            [$price, $cost, $compare] = $this->pricedVariant($product, (float) $bump);
            $rows[] = $this->variantRow(
                product: $product,
                thumbnail: $thumbnail,
                cost: $cost,
                price: $price,
                compare: $compare,
                now: $now,
                colorId: $colors->get(Str::lower($colorName))?->id,
                stockIn: rand(15, 50),
            );
        }

        return $rows;
    }

    /** @return array{0: float, 1: float, 2: float|null} */
    private function pricedVariant(Product $product, float $bump): array
    {
        $price = round(((float) $product->price) + $bump, 2);
        $cost = (float) $product->cost_price;
        if ($cost > 0) {
            $cost = round($cost + ($bump * 0.7), 2);
        }

        $compare = $product->compare_price;
        if ($compare !== null) {
            $compare = round(((float) $compare) + $bump, 2);
            if ($compare >= $price) {
                $compare = null;
            }
        }

        return [$price, $cost, $compare];
    }

    private function resolveSize($sizes, string $value)
    {
        $key = Str::lower($value);
        if ($sizes->has($key)) {
            return $sizes->get($key);
        }

        $size = Size::firstOrCreate(
            ['slug' => Str::slug($value)],
            ['name' => $value, 'sort_order' => $sizes->count() + 1]
        );
        $sizes->put($key, $size);

        return $size;
    }

    private function resolveColor($colors, string $value)
    {
        $key = Str::lower($value);
        if ($colors->has($key)) {
            return $colors->get($key);
        }

        $color = Color::firstOrCreate(
            ['slug' => Str::slug($value)],
            ['name' => $value, 'hex_code' => '#6B7280']
        );
        $colors->put($key, $color);

        return $color;
    }

    private function isFashionProduct(Product $product): bool
    {
        $category = Str::lower($product->category?->name ?? '');
        $sub = Str::lower($product->subcategory?->name ?? '');

        return str_contains($category, 'fashion')
            || str_contains($category, 'clothing')
            || str_contains($sub, 'shirt')
            || str_contains($sub, 'pant')
            || str_contains($sub, 'dress')
            || str_contains($sub, 'wear')
            || str_contains($sub, 'top');
    }

    private function variantRow(
        Product $product,
        ?string $thumbnail,
        float|string|null $cost,
        float|string|null $price,
        float|string|null $compare,
        $now,
        ?int $colorId = null,
        ?int $sizeId = null,
        int $stockIn = 10,
    ): array {
        return [
            'product_id' => $product->id,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'sku' => strtoupper(Str::random(8)),
            'image' => $thumbnail,
            'cost_price' => $cost ?? 0,
            'price' => $price ?? 0,
            'compare_price' => $compare,
            'stock_in' => $stockIn,
            'stock_out' => 0,
            'low_stock_quantity' => 5,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
