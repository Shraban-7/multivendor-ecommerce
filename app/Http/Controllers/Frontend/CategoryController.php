<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
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
            ->with(['products.productAttributes.options', 'subcategories'])
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

        $productAttributes = ProductAttribute::with('options')->get()->unique('name');

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $productAttributes->pluck('name')->map('strtolower')->toArray()) && $value != 'all') {
                $query->whereHas('productAttributes.options', function ($q) use ($key, $value) {
                    $q->where('name', ucfirst($key))->where('value', $value);
                });
            }
        }

        $products = $query->with('productAttributes.options', 'unit', 'images')
            ->latest()
            ->skip($skip)
            ->take($limit)->get();

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }
            return view('frontend.partials.product-card-load', compact('products'))->render();
        }

        return view('frontend.categories.details', compact('category', 'productAttributes', 'products', 'brands'));
    }
}
