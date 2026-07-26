<?php

namespace App\Domain\Product\Http\Controllers\Frontend;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Review\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')
            ->whereNull('category_id')
            ->where('status', true)
            ->get();

        $brands = Brand::withCount('products')->get();

        $selectedCategories = $request->category ? explode(',', $request->category) : [];
        $selectedBrands = $request->brand ? explode(',', $request->brand) : [];
        $sortFilter = $request->sort ?? '';
        $priceMin = $request->price_min ?? 0;
        $priceMax = $request->price_max ?? 50000;

        $productOptionFilters = [];
        $filterableOptions = ['color', 'size'];
        foreach ($filterableOptions as $option) {
            if ($request->has($option)) {
                $productOptionFilters[$option] = $request->input($option);
            }
        }

        $productOptions = [];
        $allVariants = ProductVariant::with(['color', 'size'])->get();
        $uniqueColors = $allVariants->filter->color->unique('color_id')->map(fn ($v) => [
            'id' => $v->color->id,
            'value' => $v->color->name,
        ])->values()->toArray();
        $uniqueSizes = $allVariants->filter->size->unique('size_id')->sortBy('size.sort_order')->map(fn ($v) => [
            'id' => $v->size->id,
            'value' => $v->size->name,
        ])->values()->toArray();

        if (count($uniqueColors) > 0) {
            $productOptions['Color'] = $uniqueColors;
        }
        if (count($uniqueSizes) > 0) {
            $productOptions['Size'] = $uniqueSizes;
        }

        $query = Product::active()->withDefaultRelations();

        if (! empty($selectedCategories)) {
            $query->whereHas('category', function ($q) use ($selectedCategories) {
                $q->whereIn('slug', $selectedCategories);
            });
        }

        if (! empty($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        if ($request->has('price_min') || $request->has('price_max')) {
            $query->whereBetween('selling_price', [$priceMin, $priceMax]);
        }

        if (! empty($productOptionFilters)) {
            foreach ($productOptionFilters as $optionKey => $value) {
                $valueIds = is_array($value) ? $value : explode(',', $value);
                $optionKey = str_replace('_', ' ', $optionKey);
                $optionKey = ucwords($optionKey);

                if ($optionKey === 'Color') {
                    $query->whereHas('variants', function ($q) use ($valueIds) {
                        $q->whereIn('color_id', $valueIds);
                    });
                } elseif ($optionKey === 'Size') {
                    $query->whereHas('variants', function ($q) use ($valueIds) {
                        $q->whereIn('size_id', $valueIds);
                    });
                }
            }
        }

        if ($sortFilter === 'newest') {
            $query->latest();
        } elseif ($sortFilter === 'low_high') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sortFilter === 'high_low') {
            $query->orderBy('selling_price', 'desc');
        }

        $products = $query->paginate(20)->appends($request->query());

        $selectedPrice = null;
        if ($request->has('price_min') || $request->has('price_max')) {
            $selectedPrice = ['min' => $priceMin, 'max' => $priceMax];
        }

        if ($request->ajax()) {
            $html = view('components.frontend.products-page', compact(
                'products',
                'selectedCategories',
                'selectedBrands',
                'categories',
                'brands',
                'productOptions',
                'productOptionFilters',
                'selectedPrice',
            ))->render();

            return response()->json(['html' => $html]);
        }

        return view('frontend.products.index', compact(
            'products',
            'selectedCategories',
            'selectedBrands',
            'categories',
            'brands',
            'productOptions',
            'productOptionFilters',
            'selectedPrice',
        ));
    }

    public function details(Product $product)
    {
        $product->load([
            'images',
            'category',
            'subcategory',
            'brand',
            'variants.color',
            'variants.size',
            'seller',
            'reviews.user',
            'seo',
        ]);

        $reviews = $product->reviews;

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        $ratings = collect();
        for ($i = 1; $i <= 5; $i++) {
            $count = $reviews->where('rating', $i)->count();
            $ratings[$i] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
        }

        $relatedProducts = Product::active()
            ->with(['variants.color', 'variants.size', 'images', 'seller', 'category', 'subcategory'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(8)
            ->get();

        return view('frontend.products.details', [
            'product' => $product->toDetailsArray(),
            'productModel' => $product,
            'products' => $relatedProducts,
            'ratings' => $ratings,
            'totalReviews' => $totalReviews,
            'averageRating' => round($averageRating, 1),
            'seo' => $product->seo,
        ]);
    }

    public function getVariant(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $colorId = $request->input('color_id');
        $sizeId = $request->input('size_id');

        $query = ProductVariant::where('product_id', $product->id);

        if ($colorId) {
            $query->where('color_id', $colorId);
        }
        if ($sizeId) {
            $query->where('size_id', $sizeId);
        }

        $variant = $query->first();

        if (! $variant) {
            return response()->json(['message' => 'Variant not found'], 404);
        }

        return response()->json([
            'id' => $variant->id,
            'sku' => $variant->sku,
            'price' => $variant->selling_price,
            'discounted_price' => $variant->discounted_price,
            'stock' => $variant->stock_in - $variant->stock_out,
            'image' => $variant->image ? storage_url($variant->image) : null,
        ]);
    }

    public function loadReview(Request $request)
    {
        if ($request->ajax()) {
            $productId = $request->product_id;
            $page = $request->page ?? 1;

            $reviews = Review::where('product_id', $productId)
                ->latest()
                ->paginate(5, ['*'], 'page', $page);

            if ($reviews->isEmpty()) {
                return response()->json(['html' => '']);
            }

            $view = view('frontend.partials.review-card', compact('reviews'))->render();

            return response()->json([
                'html' => $view,
                'next_page' => $reviews->currentPage() + 1,
                'has_more' => $reviews->hasMorePages(),
            ]);
        }
    }
}
