<?php

namespace App\Domain\Product\Http\Controllers\Api;

use App\Domain\Product\Http\Resources\CategoryResource;
use App\Domain\Product\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::category()->with('subcategories')->get();

        return apiResourceResponse(CategoryResource::collection($categories));
    }
}
