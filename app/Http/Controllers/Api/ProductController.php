<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? '';
        $subcategory_id = $request->subcategory_id ?? '';
        $name = $request->name ?? '';

        $products = Product::query();

        if ($category_id != '') $products->where('category_id', $category_id);
        if ($subcategory_id != '') $products->where('subcategory_id', $subcategory_id);

        if ($name != '') {
            $products->where(function ($query) use ($name) {
                $query->where('name', $name)
                    ->orWhere('name', 'LIKE', "{$name}%")
                    ->orWhere('name', 'LIKE', "%{$name}%");
            });
        }

        $products = $products->with('category', 'subcategory')
            ->paginate(15)
            ->appends($request->query());

        return apiResourceResponse(ProductListResource::collection($products));
    }
}
