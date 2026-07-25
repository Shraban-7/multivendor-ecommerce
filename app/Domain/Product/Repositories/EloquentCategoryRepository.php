<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function getParentCategories(): Collection
    {
        return Category::category()->orderBy('name')->get();
    }

    public function getSubcategories(int $parentId): Collection
    {
        return Category::where('category_id', $parentId)->orderBy('name')->get();
    }

    public function getNavCategories(): Collection
    {
        return Category::with('subcategories')
            ->nav()
            ->orderBy('name')
            ->get();
    }

    public function getAllWithSubcategories(): Collection
    {
        return Category::category()
            ->with('subcategories')
            ->orderBy('name')
            ->get();
    }
}
