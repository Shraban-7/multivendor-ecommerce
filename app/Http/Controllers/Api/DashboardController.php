<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Models\Banner;
use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\FlashSaleResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\SellerResource;

class DashboardController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->where('section', Banner::SECTION_HERO)->get();
        $data['banners'] = BannerResource::collection($banners);
        $data['brands'] = BrandResource::collection(Brand::get());
        $data['categories'] = CategoryResource::collection(Category::category()->limit(8)->get());

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
            ->orderByDesc('id')
            ->get();

        $data['products'] = [
            'trending' => ProductListResource::collection($trendingProducts),
            'new' => ProductListResource::collection($newProducts),
        ];

        $data['sellers'] = SellerResource::collection(Seller::limit(10)->get());

        $flashSales = FlashSale::active()->with('approveProducts.product')->latest('id')->get();

        $data['flash_sales'] = FlashSaleResource::collection($flashSales);

        return apiResponse($data);
    }
}
