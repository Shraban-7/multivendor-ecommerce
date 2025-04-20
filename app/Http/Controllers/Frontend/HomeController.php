<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroBanner;
use App\Models\HomeMidBanner;
use App\Models\Product;
use App\Models\PromoPoster;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    public function index()
    {
        $data['categories'] = Category::slider()->get();
        $data['special_category'] = Category::special()->with(['banners','products'])->first();
        $data['light_deals'] = Product::lightDeal()->with('unit')->take(8)->get();
        $data['interest_products'] = Product::interest()->with('unit')->take(8)->get();
        $data['trending_products'] = Product::trending()->with('unit')->take(3)->get();
        $data['community_products'] = Product::community()->with('unit')->take(8)->get();
        $data['new_arrival_products'] = Product::orderBy('id', 'DESC')->with('unit')
        ->skip(6)
        ->take(Product::count() - 12)
        ->get();

        $data['hero_grid_one'] = HeroBanner::where('position',1)->first();
        $data['hero_grid_two'] = HeroBanner::where('position',2)->first();
        $data['hero_grid_three'] = HeroBanner::where('position',3)->first();
        $data['hero_grid_four'] = HeroBanner::where('position',4)->first();
        $data['hero_grid_five'] = HeroBanner::where('position',5)->first();

        $data['gallery_feature_pro_one'] = HomeMidBanner::where('position',1)->first();
        $data['gallery_feature_pro_two'] = HomeMidBanner::where('position',2)->first();
        $data['gallery_feature_pro_three'] = HomeMidBanner::where('position',3)->first();
        $data['gallery_feature_pro_four'] = HomeMidBanner::where('position',4)->first();
        $data['gallery_feature_pro_five'] = HomeMidBanner::where('position',5)->first();

        $data['promo_poster_one'] = PromoPoster::where('position',1)->first();
        $data['promo_poster_two'] = PromoPoster::where('position',2)->first();

        $data['featured_products'] = Product::featured()->take(8)->latest()->get();

        return view('frontend.pages.home',$data);
    }

    public function category_details($slug)
    {
        $category = Category::where('slug',$slug)->first();

        return view('frontend.pages.category_detail',compact('category'));
    }
}
