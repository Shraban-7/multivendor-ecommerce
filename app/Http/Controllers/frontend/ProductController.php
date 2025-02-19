<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function details($slug)
    {
        $product = Product::where('slug', $slug)->with(['category.subcategories','images'])->first();

        return view('frontend.pages.product_details',compact('product'));
    }
}
