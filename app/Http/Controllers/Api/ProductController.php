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
        $cateory_id = $request->category_id ?? '';
        $subcateory_id = $request->subcateory_id ?? '';
        $name = $request->name ?? '';

        $products = Product::query();

        if ($cateory_id != '') $products->where('cateory_id', $cateory_id);
        if ($subcateory_id != '') $products->where('subcateory_id', $subcateory_id);

        if ($name != '') {
            $products->where(function ($query) use ($name) {
                $query->where('name', $name)
                    ->orWhere('name', 'LIKE', "{$name}%")
                    ->orWhere('name', 'LIKE', "%{$name}%");
            });
        }

        $products = $products->paginate(15)->appends($request->query());

        return apiResourceResponse(ProductListResource::collection($products));
    }
}
