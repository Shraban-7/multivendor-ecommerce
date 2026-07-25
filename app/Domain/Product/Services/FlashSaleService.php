<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\FlashSaleRepositoryInterface;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class FlashSaleService
{
    public function __construct(
        private readonly FlashSaleRepositoryInterface $flashSaleRepo,
    ) {}

    public function getActive(): ?FlashSale
    {
        return $this->flashSaleRepo->getActive();
    }

    public function all(int $perPage = 20): LengthAwarePaginator
    {
        return $this->flashSaleRepo->getPaginated($perPage);
    }

    public function submitProduct(
        FlashSale $flashSale,
        Product $product,
        Seller $seller,
        float $discountedPrice,
        int $flashStock
    ): FlashSaleProduct {
        $existing = FlashSaleProduct::where('flash_sale_id', $flashSale->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing !== null) {
            throw new RuntimeException('Product is already submitted to this flash sale.');
        }

        return $this->flashSaleRepo->submitProduct([
            'flash_sale_id' => $flashSale->id,
            'product_id' => $product->id,
            'seller_id' => $seller->id,
            'discounted_price' => $discountedPrice,
            'flash_stock' => $flashStock,
            'status' => FlashSaleProduct::STATUS_PENDING,
        ]);
    }

    public function approve(FlashSaleProduct $flashSaleProduct): void
    {
        $this->flashSaleRepo->updateFlashSaleProduct($flashSaleProduct, [
            'status' => FlashSaleProduct::STATUS_APPROVED,
        ]);
    }

    public function reject(FlashSaleProduct $flashSaleProduct): void
    {
        $this->flashSaleRepo->updateFlashSaleProduct($flashSaleProduct, [
            'status' => FlashSaleProduct::STATUS_REJECTED,
        ]);
    }

    public function forSeller(int $sellerId): Collection
    {
        return FlashSaleProduct::where('seller_id', $sellerId)
            ->with(['product', 'flashSale'])
            ->latest()
            ->get();
    }
}
