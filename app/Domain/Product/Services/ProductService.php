<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Domain\Vendor\Models\Seller;
use App\Services\ImageOptimizerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        protected StockManagerService $stockManager,
        private readonly ProductRepositoryInterface $productRepo,
    ) {}

    public function findBySlug(string $slug): ?Product
    {
        return $this->productRepo->findBySlug($slug);
    }

    public function updateQuickStock(Product $product, int $stock): Product
    {
        $this->stockManager->setStock($product, null, $stock, 'Quick stock update');

        return $this->productRepo->findById($product->id);
    }

    public function buildProductData(array $validated, Seller $seller): array
    {
        $data = [
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'brand_id' => $validated['brand'] ?? $validated['brand_id'] ?? null,
            'name' => trim($validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'sku' => $validated['sku'] ?? null,
            'cost_price' => $validated['cost_price'],
            'price' => $validated['price'],
            'compare_price' => ! empty($validated['compare_price']) ? $validated['compare_price'] : null,
            'payment_type' => $validated['payment_type'] ?? null,
            'unit_id' => $validated['unit_id'] ?? null,
            'unit_value' => $validated['unit_value'] ?? null,
            'low_stock_quantity' => $validated['low_stock_quantity'] ?? 0,
            'status' => Product::STATUS_PENDING_APPROVAL,
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'width' => $validated['width'] ?? null,
            'length' => $validated['length'] ?? null,
            'country_of_origin' => $validated['country_of_origin'] ?? null,
            'manufacturer_name' => $validated['manufacturer_name'] ?? null,
            'manufacturer_details' => $validated['manufacturer_details'] ?? null,
        ];

        if (! empty($validated['specifications'])) {
            $specs = [];
            $lines = is_array($validated['specifications']) ? $validated['specifications'] : explode("\n", $validated['specifications']);
            foreach ($lines as $line) {
                $line = trim($line);
                if (str_contains($line, ':')) {
                    $parts = explode(':', $line, 2);
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if ($key && $value) {
                        $specs[$key] = $value;
                    }
                }
            }
            $data['specifications'] = $specs;
        }

        return $data;
    }

    public function uploadThumbnail(UploadedFile $file, string $imageFolder): string
    {
        $imageService = new ImageOptimizerService;

        return $imageService->uploadAndOptimize($file, $imageFolder);
    }

    public function replaceThumbnail(Product $product, UploadedFile $file, string $imageFolder): string
    {
        if (! empty($product->thumbnail)) {
            delete_file($product->thumbnail);
        }

        return $this->uploadThumbnail($file, $imageFolder);
    }

    public function deleteProduct(Product $product): void
    {
        if (! empty($product->thumbnail)) {
            delete_file($product->thumbnail);
        }

        foreach ($product->images as $image) {
            delete_file($image->image);
            $image->delete();
        }

        foreach ($product->variants as $variant) {
            if (! empty($variant->image)) {
                delete_file($variant->image);
            }
            $variant->delete();
        }

        $product->stock_history()->delete();
        $product->seo()?->delete();

        $this->productRepo->delete($product);
    }

    public function duplicate(Product $original, Seller $seller): Product
    {
        $newName = $original->name . ' (Copy)';
        $data = [
            'category_id' => $original->category_id,
            'subcategory_id' => $original->subcategory_id,
            'brand_id' => $original->brand_id,
            'name' => $newName,
            'short_description' => $original->short_description,
            'description' => $original->description,
            'cost_price' => $original->cost_price,
            'price' => $original->price,
            'compare_price' => $original->compare_price,
            'payment_type' => $original->payment_type?->value,
            'unit_id' => $original->unit_id,
            'unit_value' => $original->unit_value,
            'low_stock_quantity' => $original->low_stock_quantity,
            'weight' => $original->weight,
            'height' => $original->height,
            'width' => $original->width,
            'length' => $original->length,
            'seller_id' => $seller->id,
            'slug' => str_slug('products', 'slug', $newName),
            'sku' => Product::generateSku($seller->id),
            'status' => Product::STATUS_DRAFT,
            'thumbnail' => $original->thumbnail,
            'is_featured' => 0,
            'best_selling' => 0,
            'is_trending' => 0,
        ];

        $duplicated = $this->productRepo->store($data);

        foreach ($original->variants as $variant) {
            $duplicated->variants()->create([
                'color_id' => $variant->color_id,
                'size_id' => $variant->size_id,
                'sku' => strtoupper(Str::slug($duplicated->slug . '-' . ($variant->color_id ?? '') . '-' . ($variant->size_id ?? '')) . '-' . Str::random(4)),
                'cost_price' => $variant->cost_price,
                'price' => $variant->price,
                'compare_price' => $variant->compare_price,
                'stock_in' => 0,
                'stock_out' => 0,
            ]);
        }

        foreach ($original->images as $image) {
            $duplicated->images()->create(['image' => $image->image]);
        }

        if ($original->seo) {
            $duplicated->seo()->create([
                'meta_title' => $original->seo->meta_title,
                'meta_description' => $original->seo->meta_description,
                'meta_keywords' => $original->seo->meta_keywords,
                'og_title' => $original->seo->og_title,
                'og_description' => $original->seo->og_description,
                'og_image' => $original->seo->og_image,
            ]);
        }

        return $duplicated;
    }

    public function createVariants(Product $product, array $variants, Seller $seller, string $imageFolder): void
    {
        foreach ($variants as $v) {
            if (empty($v['cost_price']) || empty($v['price'])) {
                continue;
            }

            $skuParts = array_filter([
                $product->slug,
                $v['color_slug'] ?? null,
                $v['size_slug'] ?? null,
            ]);

            $sku = strtoupper(Str::slug(implode('-', $skuParts)) ?: Str::random(8));

            $skuExists = ProductVariant::where('product_id', $product->id)
                ->where('sku', $sku)
                ->exists();

            if ($skuExists) {
                $sku = $sku . '-' . Str::random(4);
            }

            ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => $v['color_id'] ?? null,
                'size_id' => $v['size_id'] ?? null,
                'sku' => $sku,
                'barcode' => $v['barcode'] ?? null,
                'cost_price' => $v['cost_price'],
                'price' => $v['price'],
                'compare_price' => ! empty($v['compare_price']) ? $v['compare_price'] : null,
                'weight' => $v['weight'] ?? null,
                'stock_in' => $v['stock'] ?? 0,
                'status' => true,
            ]);
        }
    }
}
