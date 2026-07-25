<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use App\Domain\Product\Repositories\Contracts\FlashSaleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentFlashSaleRepository implements FlashSaleRepositoryInterface
{
    public function findById(int $id): ?FlashSale
    {
        return FlashSale::find($id);
    }

    public function findOrFail(int $id): FlashSale
    {
        return FlashSale::findOrFail($id);
    }

    public function getActive(): ?FlashSale
    {
        return FlashSale::active()
            ->with(['approveProducts.product'])
            ->first();
    }

    public function getPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return FlashSale::latest()->paginate($perPage);
    }

    public function store(array $data): FlashSale
    {
        return FlashSale::create($data);
    }

    public function update(FlashSale $flashSale, array $data): bool
    {
        return $flashSale->update($data);
    }

    public function delete(FlashSale $flashSale): bool
    {
        return $flashSale->delete();
    }

    public function getFlashSaleProducts(int $flashSaleId): LengthAwarePaginator
    {
        return FlashSaleProduct::where('flash_sale_id', $flashSaleId)
            ->with(['seller', 'product'])
            ->paginate(20);
    }

    public function submitProduct(array $data): FlashSaleProduct
    {
        return FlashSaleProduct::create($data);
    }

    public function updateFlashSaleProduct(FlashSaleProduct $product, array $data): bool
    {
        return $product->update($data);
    }
}
