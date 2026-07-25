<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Lightweight catalog cache layer used by Performance/02.
 * Full Product domain extraction may replace this with the richer CatalogService.
 */
class CatalogCacheService
{
    public function categories(): mixed
    {
        return Cache::remember('catalog.categories', now()->addHour(), function () {
            return Category::query()->orderBy('name')->get();
        });
    }

    public function approvedProducts(int $perPage = 20): LengthAwarePaginator
    {
        return Product::query()
            ->with(['variants', 'images', 'seller'])
            ->where('is_approve', 1)
            ->latest()
            ->paginate($perPage);
    }

    public function forgetCategoryTree(): void
    {
        Cache::forget('catalog.categories');
    }
}
