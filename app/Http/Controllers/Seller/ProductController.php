<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function products()
    {
        $seller_id = seller()->id;

        $products = Product::where('seller_id', $seller_id)->latest('id')->paginate(10);

        return view('seller.products.index', compact('products'));
    }
}
