<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductUnit;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/products.json');

        if (! File::exists($jsonPath)) {
            $this->command?->warn('File not found: '.$jsonPath);

            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        $products = $data['products'] ?? [];

        if ($products === []) {
            $this->command?->warn('No products found in JSON.');

            return;
        }

        $sellers = Seller::query()->orderBy('id')->get(['id', 'name']);
        if ($sellers->isEmpty()) {
            $this->command?->error('No sellers available. Run SellerSeeder first.');

            return;
        }

        $unitId = ProductUnit::where('short_name', 'pc')->value('id')
            ?? ProductUnit::query()->value('id')
            ?? 1;

        $categoriesBySlug = Category::query()->get(['id', 'slug', 'name', 'category_id'])->keyBy('slug');
        $brandsBySlug = Brand::query()->get(['id', 'slug'])->keyBy('slug');

        $perSeller = [];
        foreach ($sellers as $seller) {
            $perSeller[$seller->id] = 0;
        }

        $created = 0;

        DB::transaction(function () use (
            $products,
            $sellers,
            $unitId,
            &$categoriesBySlug,
            &$brandsBySlug,
            &$perSeller,
            &$created,
        ) {
            $slugSeen = [];
            $sellerCount = $sellers->count();

            foreach (array_values($products) as $index => $productData) {
                $seller = $sellers[$index % $sellerCount];
                $sellerId = $seller->id;

                $categorySlug = Str::slug($productData['category']);
                $category = $categoriesBySlug->get($categorySlug);
                if (! $category) {
                    $category = Category::create([
                        'name' => $productData['category'],
                        'slug' => $categorySlug,
                    ]);
                    $categoriesBySlug->put($categorySlug, $category);
                }

                $subcategorySlug = Str::slug($productData['subcategory']);
                $subcategory = $categoriesBySlug->get($subcategorySlug);
                if (! $subcategory) {
                    $subcategory = Category::create([
                        'name' => $productData['subcategory'],
                        'slug' => $subcategorySlug,
                        'category_id' => $category->id,
                    ]);
                    $categoriesBySlug->put($subcategorySlug, $subcategory);
                }

                $brandId = null;
                if (! empty($productData['brand'])) {
                    $brandSlug = Str::slug($productData['brand']);
                    $brand = $brandsBySlug->get($brandSlug);
                    if (! $brand) {
                        $brand = Brand::create([
                            'name' => $productData['brand'],
                            'slug' => $brandSlug,
                        ]);
                        $brandsBySlug->put($brandSlug, $brand);
                    }
                    $brandId = $brand->id;
                }

                $baseSlug = Str::slug($productData['name']) ?: 'product';
                $slug = $baseSlug;
                if (isset($slugSeen[$slug])) {
                    $slug = $baseSlug.'-'.($index + 1);
                }
                $slugSeen[$slug] = true;

                $price = (float) ($productData['price'] ?? 0);
                $cost = (float) ($productData['cost_price'] ?? max(1, round($price * 0.7, 2)));
                $comparePrice = $productData['compare_price'] ?? null;
                if ($comparePrice !== null && (float) $comparePrice >= $price) {
                    $comparePrice = null;
                }

                Product::create([
                    'name' => $productData['name'],
                    'slug' => $slug,
                    'thumbnail' => $productData['thumbnail'] ?? null,
                    'short_description' => Str::limit(strip_tags($productData['description'] ?? $productData['name']), 160),
                    'description' => $productData['description'] ?? '',
                    'cost_price' => $cost > 0 ? $cost : max(1, round($price * 0.7, 2)),
                    'price' => $price,
                    'compare_price' => $comparePrice,
                    'weight' => rand(1, 50) / 10,
                    'height' => rand(5, 100),
                    'width' => rand(5, 80),
                    'length' => rand(5, 120),
                    'unit_value' => 1,
                    'unit_id' => $unitId,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'brand_id' => $brandId,
                    'seller_id' => $sellerId,
                    'sku' => strtoupper(substr($category->name, 0, 2)).'-'.strtoupper(Str::random(6)),
                    'stock_in' => 0,
                    'stock_out' => 0,
                    'low_stock_quantity' => 5,
                    'views' => 0,
                    'is_trending' => (bool) rand(0, 1),
                    'best_selling' => (bool) rand(0, 1),
                    'is_featured' => (bool) rand(0, 1),
                    'status' => Product::STATUS_ACTIVE,
                ]);

                $perSeller[$sellerId]++;
                $created++;
            }
        });

        foreach ($sellers as $seller) {
            $this->command?->info(sprintf(
                '  %-18s %3d products',
                $seller->name,
                $perSeller[$seller->id] ?? 0
            ));
        }

        $this->command?->info("Created {$created} products across {$sellers->count()} sellers.");
    }
}
