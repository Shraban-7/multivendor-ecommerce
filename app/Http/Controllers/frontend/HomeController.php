<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data['categories'] = Category::slider()->get();
        $data['special_category'] = Category::special()->with('banners')->first();
        $data['light_deals'] = Product::lightDeal()->take(8)->get();
        $data['interest_products'] = Product::interest()->take(8)->get();

        // return $data['interest_products'];
        return view('frontend.pages.home',$data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug',$slug)->first();

        return view('frontend.pages.category_detail',compact('category'));
    }
}
