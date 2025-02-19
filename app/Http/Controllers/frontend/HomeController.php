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
        $data['light_deals'] = Product::lightDeal()->get();

        // return $data['light_deals'];
        return view('frontend.pages.home',$data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug',$slug)->first();

        return view('frontend.pages.category_detail',compact('category'));
    }
}
