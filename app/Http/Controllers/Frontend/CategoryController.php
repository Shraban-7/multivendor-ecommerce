<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;

class CategoryController extends Controller
{
    public function category_details($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->with(['products.productAttributes.options', 'subcategories'])
            ->first();

        $query = $category->products();

        if ($request->has('subcategory') && $request->subcategory != 'all') {
            $query->whereHas('subcategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        $productAttributes = ProductAttribute::with('options')->get()->unique('name');

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $productAttributes->pluck('name')->map('strtolower')->toArray()) && $value != 'all') {
                $query->whereHas('productAttributes.options', function ($q) use ($key, $value) {
                    $q->where('name', ucfirst($key))->where('value', $value);
                });
            }
        }

        $products = $query->with('productAttributes.options','unit')->get();

        // return $products;

        return view('frontend.pages.category_detail', compact('category', 'productAttributes', 'products'));
    }
}
