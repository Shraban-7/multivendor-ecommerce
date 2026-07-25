<?php

namespace App\Domain\Vendor\Repositories;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SellerRepositoryInterface
{
    public function findById(int $id): ?Seller;

    public function findByUsername(string $username): ?Seller;

    public function getPaginated(int $perPage = 30): LengthAwarePaginator;

    public function getPendingPaginated(int $perPage = 30): LengthAwarePaginator;

    public function store(array $data): Seller;

    public function update(Seller $seller, array $data): bool;

    public function setStatus(Seller $seller, int $status): bool;

    public function setBestSeller(Seller $seller, bool $isBestSeller): bool;

    public function softDelete(Seller $seller): bool;

    public function restore(Seller $seller): bool;

    public function permanentDelete(Seller $seller): bool;
}
