<?php

namespace App\Domain\Product\Repositories\Contracts;

use App\Domain\Product\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;

    public function findBySlug(string $slug): ?Category;

    public function store(array $data): Category;

    public function update(Category $category, array $data): bool;

    public function delete(Category $category): bool;

    public function getParentCategories(): Collection;

    public function getSubcategories(int $parentId): Collection;

    public function getNavCategories(): Collection;

    public function getAllWithSubcategories(): Collection;
}
