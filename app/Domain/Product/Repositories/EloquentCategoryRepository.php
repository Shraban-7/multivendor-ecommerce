<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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
        $this->clearCache();

        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        $this->clearCache();

        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        $this->clearCache();

        return $category->delete();
    }

    public function getParentCategories(): Collection
    {
        return Cache::remember('categories.parents', 3600, fn () =>
            Category::category()->orderBy('name')->get()
        );
    }

    public function getSubcategories(int $parentId): Collection
    {
        return Cache::remember('categories.sub.'.$parentId, 3600, fn () =>
            Category::where('category_id', $parentId)->orderBy('name')->get()
        );
    }

    public function getNavCategories(): Collection
    {
        return Cache::remember('categories.nav', 3600, fn () =>
            Category::with('subcategories')
                ->nav()
                ->orderBy('name')
                ->get()
        );
    }

    public function getAllWithSubcategories(): Collection
    {
        return Cache::remember('categories.all', 3600, fn () =>
            Category::category()
                ->with('subcategories')
                ->orderBy('name')
                ->get()
        );
    }

    private function clearCache(): void
    {
        Cache::forget('categories.parents');
        Cache::forget('categories.nav');
        Cache::forget('categories.all');
        foreach (Category::pluck('id') as $id) {
            Cache::forget('categories.sub.'.$id);
        }
    }
}
