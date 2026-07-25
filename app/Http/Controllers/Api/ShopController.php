<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Http\Resources\BrandResource;
use App\Domain\Product\Http\Resources\CategoryResource;
use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function index()
    {
        $data['categories'] = CategoryResource::collection(Category::category()->get());

        $data['brands'] = BrandResource::collection(Brand::get());

        return apiResponse($data);
    }
}
