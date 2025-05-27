<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\SellerResource;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['brands'] = BrandResource::collection(Brand::get());
        $data['categories'] = CategoryResource::collection(Category::category()->get());

        $newProducts = Product::latest('id')->take(16)->get();
        $trendingProducts = Product::trending()->whereHas('variants')->take(24)->get();

        $data['products'] = array(
            'trending' => ProductListResource::collection($trendingProducts),
            'new' => ProductListResource::collection($newProducts),
        );

        $data['sellers'] = SellerResource::collection(Seller::limit(10)->get());

        return apiResponse($data);
    }
}
