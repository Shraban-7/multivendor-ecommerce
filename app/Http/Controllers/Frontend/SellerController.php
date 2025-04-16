<?php

namespace App\Http\Controllers\Frontend;

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

        if($alreadyFollowed) {
            $alreadyFollowed->delete();
            $seller->decrement('total_follower');
            return redirect()->back()->with('success', "Unfollowed Successfully");
        }

        SellerFollower::create([
            'seller_id' => $seller->id,
            'user_id' => $userId,
        ]);

        return redirect()->back()->with('success', "Followed Successfully");
    }

    public function shop($username, Request $request)
    {
        $seller = Seller::where('username', $username)->firstOrFail();

        $limit = 8;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $products = Product::with('category')
            ->where('seller_id', $seller->id)
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();

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

        $alreadyFollowed = SellerFollower::where('seller_id', $seller->id)->where('user_id', $userId)->first();

        return view('frontend.shops.details', compact('seller', 'products', 'categories', 'alreadyFollowed'));
    }
}
