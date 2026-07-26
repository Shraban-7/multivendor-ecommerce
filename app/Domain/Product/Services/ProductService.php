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
        return [
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
            'status' => Product::STATUS_ACTIVE,
        ];
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

            ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => $v['color_id'] ?? null,
                'size_id' => $v['size_id'] ?? null,
                'sku' => strtoupper(Str::slug(implode('-', $skuParts)) ?: Str::random(8)),
                'cost_price' => $v['cost_price'],
                'price' => $v['price'],
                'compare_price' => ! empty($v['compare_price']) ? $v['compare_price'] : null,
                'stock_in' => $v['stock'] ?? 0,
            ]);
        }
    }
}
