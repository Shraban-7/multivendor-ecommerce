<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.static_page', compact('page'));
    }
}
