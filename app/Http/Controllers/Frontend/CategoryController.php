<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductAttribute;

class CategoryController extends Controller
{
    public function details($slug, Request $request)
    {
        $limit = 8;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $category = Category::where('slug', $slug)
            ->with(['products', 'subcategories',])
            ->first();

        $brands = Brand::get();

        $query = $category->products();

        if ($request->has('subcategory') && $request->subcategory != 'all') {
            $query->whereHas('subcategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        if ($request->has('brand')) {
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

        if ($request->has('review')) {
            $query->withAvg('reviews', 'rating')
                ->having('avg_review', '=', $request->review);
        }

        $productAttributes = Option::with('options')->get();

        foreach ($request->all() as $key => $value) {
            if (in_array(strtolower($key), $productAttributes->pluck('name')->map('strtolower')->toArray()) && $value != 'all') {
                $query->whereHas('variants.attributeOptions', function ($q) use ($value) {
                    $q->where('value', ucfirst($value));
                });
            }
        }

        $category_products = $query->with('variants','unit', 'images')
            ->latest()
            ->skip($skip)
            ->take($limit)->get();

        $products = $category_products->map(fn($product) => $product->toDetailsArray());

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }
            return view('frontend.partials.product-card-load', compact('products'))->render();
        }

        return view('frontend.categories.details', compact('category', 'productAttributes', 'products', 'brands'));
    }
}
