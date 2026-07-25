<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['html' => '']);
        }

        $products = Product::where('name', 'like', '%'.$query.'%')
            ->take(5)
            ->get();

        $sellers = Seller::where('business_name', 'like', '%'.$query.'%')
            ->take(5)
            ->get();

        $html = view('components.frontend.search-suggestions', compact('products', 'sellers'))->render();

        return response()->json(['html' => $html]);
    }
}
