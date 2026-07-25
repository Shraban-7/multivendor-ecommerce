<?php

namespace App\Domain\Product\Repositories\Contracts;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FlashSaleRepositoryInterface
{
    public function findById(int $id): ?FlashSale;

    public function findOrFail(int $id): FlashSale;

    public function getActive(): ?FlashSale;

    public function getPaginated(int $perPage = 20): LengthAwarePaginator;

    public function store(array $data): FlashSale;

    public function update(FlashSale $flashSale, array $data): bool;

    public function delete(FlashSale $flashSale): bool;

    public function getFlashSaleProducts(int $flashSaleId): LengthAwarePaginator;

    public function submitProduct(array $data): FlashSaleProduct;

    public function updateFlashSaleProduct(FlashSaleProduct $product, array $data): bool;
}
