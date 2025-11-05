<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Models\Product;
use App\Enums\StockType;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class ProductStockController extends Controller
{
    public function index()
    {
        $seller = Seller::find(get_seller_id());

        $productIds = Product::where('seller_id', $seller->id)->pluck('id');

        $stockHistories = StockHistory::with(['product', 'variant'])
            ->whereIn('product_id', $productIds)
            ->latest()
            ->paginate(45);

        return view('seller.products.stock_history', compact('stockHistories'));
    }

    public function products()
    {
        $products = Product::select('id', 'name', 'stock_in', 'stock_out')
            ->where('seller_id', get_seller_id())
            ->get()
            ->map(function ($product) {
                $product->current_stock = (int)$product->stock_in - (int)$product->stock_out;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'current_stock' => max($product->current_stock, 0),
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function variants(Request $request)
    {
        $variants = ProductVariant::where('product_id', $request->product_id)
            ->with('option_values.option') 
            ->get()
            ->map(function ($variant) {
                $currentStock = (int) $variant->stock_in - (int) $variant->stock_out;

                return [
                    'id' => $variant->id,
                    'name' => $variant->fullName,
                    'sku' => $variant->sku,
                    'current_stock' => max($currentStock, 0),
                ];
            });

        return response()->json(['variants' => $variants]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required',
            'variant_id' => 'nullable',
            'quantity' => 'nullable|numeric|min:0',
            'stock_action'   => 'nullable|numeric',
            'note'     => 'nullable|string',
        ]);

        $variant = ProductVariant::find($data['variant_id']);
        $product = Product::find($data['product_id']);

        $action = $data['stock_action'];
        $quantity = $data['quantity'];
        $note = $data['note'];

        $currentStock = $variant ? ($variant->stock_in - $variant->stock_out) : ($product->stock_in - $product->stock_out);

        if ($action == StockType::REMOVE_STOCK->value && $quantity > $currentStock) {
            return redirect()->back()->with('warning', 'Insufficient stock! You cannot remove more than the available quantity.');
        }

        if ($variant) {
            StockHistory::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
                'type' => $action,
                'note' => $note,
            ]);

            if ($action == StockType::SET_EXACT_STOCK->value) {
                $variant->stock_in = $quantity;
                $variant->stock_out = 0;
            } elseif ($action == StockType::ADD_STOCK->value) {
                $variant->stock_in += $quantity;
            } elseif ($action == StockType::REMOVE_STOCK->value) {
                $variant->stock_in -= $quantity;
                if ($variant->stock_in < 0) $variant->stock_in = 0;
            }

            $variant->save();
        } else {
            StockHistory::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'type' => $action,
                'note' => $note,
            ]);

            if ($action == StockType::SET_EXACT_STOCK->value) {
                $product->stock_in = $quantity;
                $product->stock_out = 0;
            } elseif ($action == StockType::ADD_STOCK->value) {
                $product->stock_in += $quantity;
            } elseif ($action == StockType::REMOVE_STOCK->value) {
                $product->stock_in -= $quantity;
                if ($product->stock_in < 0) $product->stock_in = 0;
            }

            $product->save();
        }

        return redirect()->back()->with('success', 'Stock updated successfully!');
    }
}
