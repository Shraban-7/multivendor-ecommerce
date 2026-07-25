<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Auth\Models\User;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Review\Models\ReportReview;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerFollower;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::orderBy('name')
            ->with('division', 'district', 'banner_images')
            ->withCount('products')
            ->simplePaginate(100);

        return view('frontend.shops.index', compact('sellers'));
    }

    public function follow(Seller $seller)
    {
        $userId = auth()->id();

        $follow = SellerFollower::where('seller_id', $seller->id)
            ->where('user_id', $userId)
            ->first();

        if ($follow) {
            $follow->delete();
            $seller->decrement('total_followers');

            return apiResponse([
                'following' => false,
                'total_followers' => $seller->total_followers,
            ], 'Unfollowed Successfully');
        }

        SellerFollower::create([
            'seller_id' => $seller->id,
            'user_id' => $userId,
        ]);

        $seller->increment('total_followers');

        return apiResponse([
            'following' => true,
            'total_followers' => $seller->total_followers,
        ], 'Followed Successfully');
    }

    public function shop($username, Request $request)
    {
        $seller = Seller::where('username', $username)->firstOrFail();

        $limit = 20;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

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

        $shopProducts = $productQuery->skip($skip)->take($limit)->get();

        $products = $shopProducts;

        $categoryIds = Product::with('category')
            ->where('seller_id', $seller->id)->pluck('category_id')->unique()->values()->all();

        $categories = Category::whereIn('id', $categoryIds)->get();

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }

            return view('frontend.partials.product-card-load', compact('products'))->render();
        }

        $userId = Auth::id();

        $alreadyFollowed = SellerFollower::where('seller_id', $seller->id)
            ->where('user_id', $userId)->first();

        $totalItem = Product::where('seller_id', $seller->id)->count();

        return view('frontend.shops.details', compact(
            'seller',
            'products',
            'categories',
            'alreadyFollowed',
            'totalItem',
        ));
    }

    public function review(Seller $seller, Request $request)
    {
        $userId = Auth::id();
        $totalItem = Product::where('seller_id', $seller->id)->count();
        $alreadyFollowed = SellerFollower::where('seller_id', $seller->id)
            ->where('user_id', $userId)->first();
        $categoryIds = Product::with('category')
            ->where('seller_id', $seller->id)->pluck('category_id')->unique()->values()->all();

        $categories = Category::whereIn('id', $categoryIds)->get();
        $avgRating = Review::whereIn('product_id', function ($query) use ($seller) {
            $query->select('id')
                ->from('products')
                ->where('seller_id', $seller->id);
        })->avg('rating');

        $ratingsCount = Review::whereIn('product_id', function ($query) use ($seller) {
            $query->select('id')
                ->from('products')
                ->where('seller_id', $seller->id);
        })->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $totalReviews = $ratingsCount->sum();

        $ratingDistribution = [];

        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = isset($ratingsCount[$i])
                ? round(($ratingsCount[$i] / $totalReviews) * 100)
                : 0;
        }

        $reviews = Review::with('user', 'images')
            ->whereIn('product_id', function ($query) use ($seller) {
                $query->select('id')
                    ->from('products')
                    ->where('seller_id', $seller->id);
            })
            ->latest()
            ->skip($request->offset ?? 0)
            ->take(2)
            ->get();

        if ($request->ajax()) {
            // $reviews = Review::with('user', 'images')
            //     ->whereIn('product_id', function ($query) use ($seller) {
            //         $query->select('id')
            //             ->from('products')
            //             ->where('seller_id', $seller->id);
            //     })
            //     ->latest()
            //     ->skip($request->offset ?? 0)
            //     ->take(2)
            //     ->get();

            if ($reviews->isEmpty()) {
                return '';
            }

            return view('frontend.partials.review-card', [
                'reviews' => $reviews,
            ])->render();
        }

        return view('frontend.shops.review', compact('seller', 'totalItem', 'alreadyFollowed', 'categories', 'avgRating', 'totalReviews', 'ratingDistribution', 'reviews'));
    }

    public function markHelpful(Request $request, Review $review)
    {
        $review->increment('helpful_count');

        return response()->json([
            'message' => 'Marked as helpful!',
            'count' => $review->helpful_count,
        ]);
    }

    public function reviewReport(Request $request)
    {
        $seller = Seller::where('id', auth('seller')->id())->first();
        $user = User::where('id', auth()->id())->first();

        if ($seller) {
            ReportReview::create([
                'seller_id' => $seller->id,
                'review_id' => $request->review_id,
            ]);
        } elseif ($user) {
            ReportReview::create([
                'user_id' => $user->id,
                'review_id' => $request->review_id,
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'Review reported successfully.']);
    }
}
