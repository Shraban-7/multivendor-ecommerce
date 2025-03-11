<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function details($slug,Request $request)
    {
        $product = Product::where('slug', $slug)->with(['category.subcategories','images','seller', 'product_attributes.options'])->first();
        $products = Product::where('seller_id',$product->seller->id)->get();
        $total_sell = Product::where('seller_id', $product->seller->id)->sum('stock_out');
        $interest_products = Product::whereCategory($product->category)->where('id', '!=', $product->id)->paginate(6);

        return view('frontend.pages.product_details',compact('product','products', 'total_sell', 'interest_products',));
    }
}
