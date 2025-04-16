<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SellerFollower;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function follow(Seller $seller)
    {
        $userId = Auth::id();
        $alreadyFollowed = SellerFollower::where('seller_id', $seller->id)->where('user_id', $userId)->first();

        if ($alreadyFollowed) {
            $alreadyFollowed->delete();
            $seller->decrement('total_follower');
            return redirect()->back()->with('success', "Unfollowed Successfully");
        }

        SellerFollower::create([
            'seller_id' => $seller->id,
            'user_id' => $userId,
        ]);

        $seller->update([
            'total_follower' => $seller->total_follower + 1
        ]);

        return redirect()->back()->with('success', "Followed Successfully");
    }

    public function shop($username, Request $request)
    {
        $seller = Seller::where('username', $username)->firstOrFail();

        $limit = 8;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $productQuery = Product::with('category')
            ->where('seller_id', $seller->id);

        if ($request->sortBy == 'best-selling') {
            $productQuery->where('best_selling', 1);
        } elseif ($request->sortBy == 'trending') {
            $productQuery->where('is_trending', 1);
        } elseif ($request->sortBy == 'new-arrivals') {
            $productQuery->latest();
        } else {
            $productQuery->orderBy('id','asc');
        }

        $products = $productQuery->skip($skip)->take($limit)->get();

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

        $avgRating = Review::whereIn('product_id', function ($query) use ($seller) {
            $query->select('id')
                ->from('products')
                ->where('seller_id', $seller->id);
        })->avg('rating');

        $totalItem = Product::where('seller_id', $seller->id)->count();

        return view('frontend.shops.details', compact(
            'seller',
            'products',
            'categories',
            'alreadyFollowed',
            'avgRating',
            'totalItem'
        ));
    }


    public function review(Seller $seller)
    {
        return view('frontend.shops.review', compact('seller'));
    }
}
