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

    public function show(Seller $seller, Request $request)
    {
        $data['seller'] = SellerResource::make($seller);

        $category_ids = Product::where('seller_id', $seller->id)
            ->select('category_id')
            ->distinct('category_id')
            ->pluck('category_id')
            ->toArray();

        $productQuery = Product::where('seller_id', $seller->id)
            ->withDefaultRelations()
            ->active();

        if ($request->sortBy === 'popular') {
            $productQuery->orderBy('stock_out', 'desc');
        } elseif ($request->sortBy === 'low-to-high') {
            $productQuery->orderBy('selling_price', 'asc');
        } elseif ($request->sortBy === 'high-to-low') {
            $productQuery->orderBy('selling_price', 'desc');
        } else {
            $productQuery->latest();
        }

        $products = $productQuery->paginate(12);

        $data['products'] = ProductListResource::collection($products);

        $data['categories'] = CategoryResource::collection(Category::whereIn('id', $category_ids)->get());

        return apiResponse($data);
    }
}
