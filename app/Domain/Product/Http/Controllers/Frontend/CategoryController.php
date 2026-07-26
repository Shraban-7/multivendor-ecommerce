<?php

namespace App\Domain\Product\Http\Controllers\Frontend;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function details($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->with(['subcategories'])
            ->firstOrFail();

        $brands = Brand::withCount('products')->get();

        $selectedSubcategory = $request->get('subcategory', 'all');
        $selectedBrands = $request->has('brand') ? explode(',', $request->brand) : [];
        $sortFilter = $request->get('sort', '');

        $productOptionFilters = [];
        foreach (['color', 'size'] as $option) {
            if ($request->has($option)) {
                $productOptionFilters[$option] = $request->input($option);
            }
        }

        $productOptions = [];
        $allVariants = ProductVariant::whereHas('product', function ($q) use ($category) {
            $q->where('category_id', $category->id);
        })->with(['color', 'size'])->get();

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

        $query = Product::where('category_id', $category->id)->withDefaultRelations()->active();

        if ($selectedSubcategory !== 'all') {
            $query->whereHas('subcategory', function ($q) use ($selectedSubcategory) {
                $q->where('slug', $selectedSubcategory);
            });
        }

        if (! empty($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        $priceMin = $request->has('price_min') ? (int) $request->price_min : 0;
        $priceMax = $request->has('price_max') ? (int) $request->price_max : 50000;

        if ($request->has('price_min') || $request->has('price_max')) {
            $query->whereBetween('price', [$priceMin, $priceMax]);
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
            $query->orderBy('price', 'asc');
        } elseif ($sortFilter === 'high_low') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(20)->appends($request->query());

        if ($request->ajax()) {
            if ($request->get('load_more')) {
                if ($products->isEmpty()) {
                    return '';
                }
                $html = '';
                foreach ($products as $product) {
                    $html .= view('components.frontend.product-card', ['product' => $product])->render();
                }
                return $html;
            }

            $html = view('components.frontend.category-products-page', compact(
                'category',
                'products',
                'brands',
                'selectedBrands',
                'selectedSubcategory',
                'productOptions',
                'productOptionFilters',
            ))->render();

            return response()->json(['html' => $html]);
        }

        return view('frontend.categories.details', compact(
            'category',
            'products',
            'brands',
            'selectedBrands',
            'selectedSubcategory',
            'productOptions',
            'productOptionFilters',
        ));
    }
}
