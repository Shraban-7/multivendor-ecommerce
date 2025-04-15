<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function details($slug, Request $request)
    {
        $limit = 8;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;
        $product = Product::where('slug', $slug)->with(['category.subcategories', 'images', 'seller', 'productAttributes.options'])->first();
        $products = Product::where('seller_id', $product->seller->id)->get();
        $total_sell = Product::where('seller_id', $product->seller->id)->sum('stock_out');
        $interest_products = Product::whereCategory($product->category)->where('id', '!=', $product->id)->latest()
            ->skip($skip)
            ->take($limit)->get();

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }
            return view('frontend.partials.product-card-load', ['products' => $interest_products])->render();
        }

        return view('frontend.products.details', compact('product', 'products', 'total_sell', 'interest_products',));
    }
}
