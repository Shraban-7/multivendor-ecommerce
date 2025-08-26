<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $seller_id = seller()->id;

        $products = Product::where('seller_id', $seller_id)->with('variants.option_values')->get();

        $categories = Category::limit(5)->get();

        return view('seller.pos', compact('products', 'categories'));
    }
}
