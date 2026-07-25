<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Http\Resources\BannerResource;
use App\Domain\Product\Http\Resources\BrandResource;
use App\Domain\Product\Http\Resources\CategoryResource;
use App\Domain\Product\Http\Resources\FlashSaleResource;
use App\Domain\Product\Http\Resources\ProductListResource;
use App\Domain\Product\Models\Banner;
use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Http\Resources\SellerResource;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $data['banners'] = Cache::remember('dashboard.banners', 900, function () {
            return BannerResource::collection(
                Banner::active()->where('section', Banner::SECTION_HERO)->get()
            );
        });

        $data['brands'] = Cache::remember('dashboard.brands', 900, function () {
            return BrandResource::collection(Brand::get());
        });

        $data['categories'] = Cache::remember('dashboard.categories', 900, function () {
            return CategoryResource::collection(Category::category()->limit(8)->get());
        });

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

        $data['sellers'] = SellerResource::collection(
            Seller::with(['district', 'division'])->limit(10)->get()
        );

        $flashSales = FlashSale::active()->with('approveProducts.product')->latest('id')->get();
        $data['flash_sales'] = FlashSaleResource::collection($flashSales);

        return apiResponse($data);
    }
}
