<?php

namespace App\Domain\Product\Observers;

use App\Domain\Product\Models\Banner;
use Illuminate\Support\Facades\Cache;

class BannerObserver
{
    public function created(Banner $banner): void
    {
        Cache::forget('dashboard.banners');
    }

    public function updated(Banner $banner): void
    {
        Cache::forget('dashboard.banners');
    }

    public function deleted(Banner $banner): void
    {
        Cache::forget('dashboard.banners');
    }
}
