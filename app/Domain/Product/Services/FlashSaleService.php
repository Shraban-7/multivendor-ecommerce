<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use App\Domain\Product\Models\Product;
use App\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class FlashSaleService
{
    /**
     * Return the currently active flash sale with approved products, or null.
     */
    public function getActive(): ?FlashSale
    {
        return FlashSale::active()
            ->with(['approveProducts.product'])
            ->first();
    }

    /**
     * Return a paginated list of all flash sales (admin).
     */
    public function all(int $perPage = 20): LengthAwarePaginator
    {
        return FlashSale::latest()->paginate($perPage);
    }

    /**
     * Submit a product to a flash sale.
     */
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

        return FlashSaleProduct::create([
            'flash_sale_id' => $flashSale->id,
            'product_id' => $product->id,
            'seller_id' => $seller->id,
            'discounted_price' => $discountedPrice,
            'flash_stock' => $flashStock,
            'status' => FlashSaleProduct::STATUS_PENDING,
        ]);
    }

    /**
     * Approve a flash sale product.
     */
    public function approve(FlashSaleProduct $flashSaleProduct): void
    {
        $flashSaleProduct->update(['status' => FlashSaleProduct::STATUS_APPROVED]);
    }

    /**
     * Reject a flash sale product.
     */
    public function reject(FlashSaleProduct $flashSaleProduct): void
    {
        $flashSaleProduct->update(['status' => FlashSaleProduct::STATUS_REJECTED]);
    }

    /**
     * Return flash sale products for a given seller.
     */
    public function forSeller(int $sellerId): Collection
    {
        return FlashSaleProduct::where('seller_id', $sellerId)
            ->with(['product', 'flashSale'])
            ->latest()
            ->get();
    }
}
