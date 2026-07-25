<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SellerResource;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::get();

        return apiResourceResponse(SellerResource::collection($sellers));
    }

    public function show(Seller $seller, Request $request)
    {
        $data['seller'] = SellerResource::make($seller);

        $category_ids = Product::where('seller_id', $seller->id)
            ->select('category_id')
            ->distinct('category_id')
            ->pluck('category_id')
            ->toArray();

        $data['categories'] = CategoryResource::collection(Category::whereIn('id', $category_ids)->get());

        return apiResponse($data);
    }
}
