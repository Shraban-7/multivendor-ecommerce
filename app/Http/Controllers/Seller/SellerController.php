<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function shop_details($username)
    {
        $seller = Seller::where('username', $username)
            ->with(['products' => function ($query) {
                $query->latest()->take(8);
            }])
            ->first();

        return view('frontend.shops.shop_details', compact('seller'));
    }
}
