<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
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

        $products = Product::query()->with('seller:id,name')->orderBy('id')->get();
        if ($products->isEmpty()) {
            $this->command?->warn('No products found. Run ProductSeeder first.');

            return;
        }

        $now = now();
        $rows = [];
        $perSeller = [];

        foreach ($products as $product) {
            $json = $jsonByName->get(Str::lower(trim($product->name)));

            $gallery = collect($json['images'] ?? [])
                ->filter()
                ->unique()
                ->values();

            $thumbnail = $json['thumbnail'] ?? $product->thumbnail;

            if ($gallery->isEmpty() && $thumbnail) {
                $gallery = collect([$thumbnail]);
            } elseif ($thumbnail && ! $gallery->contains($thumbnail)) {
                $gallery = $gallery->prepend($thumbnail)->unique()->values();
            }

            // Ensure every product has at least 2 gallery slides for the details UI.
            if ($gallery->isEmpty()) {
                $gallery = collect([
                    'images/products/placeholder-1.jpg',
                    'images/products/placeholder-2.jpg',
                ]);
            } elseif ($gallery->count() === 1) {
                $gallery->push($gallery->first());
            }

            foreach ($gallery as $image) {
                $rows[] = [
                    'product_id' => $product->id,
                    'image' => $image,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $sellerName = $product->seller?->name ?? 'Unknown';
            $perSeller[$sellerName] = ($perSeller[$sellerName] ?? 0) + $gallery->count();
        }

        DB::transaction(function () use ($rows) {
            ProductImage::query()->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                ProductImage::insert($chunk);
            }
        });

        foreach ($perSeller as $seller => $count) {
            $this->command?->info(sprintf('  %-18s %3d images', $seller, $count));
        }

        $this->command?->info('Attached '.count($rows).' gallery images for '.$products->count().' products.');
    }
}
