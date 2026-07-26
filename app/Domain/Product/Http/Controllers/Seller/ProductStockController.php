<?php

namespace App\Domain\Product\Http\Controllers\Seller;

use App\Domain\Product\Enums\StockType;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\StockHistory;
use App\Domain\Product\Services\StockManagerService;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;

class ProductStockController extends Controller
{
    public function __construct(
        private readonly StockManagerService $stockManager,
    ) {}

    public function index()
    {
        $seller = Seller::find(get_seller_id());

        $stockHistories = StockHistory::with(['product', 'variant.color', 'variant.size'])
            ->whereIn('product_id', function ($q) use ($seller) {
                $q->select('id')->from('products')->where('seller_id', $seller->id);
            })
            ->latest()
            ->paginate(45);

        return view('seller.products.stock_history', compact('stockHistories'));
    }

    public function products()
    {
        $products = Product::select('id', 'name', 'sku', 'stock_in', 'stock_out')
            ->with('variants:id,product_id,stock_in,stock_out')
            ->where('seller_id', get_seller_id())
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'current_stock' => max((int) $product->totalStock, 0),
                    'has_variants' => $product->variants->isNotEmpty(),
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function variants(Request $request)
    {
        $product = Product::where('id', $request->product_id)
            ->where('seller_id', get_seller_id())
            ->firstOrFail();

        $variants = ProductVariant::where('product_id', $product->id)
            ->with('color', 'size')
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->label,
                    'sku' => $variant->sku,
                    'current_stock' => max((int) $variant->availableStock, 0),
                ];
            });

        return response()->json(['variants' => $variants]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:0',
            'stock_action' => 'required|integer',
            'note' => 'nullable|string|max:500',
        ]);

        $product = Product::where('id', $data['product_id'])
            ->where('seller_id', get_seller_id())
            ->first();

        if (! $product) {
            return redirect()->back()->with('warning', 'Product not found.');
        }

        $variant = null;
        if (! empty($data['variant_id'])) {
            $variant = ProductVariant::where('id', $data['variant_id'])
                ->where('product_id', $product->id)
                ->first();

            if (! $variant) {
                return redirect()->back()->with('warning', 'Variant not found for this product.');
            }
        }

        $type = StockType::tryFrom((int) $data['stock_action']);
        if ($type === null) {
            return redirect()->back()->with('warning', 'Invalid stock action.');
        }

        try {
            $this->stockManager->adjustStock(
                $product,
                $variant,
                (int) $data['quantity'],
                $type,
                $data['note'] ?? ''
            );
        } catch (RuntimeException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Stock updated successfully!');
    }
}
