<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\FlashSaleRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CatalogService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
        private readonly CategoryRepositoryInterface $categoryRepo,
        private readonly FlashSaleRepositoryInterface $flashSaleRepo,
    ) {}

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['brand', 'images', 'category', 'unit', 'variants.color', 'variants.size'])
            ->active();

        $this->applyFilters($query, $filters);

        return $query->latest('id')->paginate($perPage);
    }

    public function listForSeller(int $sellerId, int $perPage = 25): LengthAwarePaginator
    {
        return $this->productRepo->getForSeller($sellerId, $perPage);
    }

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
            $query->where('price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (! empty($filters['in_stock'])) {
            $query->whereRaw('(stock_in - stock_out) > 0');
        }

        if (! empty($filters['sort'])) {
            match ($filters['sort']) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'newest' => $query->latest('id'),
                'popular' => $query->orderByDesc('views'),
                'rating' => $query->orderByDesc('avg_rating'),
                default => $query->latest('id'),
            };
        }
    }

    public function featured(int $limit = 12): Collection
    {
        return Cache::remember('products.featured', 300, function () use ($limit) {
            return $this->productRepo->getFeatured($limit);
        });
    }

    public function trending(int $limit = 12): Collection
    {
        return Cache::remember('products.trending', 300, function () use ($limit) {
            return $this->productRepo->getTrending($limit);
        });
    }

    public function byCategory(Category $category, int $perPage = 24): LengthAwarePaginator
    {
        return Cache::remember('products.category.'.$category->id.'.page.'.request('page', 1), 120, function () use ($category, $perPage) {
            $categoryIds = [$category->id];

            if ($category->subcategories()->exists()) {
                $categoryIds = array_merge(
                    $categoryIds,
                    $category->subcategories()->pluck('id')->toArray()
                );
            }

            return Product::active()
                ->whereIn('category_id', $categoryIds)
                ->with(['brand', 'images', 'variants.color', 'variants.size'])
                ->latest('id')
                ->paginate($perPage);
        });
    }

    public function activeFlashSale(): ?FlashSale
    {
        return Cache::remember('flash_sale.active', 60, function () {
            return $this->flashSaleRepo->getActive();
        });
    }

    public function recordView(Product $product): void
    {
        $this->productRepo->incrementViews($product);
    }
}
