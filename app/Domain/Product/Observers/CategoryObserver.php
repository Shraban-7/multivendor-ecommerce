<?php

namespace App\Domain\Product\Observers;

use App\Domain\Product\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(Category $category): void
    {
        Cache::forget('dashboard.categories');
    }

    public function updated(Category $category): void
    {
        Cache::forget('dashboard.categories');
    }

    public function deleted(Category $category): void
    {
        Cache::forget('dashboard.categories');
    }
}
