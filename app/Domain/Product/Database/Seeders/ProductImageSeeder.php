<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Database\Seeders\Support\ProductImageResolver;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
use App\Domain\Product\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
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

        $products = Product::query()
            ->with(['seller:id,name', 'category:id,name'])
            ->orderBy('id')
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No products found. Run ProductSeeder first.');

            return;
        }

        $now = now();
        $rows = [];
        $thumbnailUpdates = [];
        $perSeller = [];

        foreach ($products as $product) {
            $json = $jsonByName->get(Str::lower(trim($product->name)));

            $gallery = ProductImageResolver::forProduct(
                name: $product->name,
                category: $product->category?->name ?? ($json['category'] ?? null),
                jsonThumbnail: $json['thumbnail'] ?? $product->thumbnail,
                jsonImages: $json['images'] ?? null,
                minImages: 2,
                forceNameMatch: false,
            );

            $thumbnailUpdates[$product->id] = $gallery[0];

            foreach ($gallery as $image) {
                $rows[] = [
                    'product_id' => $product->id,
                    'image' => $image,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $sellerName = $product->seller?->name ?? 'Unknown';
            $perSeller[$sellerName] = ($perSeller[$sellerName] ?? 0) + count($gallery);
        }

        DB::transaction(function () use ($rows, $thumbnailUpdates) {
            ProductImage::query()->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                ProductImage::insert($chunk);
            }

            foreach ($thumbnailUpdates as $productId => $thumbnail) {
                Product::where('id', $productId)->update(['thumbnail' => $thumbnail]);
                ProductVariant::where('product_id', $productId)->update(['image' => $thumbnail]);
            }
        });

        foreach ($perSeller as $seller => $count) {
            $this->command?->info(sprintf('  %-18s %3d images', $seller, $count));
        }

        $this->command?->info('Attached '.count($rows).' gallery images and synced thumbnails for '.$products->count().' products.');
    }
}
