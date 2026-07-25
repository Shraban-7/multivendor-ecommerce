<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::category()->with('subcategories')->get();

        return apiResourceResponse(CategoryResource::collection($categories));
    }
}
