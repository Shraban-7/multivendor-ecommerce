<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\HeroBannerResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\SellerResource;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\HeroBanner;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['banners'] = HeroBannerResource::collection(HeroBanner::active()->orderBy('position')->get());

        $data['brands'] = BrandResource::collection(Brand::get());
        $data['categories'] = CategoryResource::collection(Category::category()->get());

        $newProducts = Product::withDefaultRelations()
            ->active()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(16)
            ->get();

        $trendingProducts = Product::withDefaultRelations()
            ->active()
            ->trending()
            ->limit(16)
            ->get();

        $data['products'] = array(
            'trending' => ProductListResource::collection($trendingProducts),
            'new' => ProductListResource::collection($newProducts),
        );

        $data['sellers'] = SellerResource::collection(Seller::limit(10)->get());

        return apiResponse($data);
    }
}
