<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\AffiliateClick;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::withDefaultRelations()->latest('id');

        $selectedCategories = $request->category ? explode(',', $request->category) : [];
        $selectedBrands = $request->brand ? explode(',', $request->brand) : [];

        if (!empty($selectedCategories)) {
            $query->whereHas('category', function ($q) use ($selectedCategories) {
                $q->whereIn('slug', $selectedCategories);
            });
        }

        if (!empty($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        $products = $query->paginate(16)->appends($request->query());

        $categories = Category::category()->withCount('products')->get();

        $brands = Brand::all();

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'brands',
            'selectedCategories',
            'selectedBrands'
        ));
    }


    public function details($slug, Request $request)
    {
        $limit = 10;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $productModel = Product::where('slug', $slug)->withDefaultRelations()->firstOrFail();

        $categoryId = $productModel->category->id;
        $sellerId = $productModel->seller->id;

        $product = $productModel->toDetailsArray();

        if ($request->has('ref')) {
            $refCode = $request->query('ref');

            $cookieValue = Cookie::get('affiliate_refs');
            $affiliateRefs = json_decode($cookieValue, true) ?: [];

            $affiliateUser = User::where('referral_code', $refCode)->first();

            $affiliateRefs[$slug][] = $refCode;
            $affiliateRefs[$slug] = array_unique($affiliateRefs[$slug]);

            Cookie::queue('affiliate_refs', json_encode($affiliateRefs), 60 * 24 * 7);

            AffiliateClick::create([
                'affiliate_id' => $affiliateUser->id,
                'product_id' => $product['id'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'clicked_at' => now(),
            ]);
        }

        $interest_products = Product::withDefaultRelations()->where('category_id', $categoryId)
            ->active()
            ->where('id', '!=', $product['id'])
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();

        $products = $interest_products->map(fn($product) => $product->toDetailsArray());

        $reviewStats = Review::where('product_id', $product['id'])
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $totalReviews = array_sum($reviewStats);
        $averageRating = $product['rating'];

        $ratings = collect(range(1, 5))->mapWithKeys(function ($star) use ($reviewStats) {
            return [$star => $reviewStats[$star] ?? 0];
        });

        // $sellerModel = Seller::where('id', $sellerId)->with('followers')->first();

        // $seller = [
        //     'username'        => $sellerModel->username,
        //     'business_name'       => $sellerModel->business_name,
        //     'business_logo'       => $sellerModel->business_logo,
        //     'total_followers' => number_shorten_format($sellerModel->followers->count()),
        //     'rating'          => round($averageRating),
        //     'total_products'  => Product::active()->where('seller_id', $sellerId)->count(),
        // ];

        if ($request->ajax()) {
            $type = $request->get('type');

            if ($type === 'products') {
                if ($products->isEmpty()) {
                    return '';
                }

                return view('frontend.partials.product-card-load', [
                    'products' => $products,
                ])->render();
            }

            if ($type === 'reviews') {
                $reviews = Review::with('user', 'images')->where('product_id', $product['id'])
                    ->latest()
                    ->skip($request->offset ?? 0)
                    ->take(2)
                    ->get();

                if ($reviews->isEmpty()) {
                    return '';
                }

                return view('frontend.partials.review-card', [
                    'reviews' => $reviews,
                ])->render();
            }
        }

        return view('frontend.products.details', [
            'product' => $product,
            'products' => $products,
            'ratings' => $ratings,
            'totalReviews' => $totalReviews,
            'averageRating' => round($averageRating, 1),
            // 'seller' => $seller,
            'seo' => $productModel->seo
        ]);
    }

    public function loadReview(Request $request)
    {
        if ($request->ajax()) {
            $productId = $request->product_id;
            $page = $request->page ?? 1;

            $reviews = Review::where('product_id', $productId)
                ->latest()
                ->paginate(5, ['*'], 'page', $page);

            if ($reviews->isEmpty()) {
                return response()->json(['html' => '']);
            }

            $view = view('frontend.partials.review-card', compact('reviews'))->render();

            return response()->json([
                'html' => $view,
                'next_page' => $reviews->currentPage() + 1,
                'has_more' => $reviews->hasMorePages(),
            ]);
        }
    }
}
