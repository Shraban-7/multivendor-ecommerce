<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductListResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['categories'] = CategoryResource::collection(Category::category()->get());

        $newProducts = Product::latest('id')->take(8)->get();
        $trendingProducts = Product::trending()->take(8)->get();

        $data['products'] = array(
            'trending' => ProductListResource::collection($trendingProducts),
            'new' => ProductListResource::collection($newProducts),
        );

        return apiResponse($data);
    }
}