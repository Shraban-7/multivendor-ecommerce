<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CatalogService
{
    /**
     * Return a paginated product listing filtered by the given criteria.
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['brand', 'images', 'category', 'unit', 'variants.option_values'])
            ->active();

        $this->applyFilters($query, $filters);

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Return products for a given seller, paginated.
     */
    public function listForSeller(int $sellerId, int $perPage = 25): LengthAwarePaginator
    {
        return Product::with(['variants.option_values', 'unit'])
            ->where('seller_id', $sellerId)
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Apply search/filter criteria to a query builder.
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function (Builder $q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (! empty($filters['seller_id'])) {
            $query->where('seller_id', $filters['seller_id']);
        }

        if (! empty($filters['min_price'])) {
            $query->where('selling_price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('selling_price', '<=', $filters['max_price']);
        }

        if (! empty($filters['in_stock'])) {
            $query->whereRaw('(stock_in - stock_out) > 0');
        }

        if (! empty($filters['sort'])) {
            match ($filters['sort']) {
                'price_asc' => $query->orderBy('selling_price', 'asc'),
                'price_desc' => $query->orderBy('selling_price', 'desc'),
                'newest' => $query->latest('id'),
                'popular' => $query->orderByDesc('views'),
                'rating' => $query->orderByDesc('avg_rating'),
                default => $query->latest('id'),
            };
        }
    }

    /**
     * Return featured products with optional cache.
     */
    public function featured(int $limit = 12): Collection
    {
        return Cache::remember('products.featured', 300, function () use ($limit) {
            return Product::active()
                ->where('is_featured', true)
                ->with(['brand', 'images', 'variants.option_values'])
                ->latest('id')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Return trending products.
     */
    public function trending(int $limit = 12): Collection
    {
        return Cache::remember('products.trending', 300, function () use ($limit) {
            return Product::active()
                ->trending()
                ->with(['brand', 'images', 'variants.option_values'])
                ->latest('id')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Return products for a given category (including subcategory matches).
     */
    public function byCategory(Category $category, int $perPage = 24): LengthAwarePaginator
    {
        $categoryIds = [$category->id];

        if ($category->subcategories()->exists()) {
            $categoryIds = array_merge(
                $categoryIds,
                $category->subcategories()->pluck('id')->toArray()
            );
        }

        return Product::active()
            ->whereIn('category_id', $categoryIds)
            ->with(['brand', 'images', 'variants.option_values'])
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Return the active flash sale with approved products.
     */
    public function activeFlashSale(): ?FlashSale
    {
        return FlashSale::active()
            ->with(['approveProducts.product.images', 'approveProducts.product.variants'])
            ->first();
    }

    /**
     * Increment product view count.
     */
    public function recordView(Product $product): void
    {
        $product->increment('views');
    }
}
