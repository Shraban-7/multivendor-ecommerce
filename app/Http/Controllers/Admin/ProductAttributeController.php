<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;
use App\Models\ProductAttributeOption;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $productAttributes = ProductAttribute::get();
        return view('admin.product-attributes.index', compact('productAttributes'));
    }

    public function optionDelete(ProductAttributeOption $option)
    {
        $option->delete();

        return redirect()->back()->with('success', 'Attribute options removed successfully.');
    }

    public function destroy(ProductAttribute $product_attribute)
    {
        $product_attribute->delete();

        return redirect()->back()->with('success', 'Attribute removed successfully.');
    }
}
