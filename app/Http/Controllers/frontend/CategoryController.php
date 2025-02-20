<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function category_details($slug)
    {
        $category = Category::where('slug', $slug)->with(['products','subcategories'])->first();

        return view('frontend.pages.category_detail', compact('category'));
    }
}
