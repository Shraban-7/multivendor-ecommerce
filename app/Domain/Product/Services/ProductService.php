<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;

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
}
