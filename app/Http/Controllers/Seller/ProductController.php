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
        $user = Auth::guard('seller')->id();
        $products = Product::where('seller_id', $user)->latest('id')->get();

        return view('seller.products.index',compact('products'));
    }
}
