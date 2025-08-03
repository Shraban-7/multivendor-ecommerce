<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroBanner;
use App\Models\HomeMidBanner;
use App\Models\Product;
use App\Models\PromoPoster;
use App\Models\SellerCampaign;

class HomeController extends Controller
{
    public function index()
    {
        $data['categories'] = Category::slider()->get();
        $data['special_category'] = Category::special()->with(['banners', 'products'])->first();
        $campaigns = SellerCampaign::with('products')->latest()->get();

        $light_deals = [];

        foreach ($campaigns as $campaign) {
            foreach ($campaign->products as $product) {
                $product->campaign_start_date = $campaign->start_date;
                $product->campaign_end_date   = $campaign->end_date;

                $light_deals[] = $product;
            }
        }

        $data['light_deals'] = $light_deals;

        $data['interest_products'] = Product::with('unit')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(8)
            ->get();

        $data['trending_products'] = Product::with('unit')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(3)
            ->get();

        $community_products = Product::community()
            ->with('unit')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(8)
            ->get();

        $data['community_products'] = $community_products->map(fn($product) => $product->toDetailsArray());

        $new_arrival_products = Product::with('unit')
            ->withAvg('reviews', 'rating')
            // ->where('is_featured', 0)
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(16)
            ->get();

        $data['new_arrival_products'] = $new_arrival_products->map(fn($product) => $product->toDetailsArray());

        $trending_products = Product::with('unit')
            ->withAvg('reviews', 'rating')
            ->where('is_trending', 1)
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(16)
            ->get();

        $data['trending_products'] = $trending_products->map(fn($product) => $product->toDetailsArray());

        $bestselling_products = Product::with('unit')
            ->withAvg('reviews', 'rating')
            ->where('best_selling', 1)
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(16)
            ->get();

        $data['bestselling_products'] = $bestselling_products->map(fn($product) => $product->toDetailsArray());

        $featured_products = Product::with('unit')
            ->withAvg('reviews', 'rating')
            ->where('is_featured', 1)
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(16)
            ->get();

        $data['featured_products'] = $featured_products->map(fn($product) => $product->toDetailsArray());

        $data['hero_grid_one']   = HeroBanner::where('position', 1)->first();
        $data['hero_grid_two']   = HeroBanner::where('position', 2)->first();
        $data['hero_grid_three'] = HeroBanner::where('position', 3)->first();
        $data['hero_grid_four']  = HeroBanner::where('position', 4)->first();
        $data['hero_grid_five']  = HeroBanner::where('position', 5)->first();

        $data['gallery_feature_pro_one']   = HomeMidBanner::where('position', 1)->first();
        $data['gallery_feature_pro_two']   = HomeMidBanner::where('position', 2)->first();
        $data['gallery_feature_pro_three'] = HomeMidBanner::where('position', 3)->first();
        $data['gallery_feature_pro_four']  = HomeMidBanner::where('position', 4)->first();
        $data['gallery_feature_pro_five']  = HomeMidBanner::where('position', 5)->first();

        $data['promo_poster_one'] = PromoPoster::where('position', 1)->first();
        $data['promo_poster_two'] = PromoPoster::where('position', 2)->first();

        return view('frontend.pages.home', $data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug', $slug)->first();

        return view('frontend.pages.category_detail', compact('category'));
    }
}
