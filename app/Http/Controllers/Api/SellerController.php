<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\SellerResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::get();

        return apiResourceResponse(SellerResource::collection($sellers));
    }

    public function show(Seller $seller)
    {
        $data['seller'] = SellerResource::make($seller);

        $category_ids = Product::where('seller_id', $seller->id)
            ->select('category_id')
            ->distinct('category_id')
            ->pluck('category_id')
            ->toArray();

        $popularProducts = Product::where('seller_id', $seller->id)->limit(10)->get();
        $newProducts = Product::where('seller_id', $seller->id)->latest('id')->get();

        $data['popular_products'] = ProductListResource::collection($popularProducts);
        $data['new_products'] = ProductListResource::collection($newProducts);
        $data['categories'] = CategoryResource::collection(Category::whereIn('id', $category_ids)->get());

        return apiResponse($data);
    }
}
