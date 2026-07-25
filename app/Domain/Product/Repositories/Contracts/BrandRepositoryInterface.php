<?php

namespace App\Domain\Product\Repositories\Contracts;

use App\Domain\Product\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BrandRepositoryInterface
{
    public function findById(int $id): ?Brand;

    public function findBySlug(string $slug): ?Brand;

    public function getPaginated(int $perPage = 10): LengthAwarePaginator;

    public function getAll(): Collection;

    public function store(array $data): Brand;

    public function update(Brand $brand, array $data): bool;

    public function delete(Brand $brand): bool;
}
