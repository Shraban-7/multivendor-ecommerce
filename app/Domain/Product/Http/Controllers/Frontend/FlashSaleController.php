<?php

namespace App\Domain\Product\Http\Controllers\Frontend;

use App\Domain\Product\Models\FlashSale;
use App\Http\Controllers\Controller;

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

    public function show($id)
    {
        $flashSale = FlashSale::where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        $products = $flashSale->products()->paginate(20);

        return view('frontend.flash-sales.show', compact('flashSale', 'products'));
    }
}
