<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $seller = Seller::find(get_seller_id());

        $flashSales = FlashSale::active()
            ->orderBy('start_time', 'asc')
            ->get();

        $sellerFlashSales = FlashSale::whereHas('products', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->get();

        return view('seller.flash-sales.index', compact('flashSales', 'sellerFlashSales'));
    }

    public function details($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $seller = Seller::find(get_seller_id());

        $myProducts = $seller->products()->active()->get();

        $submitted = FlashSaleProduct::with('product')
            ->where('flash_sale_id', $id)
            ->where('seller_id', $seller->id)
            ->get();

        return view('seller.flash-sales.details', compact('flashSale', 'submitted', 'myProducts'));
    }

    public function submit(Request $request, $id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $seller = Seller::find(get_seller_id());

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = $seller->products()->findOrFail($request->product_id);

        if (FlashSaleProduct::where('flash_sale_id', $id)
            ->where('product_id', $product->id)
            ->exists()
        ) {
            return back()->with('error', 'This product is already submitted.');
        }

        FlashSaleProduct::create([
            'flash_sale_id' => $id,
            'seller_id' => $seller->id,
            'product_id' => $product->id,
            'stock_in' => $product->totalStock,
            'stock_out' => 0,
            'status' => FlashSaleProduct::STATUS_PENDING,
        ]);

        return back()->with('success', 'Product submitted. Awaiting admin approval.');
    }
}
