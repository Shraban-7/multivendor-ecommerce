<?php

namespace App\Domain\Product\Http\Controllers\Frontend;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;\nuse App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function details($slug, Request $request)
    {
        $limit = 16;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $category = Category::where('slug', $slug)
            ->with(['products', 'subcategories'])
            ->firstOrFail();

        $brands = Brand::all();

        $query = Product::where('category_id', $category->id)->withDefaultRelations()->active();

        if ($request->filled('subcategory') && $request->subcategory !== 'all') {
            $query->whereHas('subcategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->price == 'under') {
            $query->where('selling_price', '<', 500);
        } elseif ($request->price == 'range') {
            $query->whereBetween('selling_price', [500, 5000]);
        } elseif ($request->price == 'upper') {
            $query->where('selling_price', '>', 5000);
        } elseif ($request->price == 'min') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($request->price == 'max') {
            $query->orderBy('selling_price', 'desc');
        }

        if ($request->filled('review')) {
            $query->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '=', $request->review);
        }

                }

        $products = $query->skip($skip)
            ->take($limit)
            ->get();

                $colors = Color::all();
        $sizes = Size::all();

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }

            return view('frontend.partials.product-card-load', compact('products'))->render();
        }

        return view('frontend.categories.details', compact('category', 'colors', 'sizes', 'products', 'brands'));
    }
}

