<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{

    public function profile($username, Request $request)
    {
        $seller = Seller::where('username', $username)->first();

        if ($request->isMethod('GET')) {
            return view('seller.profile', compact('seller'));
        }

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
            'phone' => 'required|string',
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
            'business_address' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'business_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($seller->fullname !== $request->fullname) {
            $data['username'] = str_slug('sellers', 'username', $request->fullname);
        } else {
            $data['username'] = $seller->username;
        }

        if ($request->hasFile('image')) {
            if (!empty($seller->image)) {
                delete_file($seller->image);
            }
            $filePath = 'images/seller/avatar';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $seller->image;
        }

        if ($request->hasFile('business_logo')) {
            if (!empty($seller->business_logo)) {
                delete_file($seller->business_logo);
            }
            $filePath = 'images/seller/business_logo';
            $data['business_logo'] = upload_file($request->file('business_logo'), $filePath);
        } else {
            $data['business_logo'] = $seller->business_logo;
        }


        $seller->update($data);

        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }

    public function shop_details($username, Request $request)
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

        return view('frontend.shops.shop_details', compact('seller', 'products', 'categories'));
    }
}
