<?php

namespace App\Domain\Product\Console;

use App\Domain\Product\Database\Seeders\Support\ProductImageResolver;
use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
use App\Domain\Product\Models\ProductVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SyncProductCatalogCommand extends Command
{
    protected $signature = 'products:sync-catalog
                            {--json : Also rewrite database/data/products.json}
                            {--force-images : Replace every product image with name-matched photos (default)}';

    protected $description = 'Rename Surpass products and refresh images so thumbnails match product names';

    public function handle(): int
    {
        $brand = Brand::where('slug', 'surpass')->orWhere('name', 'Surpass')->first();

        if ($brand) {
            $brand->update([
                'name' => 'NovaTech',
                'slug' => 'novatech',
            ]);
            $this->info('Renamed brand Surpass → NovaTech');
        }

        $products = Product::query()
            ->with('category:id,name')
            ->orderBy('id')
            ->get();

        $renamed = 0;
        $imaged = 0;
        $now = now();
        $jsonMap = [];

        foreach ($products as $product) {
            $originalName = $product->name;
            $newName = $this->renameIfSurpass($originalName);

            if ($newName !== $originalName) {
                $slugBase = Str::slug($newName) ?: 'product-'.$product->id;
                $slug = $slugBase;
                $n = 1;
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $slugBase.'-'.(++$n);
                }

                $product->name = $newName;
                $product->slug = $slug;
                $renamed++;
            }

            $images = ProductImageResolver::gallery(
                $product->name,
                $product->category?->name,
                3
            );

            $product->thumbnail = $images[0];
            $product->save();

            ProductImage::where('product_id', $product->id)->delete();
            $imageRows = [];
            foreach ($images as $image) {
                $imageRows[] = [
                    'product_id' => $product->id,
                    'image' => $image,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            ProductImage::insert($imageRows);
            ProductVariant::where('product_id', $product->id)->update(['image' => $images[0]]);

            $imaged++;
            $payload = [
                'name' => $product->name,
                'thumbnail' => $images[0],
                'images' => $images,
            ];
            $jsonMap[Str::lower(trim($originalName))] = $payload;
            $jsonMap[Str::lower(trim($product->name))] = $payload;
        }

        if ($this->option('json')) {
            $this->rewriteProductsJson($jsonMap);
        }

        $this->info("Renamed {$renamed} Surpass products.");
        $this->info("Updated name-matched images for {$imaged} products.");
        $this->info('Remaining Surpass names: '.Product::where('name', 'like', '%surpass%')->count());

        return self::SUCCESS;
    }

    private function renameIfSurpass(string $name): string
    {
        if (! preg_match('/surpass/i', $name)) {
            return $name;
        }

        $clean = trim(preg_replace('/\bsurpass\b/i', '', $name));
        $clean = preg_replace('/\s+/', ' ', $clean);
        $clean = trim($clean, " -\t");
        $clean = $this->titleize($clean);
        $lower = Str::lower($clean);

        $prefix = match (true) {
            str_contains($lower, 'cable') && str_contains($lower, 'braided') => 'Braided',
            str_contains($lower, 'cable') && (str_contains($lower, 'silicon') || str_contains($lower, 'silicone')) => 'Silicone',
            str_contains($lower, 'cable') => 'Premium',
            str_contains($lower, 'adapter') || str_contains($lower, 'charger') => 'FastCharge',
            str_contains($lower, 'neckband') => 'SportBeat',
            str_contains($lower, 'airpod') || str_contains($lower, 'ear bud') || str_contains($lower, 'earbuds') || str_contains($lower, 'anc') => 'SoundPulse',
            str_contains($lower, 'earphone') => 'AudioPro',
            str_contains($lower, 'speaker') || str_contains($lower, 'boomer') => 'BoomBeat',
            str_contains($lower, 'wireless') => 'WaveLink',
            default => 'NovaTech',
        };

        $clean = preg_replace('/\b'.preg_quote($prefix, '/').'\b/i', '', $clean);
        $clean = preg_replace('/\bsilicon(e)?\b/i', '', $clean);
        $clean = trim(preg_replace('/\s+/', ' ', $clean));

        return trim($prefix.' '.$clean);
    }

    private function titleize(string $value): string
    {
        $value = str_ireplace(
            ['type c', 'type-c', 'usb to ip', 'lighting'],
            ['Type-C', 'Type-C', 'USB to Lightning', 'Lightning'],
            $value
        );

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    private function rewriteProductsJson(array $jsonMap): void
    {
        $path = database_path('data/products.json');
        if (! File::exists($path)) {
            $this->warn('products.json not found, skipped JSON rewrite.');

            return;
        }

        $data = json_decode(File::get($path), true);
        $products = $data['products'] ?? [];
        $updated = 0;

        foreach ($products as &$product) {
            $key = Str::lower(trim($product['name'] ?? ''));
            $mapped = $jsonMap[$key] ?? null;

            if (! $mapped && preg_match('/surpass/i', $product['name'] ?? '')) {
                $newName = $this->renameIfSurpass($product['name']);
                $mapped = $jsonMap[Str::lower(trim($newName))] ?? null;
                if ($mapped) {
                    $product['name'] = $mapped['name'];
                } else {
                    $product['name'] = $newName;
                }
            }

            if ($mapped) {
                $product['name'] = $mapped['name'];
                $product['thumbnail'] = $mapped['thumbnail'];
                $product['images'] = $mapped['images'];
                if (isset($product['brand']) && strcasecmp((string) $product['brand'], 'Surpass') === 0) {
                    $product['brand'] = 'NovaTech';
                }
                $updated++;
            }
        }
        unset($product);

        $data['products'] = $products;
        File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->info("Updated {$updated} entries in products.json");
    }
}
