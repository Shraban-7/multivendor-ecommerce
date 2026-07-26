<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Product\Models\Banner;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $limit = 48;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;
        $data['categories'] = Category::category()->limit(16)->get();

        $data['banners'] = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->groupBy('section');

        $data['sellers'] = Seller::active()->limit(8)->get();
        $data['products'] = Product::withDefaultRelations()
            ->active()
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();
        $products = $data['products'];

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }

            return view('frontend.partials.product-card-load', [
                'products' => $products,
            ])->render();
        }

        $data['flash_sales'] = FlashSale::active()
            ->withCount('approveProducts')
            ->having('approve_products_count', '>', 0)
            ->with('approveProducts.product')
            ->get();

        return view('frontend.home', $data);

        // return view('frontend.pages.home', $data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug', $slug)->first();

        return view('frontend.pages.category_detail', compact('category'));
    }
}
