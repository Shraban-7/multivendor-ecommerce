<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('seller')->latest('id')->paginate(15);
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();

        return view('Admin.products.index', compact('products', 'categories', 'brands'));
    }
}
