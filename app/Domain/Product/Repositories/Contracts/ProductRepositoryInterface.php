<?php

namespace App\Domain\Product\Repositories\Contracts;

use App\Domain\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;

    public function findBySlug(string $slug): ?Product;

    public function findOrFail(int $id): Product;

    public function getActivePaginated(int $perPage = 25): LengthAwarePaginator;

    public function getForSeller(int $sellerId, array $filters = [], int $perPage = 25): LengthAwarePaginator;

    public function store(array $data): Product;

    public function update(Product $product, array $data): bool;

    public function delete(Product $product): bool;

    public function getFeatured(int $limit = 12): Collection;

    public function getTrending(int $limit = 12): Collection;

    public function search(string $term, int $perPage = 25): LengthAwarePaginator;

    public function incrementViews(Product $product): void;
}
