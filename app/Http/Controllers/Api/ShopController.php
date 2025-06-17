<?php
namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;

class ShopController extends Controller
{
    public function index()
    {
        $data['categories'] = CategoryResource::collection(Category::category()->get());

        $data['brands'] = BrandResource::collection(Brand::get());


        return apiResponse($data);
    }
}
