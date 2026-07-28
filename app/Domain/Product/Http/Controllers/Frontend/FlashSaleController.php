<?php

namespace App\Domain\Product\Http\Controllers\Frontend;

use App\Domain\Bundle\Models\Bundle;
use App\Domain\Product\Models\FlashSale;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::where('is_active', 1)
            ->whereDate('end_time', '>=', now())
            ->withCount('products')
            ->orderBy('start_time', 'asc')
            ->paginate(9);

        return view('frontend.flash-sales.index', compact('flashSales'));
    }

    public function show($id, Request $request)
    {
        $flashSale = FlashSale::where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        $products = $flashSale->products()->with('product')->paginate(20);

        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return '';
            }
            $html = '';
            foreach ($products as $productItem) {
                $html .= view('components.frontend.flash-sale-card', ['product' => $productItem->product])->render();
            }
            return $html;
        }

        $bundles = Bundle::active()
            ->where('is_visible', true)
            ->with(['items.product'])
            ->latest()
            ->limit(8)
            ->get();

        return view('frontend.flash-sales.show', compact('flashSale', 'products', 'bundles'));
    }
}
