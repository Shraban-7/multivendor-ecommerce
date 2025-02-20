<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('seller.dashboard');
    }

    public function shop_details($username)
    {
        $seller = Seller::where('username', $username)
            ->with(['products' => function ($query) {
                $query->latest()->take(8);
            }])
            ->first();

        return view('frontend.pages.shop_details', compact('seller'));
    }
}
