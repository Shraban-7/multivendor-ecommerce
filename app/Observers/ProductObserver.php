<?php

namespace App\Observers;

use App\Domain\Product\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    private function clearProductCache(): void
    {
        Cache::forget('products.featured');
        Cache::forget('products.trending');
    }

    public function created(Product $product): void
    {
        $this->clearProductCache();
    }

    public function updated(Product $product): void
    {
        $this->clearProductCache();
    }

    public function deleted(Product $product): void
    {
        $this->clearProductCache();
    }
}
