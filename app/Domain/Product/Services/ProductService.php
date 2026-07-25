<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;

class ProductService
{
    public function __construct(protected StockManagerService $stockManager) {}

    public function findBySlug(string $slug): ?Product
    {
        return Product::with(['variants', 'images', 'seller', 'category'])
            ->where('slug', $slug)
            ->first();
    }

    public function updateQuickStock(Product $product, int $stock): Product
    {
        $this->stockManager->setStock($product, null, $stock, 'Quick stock update');

        return $product->fresh();
    }
}
