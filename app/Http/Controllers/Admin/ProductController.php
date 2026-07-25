<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('seller', 'unit', 'variants')->latest('id')->get();
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->status = $request->status;
        $product->save();

        return back()->with('success', 'Product status updated successfully!');
    }
}
