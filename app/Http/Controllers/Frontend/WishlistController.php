<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->get();

        return view('frontend.pages.wishlist',compact('wishlists'));
    }

    public function store(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['success' => false, 'error' => 'Product not found']);
        }

        $wishlist = Wishlist::where([
            'user_id' => Auth::user()->id,
            'product_id' => $product->id
        ])->first();

        if (!$wishlist) {
            Wishlist::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Product added to Wishlist', 'action' => 'add_to_wishlist']);
    }

    public function delete(Wishlist $wishlist)
    {
        $wishlist->delete();

        return response()->json(['success' => true, 'message' => 'Product Removed From Wishlist']);
    }
}
