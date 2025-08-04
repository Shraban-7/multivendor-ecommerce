<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroBanner;
use App\Models\Product;


class HomeController extends Controller
{
    public function index()
    {
        $data['categories'] = Category::category()->get();
        
        $data['special_category'] = Category::special()->with(['banners', 'products'])->first();

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
            ->limit(10)
            ->get();

        $data['bestselling_products'] = $bestselling_products->map(fn($product) => $product->toDetailsArray());

        $featured_products = Product::with('unit')
            ->withAvg('reviews', 'rating')
            ->where('is_featured', 1)
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $data['featured_products'] = $featured_products->map(fn($product) => $product->toDetailsArray());

        $data['hero_grid_one']   = HeroBanner::where('position', 1)->first();
        $data['hero_grid_two']   = HeroBanner::where('position', 2)->first();
        $data['hero_grid_three'] = HeroBanner::where('position', 3)->first();
        $data['hero_grid_four']  = HeroBanner::where('position', 4)->first();
        $data['hero_grid_five']  = HeroBanner::where('position', 5)->first();

        $data['hero_banners'] = HeroBanner::active()->orderBy('position')->get();

        return view('frontend.pages.home', $data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug', $slug)->first();

        return view('frontend.pages.category_detail', compact('category'));
    }
}
