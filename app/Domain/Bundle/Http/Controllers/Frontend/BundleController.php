<?php

namespace App\Domain\Bundle\Http\Controllers\Frontend;

use App\Domain\Bundle\Models\Bundle;
use App\Domain\Bundle\Services\BundleInventoryService;
use App\Domain\Bundle\Services\BundlePricingService;
use App\Http\Controllers\Controller;

class BundleController extends Controller
{
    public function __construct(
        private readonly BundlePricingService $pricingService,
        private readonly BundleInventoryService $inventoryService,
    ) {}

    public function index()
    {
        $bundles = Bundle::active()
            ->where('is_visible', true)
            ->with(['items.product'])
            ->latest()
            ->paginate(20);

        return view('frontend.bundles.index', compact('bundles'));
    }

    public function show($slug)
    {
        $bundle = Bundle::active()
            ->where('is_visible', true)
            ->where('slug', $slug)
            ->with(['items.product' => function ($q) {
                $q->with('unit', 'images');
            }, 'images'])
            ->firstOrFail();

        $calculatedPrice = $this->pricingService->calculatePrice($bundle);
        $subtotal = $this->pricingService->calculateSubtotal($bundle);
        $savings = $this->pricingService->savingsAmount($bundle);
        $savingsPercent = $this->pricingService->savingsPercent($bundle);
        $stock = $this->inventoryService->calculateStock($bundle);
        $stockStatus = $this->inventoryService->getStockStatus($bundle);

        $relatedBundles = Bundle::active()
            ->where('is_visible', true)
            ->where('id', '!=', $bundle->id)
            ->with(['items.product'])
            ->latest()
            ->limit(6)
            ->get();

        return view('frontend.bundles.show', compact(
            'bundle', 'calculatedPrice', 'subtotal', 'savings',
            'savingsPercent', 'stock', 'stockStatus', 'relatedBundles'
        ));
    }
}
