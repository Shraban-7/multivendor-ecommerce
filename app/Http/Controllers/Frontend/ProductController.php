<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function details($slug, Request $request)
    {
        $limit = 8;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $product = Product::where('slug', $slug)
            ->with([
                'category.subcategories',
                'images',
                'seller',
                'productAttributes.options',
                'reviews',
            ])
            ->firstOrFail();

        $products = Product::where('seller_id', $product->seller->id)->get();
        $total_sell = Product::where('seller_id', $product->seller->id)->sum('stock_out');

        $interest_products = Product::whereCategory($product->category)
            ->where('id', '!=', $product->id)
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();

        $reviewStats = Review::where('product_id', $product->id)
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();


        $totalReviews = array_sum($reviewStats);
        $averageRating = $product->reviews->avg('rating');


        $ratings = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratings[$i] = $reviewStats[$i] ?? 0;
        }

        $product->reviews_count = $totalReviews;
        $product->one_star = $ratings[1];
        $product->two_star = $ratings[2];
        $product->three_star = $ratings[3];
        $product->four_star = $ratings[4];
        $product->five_star = $ratings[5];


        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }

            return view('frontend.partials.product-card-load', [
                'products' => $interest_products
            ])->render();
        }

        return view('frontend.products.details', compact(
            'product',
            'products',
            'total_sell',
            'interest_products',
            'ratings',
            'totalReviews',
            'averageRating',
        ));
    }

}
