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
            ->with(['products.product_attributes.product_attribute_options', 'subcategories'])
            ->first();

        $query = $category->products();

        if ($request->has('subcategory') && $request->subcategory != 'all') {
            $query->whereHas('subcategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        $product_attributes = ProductAttribute::with('product_attribute_options')->get()->unique('name');

        // return $product_attributes;

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $product_attributes->pluck('name')->map('strtolower')->toArray()) && $value != 'all') {
                $query->whereHas('product_attributes.product_attribute_options', function ($q) use ($key, $value) {
                    $q->where('name', ucfirst($key))->where('value', $value);
                });
            }
        }

        $products = $query->with('product_attributes.product_attribute_options')->get();

        return view('frontend.pages.category_detail', compact('category', 'product_attributes', 'products'));
    }
}
