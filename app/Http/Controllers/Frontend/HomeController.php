<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Seller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;
        $data['categories'] = Category::category()->get();

        // $data['special_category'] = Category::special()->with(['banners', 'products'])->first();

        $new_arrival_products = Product::withDefaultRelations()
            ->active()
            ->withAvg('reviews', 'rating')
            // ->where('is_featured', 0)
            ->withCount('reviews')
            ->orderByDesc('id')
            ->limit(16)
            ->get();

        // $data['new_arrival_products'] = $new_arrival_products->map(fn($product) => $product->toDetailsArray());

        // $trending_products = Product::withDefaultRelations()
        //     ->active()
        //     ->withAvg('reviews', 'rating')
        //     ->where('is_trending', 1)
        //     ->withCount('reviews')
        //     ->orderByDesc('id')
        //     ->limit(16)
        //     ->get();

        // $data['trending_products'] = $trending_products->map(fn($product) => $product->toDetailsArray());

        // $bestselling_products = Product::withDefaultRelations()
        //     ->active()
        //     ->withAvg('reviews', 'rating')
        //     ->where('best_selling', 1)
        //     ->withCount('reviews')
        //     ->orderByDesc('id')
        //     ->limit(10)
        //     ->get();

        // $data['bestselling_products'] = $bestselling_products->map(fn($product) => $product->toDetailsArray());

        // $featured_products = Product::withDefaultRelations()
        //     ->active()
        //     ->withAvg('reviews', 'rating')
        //     ->where('is_featured', 1)
        //     ->withCount('reviews')
        //     ->orderByDesc('id')
        //     ->limit(10)
        //     ->get();

        // $data['featured_products'] = $featured_products->map(fn($product) => $product->toDetailsArray());

        $data['banners'] = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->groupBy('section');

        $data['sellers'] = Seller::active()->limit(8)->get();

        $data['brands'] = Brand::where('status', 1)
            ->orderBy('name')
            ->limit(12)
            ->get();
        $data['products'] = Product::withDefaultRelations()
            ->active()
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();
        $products = $data['products'];

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }

            return view('frontend.partials.product-card-load', [
                'products' => $products,
            ])->render();
        }

        $data['flash_sales'] = FlashSale::active()
            ->withCount('approveProducts')
            ->having('approve_products_count', '>', 0)
            ->with('approveProducts')
            ->get();

        return view('frontend.home', $data);

        // return view('frontend.pages.home', $data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug', $slug)->first();

        return view('frontend.pages.category_detail', compact('category'));
    }
}
