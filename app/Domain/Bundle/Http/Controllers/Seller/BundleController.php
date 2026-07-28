<?php

namespace App\Domain\Bundle\Http\Controllers\Seller;

use App\Domain\Bundle\Models\Bundle;
use App\Domain\Bundle\Services\BundleInventoryService;
use App\Domain\Bundle\Services\BundlePricingService;
use App\Domain\Bundle\Services\BundleService;
use App\Domain\Bundle\Services\BundleValidationService;
use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductUnit;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function __construct(
        private readonly BundleService $bundleService,
        private readonly BundleValidationService $validationService,
        private readonly BundleInventoryService $inventoryService,
        private readonly BundlePricingService $pricingService,
        private readonly SellerRepositoryInterface $sellerRepo,
    ) {}

    public function index()
    {
        $bundles = Bundle::forSeller(get_seller_id())
            ->with(['items.product', 'items.product.unit'])
            ->latest()
            ->paginate(15);

        return view('seller.bundles.index', compact('bundles'));
    }

    public function create()
    {
        $seller = $this->sellerRepo->findById(get_seller_id());
        $products = Product::where('seller_id', $seller->id)
            ->where('status', Product::STATUS_ACTIVE)
            ->where('is_visible', true)
            ->get(['id', 'name', 'sku', 'price', 'stock_in', 'stock_out']);
        $categories = Category::category()->orderBy('name')->with('subcategories')->get();
        $brands = Brand::orderBy('name')->get();
        $units = ProductUnit::all();

        return view('seller.bundles.create', compact('products', 'categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $seller = $this->sellerRepo->findById(get_seller_id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'type' => 'required|in:fixed,mix_match',
            'price_type' => 'required|in:auto,manual',
            'price' => 'required_if:price_type,manual|nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:2',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:999',
            'items.*.is_optional' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $itemErrors = $this->validationService->validateItems($validated['items'] ?? [], $seller->id);
        if (! empty($itemErrors)) {
            return redirect()->back()->withInput()->withErrors(['items' => implode('<br>', $itemErrors)]);
        }

        $bundle = $this->bundleService->create($validated, $seller);

        return redirect()->route('seller.bundles.show', $bundle)
            ->with('success', 'Bundle created successfully');
    }

    public function show(Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $bundle->load(['items.product' => function ($q) {
            $q->with('unit');
        }, 'items.product.images', 'images']);

        $calculatedPrice = $this->pricingService->calculatePrice($bundle);
        $subtotal = $this->pricingService->calculateSubtotal($bundle);
        $savings = $this->pricingService->savingsAmount($bundle);
        $savingsPercent = $this->pricingService->savingsPercent($bundle);
        $stock = $this->inventoryService->calculateStock($bundle);
        $stockStatus = $this->inventoryService->getStockStatus($bundle);

        return view('seller.bundles.show', compact(
            'bundle', 'calculatedPrice', 'subtotal', 'savings', 'savingsPercent', 'stock', 'stockStatus'
        ));
    }

    public function edit(Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $bundle->load(['items.product', 'images', 'pricingRules']);

        $seller = $this->sellerRepo->findById(get_seller_id());
        $products = Product::where('seller_id', $seller->id)
            ->whereIn('status', [Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])
            ->get(['id', 'name', 'sku', 'price', 'stock_in', 'stock_out']);
        $categories = Category::category()->orderBy('name')->with('subcategories')->get();
        $brands = Brand::orderBy('name')->get();
        $units = ProductUnit::all();

        return view('seller.bundles.edit', compact('bundle', 'products', 'categories', 'brands', 'units'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255',
            'type' => 'required|in:fixed,mix_match',
            'price_type' => 'required|in:auto,manual',
            'price' => 'required_if:price_type,manual|nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'is_visible' => 'nullable|boolean',
            'items' => 'required|array|min:2',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:999',
            'items.*.is_optional' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $itemErrors = $this->validationService->validateItems($validated['items'] ?? [], $bundle->seller_id);
        if (! empty($itemErrors)) {
            return redirect()->back()->withInput()->withErrors(['items' => implode('<br>', $itemErrors)]);
        }

        $this->bundleService->update($bundle, $validated);

        return redirect()->route('seller.bundles.show', $bundle)
            ->with('success', 'Bundle updated successfully');
    }

    public function destroy(Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $this->bundleService->delete($bundle);

        return redirect()->route('seller.bundles.index')
            ->with('success', 'Bundle deleted successfully');
    }

    public function toggleVisibility(Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $this->bundleService->toggleVisibility($bundle);

        return redirect()->back()->with('success', 'Bundle visibility updated');
    }

    public function duplicate(Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $seller = $this->sellerRepo->findById(get_seller_id());
        $this->bundleService->duplicate($bundle, $seller);

        return redirect()->route('seller.bundles.index')
            ->with('success', 'Bundle duplicated successfully');
    }

    public function updateStatus(Request $request, Bundle $bundle)
    {
        abort_unless($bundle->seller_id === get_seller_id(), 403);

        $request->validate(['status' => 'required|integer|in:0,1,2,3']);

        $bundle->update(['status' => (int) $request->status]);

        return redirect()->back()->with('success', 'Bundle status updated');
    }

    public function getProducts(Request $request)
    {
        $sellerId = get_seller_id();
        $search = $request->get('q', '');

        $products = Product::where('seller_id', $sellerId)
            ->where('status', Product::STATUS_ACTIVE)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get(['id', 'name', 'sku', 'price', 'stock_in', 'stock_out']);

        return response()->json($products->map(fn ($p) => [
            'id' => $p->id,
            'text' => "{$p->name} ({$p->sku}) - " . money($p->price),
            'price' => (float) $p->price,
            'stock' => (int) $p->availableStock,
        ]));
    }
}
