<?php

namespace App\Domain\Product\Http\Controllers\Seller;

use App\Domain\Product\Enums\StockType;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Color;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
use App\Domain\Product\Models\ProductUnit;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\Size;
use App\Domain\Product\Models\StockHistory;
use App\Domain\Product\Repositories\Contracts\BrandRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Domain\Product\Services\ProductService;
use App\Domain\Product\Services\StockManagerService;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly StockManagerService $stockManager,
        private readonly ProductRepositoryInterface $productRepo,
        private readonly CategoryRepositoryInterface $categoryRepo,
        private readonly BrandRepositoryInterface $brandRepo,
        private readonly SellerRepositoryInterface $sellerRepo,
    ) {}

    public function index()
    {
        $categories = $this->categoryRepo->getAllWithSubcategories();
        $brands = $this->brandRepo->getAll();
        $products = $this->productRepo->getForSeller(get_seller_id());

        return view('seller.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = $this->categoryRepo->getAllWithSubcategories();
        $colors = Color::orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();
        $brands = $this->brandRepo->getAll();
        $units = ProductUnit::all();

        return view('seller.products.create', compact('categories', 'colors', 'sizes', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $seller = $this->sellerRepo->findById(get_seller_id());

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:categories,id',
            'brand' => 'nullable',
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0|gte:cost_price',
            'compare_price' => 'nullable|numeric|min:0|lt:price',
            'payment_type' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'unit_value' => 'required|string',
            'low_stock_quantity' => 'required|numeric',
            'thumbnail' => 'nullable|image|max:10000',
            'variants' => 'nullable|string',
        ]);

        $productData = $this->productService->buildProductData($validated, $seller);
        $productData['seller_id'] = $seller->id;
        $productData['slug'] = str_slug('products', 'slug', trim($validated['name']));
        $productData['sku'] = Product::generateSku($seller->id);

        $imageFolder = "{$seller->username}/products";

        if ($request->hasFile('thumbnail')) {
            $productData['thumbnail'] = $this->productService->uploadThumbnail(
                $request->file('thumbnail'),
                $imageFolder
            );
        }

        $product = $this->productRepo->store($productData);

        $variants = json_decode($request->variants ?? 'null', true);
        if (! empty($variants) && is_array($variants)) {
            $this->productService->createVariants($product, $variants, $seller, $imageFolder);
        }

        return successResponse('Product Added Successfully');
    }

    public function show(Product $product)
    {
        $product->load('variants.color', 'variants.size', 'stock_history', 'seo');

        $costPrice = $product->cost_price ?? 0;
        $sellingPrice = $product->price ?? 0;

        $profitAmount = $sellingPrice - $costPrice;
        $profitPercent = $costPrice > 0 ? ($profitAmount / $costPrice) * 100 : 0;

        $product->profit_amount = $profitAmount;
        $product->profit_percent = $profitPercent;
        $product->stock = $product->stock_in - $product->stock_out;

        foreach ($product->variants as $variant) {
            $variant->stock = ($variant->stock_in ?? 0) - ($variant->stock_out ?? 0);
        }

        $colors = Color::orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();

        return view('seller.products.details', compact('product', 'colors', 'sizes'));
    }

    public function edit($slug)
    {
        $product = $this->productRepo->findBySlug($slug);
        if (! $product) {
            abort(404);
        }

        $product->loadCount('variants', 'images', 'seo');

        $categories = $this->categoryRepo->getAllWithSubcategories();
        $brands = $this->brandRepo->getAll();
        $units = ProductUnit::all();

        return view('seller.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update($slug, Request $request)
    {
        $product = $this->productRepo->findBySlug($slug);
        if (! $product) {
            abort(404);
        }
        $seller = $product->seller;

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand' => 'nullable',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0|gte:cost_price',
            'compare_price' => 'nullable|numeric|min:0|lt:price',
            'payment_type' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'unit_value' => 'required|string',
            'best_selling' => 'nullable',
            'is_featured' => 'nullable',
            'thumbnail' => 'nullable|image|max:10000',
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'mimetypes:image/*',
        ]);

        $useMainPrices = $request->has('useMainPrices');
        $useMainDiscount = $request->has('useMainDiscount');

        $productData = $this->productService->buildProductData($validated, $seller);

        if ($request->has('best_selling')) {
            $productData['best_selling'] = $validated['best_selling'] ? 1 : 0;
        }

        if ($request->has('is_featured')) {
            $productData['is_featured'] = $validated['is_featured'] ? 1 : 0;
        }

        $imageFolder = "{$seller->username}/products";

        if ($validated['name'] && $validated['name'] !== $product->name) {
            $productData['slug'] = str_slug('products', 'slug', $validated['name']);
        }

        if ($request->hasFile('thumbnail')) {
            $productData['thumbnail'] = $this->productService->replaceThumbnail(
                $product,
                $request->file('thumbnail'),
                $imageFolder
            );
        }

        if ($request->hasFile('video')) {
            if ($product->video !== null) {
                delete_file($product->video);
            }
            $productData['video'] = upload_file($request->file('video'), "videos/{$seller->username}/products");
        }

        $this->productRepo->update($product, $productData);

        if ($useMainPrices) {
            $product->variants->each(function (ProductVariant $variant) use ($product) {
                $variant->update([
                    'cost_price' => $product->cost_price,
                    'price' => $product->price,
                ]);
            });
        }

        if ($useMainDiscount) {
            $product->variants->each(function (ProductVariant $variant) use ($product) {
                $variant->update([
                    'compare_price' => $product->compare_price,
                ]);
            });
        }

        if ($request->hasFile('files')) {
            $this->productService->attachImages($product, $request->file('files'), $imageFolder);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product Updated Successfully',
            'redirect' => route('seller.products.edit', $product->slug),
        ]);
    }

    public function delete(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('seller.products.index')->with('success', 'Product Removed Successfully');
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:4000',
        ]);

        $product = $this->productRepo->findOrFail($request->product_id);
        $imageFolder = "{$product->seller->username}/products";

        $this->productService->attachImages($product, $request->file('images'), $imageFolder);

        return redirect()->back()->with('success', 'Images added Successfully');
    }

    public function deleteImage(ProductImage $image)
    {
        delete_file($image->image);
        $image->delete();

        return redirect()->back()->with('success', 'Images deleted Successfully');
    }

    public function stockUpdate(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity.*' => 'nullable|numeric|min:0',
            'stock_action.*' => 'nullable|numeric',
            'stock_note.*' => 'nullable|string',
            'stock_quantity_product' => 'nullable|numeric|min:0',
            'stock_action_product' => 'nullable|numeric',
            'stock_note_product' => 'nullable|string',
        ]);

        if ($request->has('stock_quantity_product') && $request->has('stock_action_product')) {
            $type = StockType::from((int) $request->stock_action_product);
            $quantity = (int) $request->stock_quantity_product;
            $note = $request->stock_note_product ?? '';

            try {
                $this->stockManager->adjustStock($product, null, $quantity, $type, $note);
            } catch (RuntimeException $e) {
                return redirect()->back()->with('warning', $e->getMessage());
            }

            return redirect()->back()->with('success', 'Stock updated successfully!');
        }

        $stockQuantities = $request->input('stock_quantity', []);
        $stockActions = $request->input('stock_action', []);
        $stockNotes = $request->input('stock_note', []);

        foreach ($stockQuantities as $variantId => $quantity) {
            if (! $quantity) {
                continue;
            }

            $variant = ProductVariant::find($variantId);
            if (! $variant) {
                continue;
            }

            $type = StockType::from((int) ($stockActions[$variantId] ?? StockType::ADD_STOCK->value));
            $note = $stockNotes[$variantId] ?? '';

            try {
                $this->stockManager->adjustStock($product, $variant, (int) $quantity, $type, $note);
            } catch (RuntimeException) {
                // Skip variants with insufficient stock silently (matches previous behavior)
                continue;
            }
        }

        return redirect()->back()->with('success', 'Stock updated successfully for all variants!');
    }

    public function updateSeo(Request $request, $slug)
    {
        $product = $this->productRepo->findBySlug($slug);
        if (! $product) {
            abort(404);
        }
        $seller = $product->seller;

        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('og_image')) {
            if ($product->seo && $product->seo->og_image) {
                delete_file($product->seo->og_image);
            }
            $validated['og_image'] = upload_file(
                $request->file('og_image'),
                "{$seller->username}/products"
            );
        }

        $this->productService->updateSeo($product, $validated);

        return successResponse('Product SEO Updated Successfully');
    }

    public function stockHistory()
    {
        $seller = $this->sellerRepo->findById(get_seller_id());
        $productIds = Product::where('seller_id', $seller->id)->pluck('id');

        $stockHistories = StockHistory::with(['product', 'variant'])
            ->whereIn('product_id', $productIds)
            ->latest()
            ->paginate(45);

        return view('seller.products.stock_history', compact('stockHistories'));
    }

    public function printBarcode(Request $request)
    {
        $products = Product::where('seller_id', get_seller_id())
            ->with('variants.color', 'variants.size')
            ->get();

        $seller = $this->sellerRepo->findById(get_seller_id());

        return view('seller.barcodes.index', compact('products', 'seller'));
    }

    public function printBarcodeLabels(Request $request)
    {
        $request->validate([
            'sku' => 'required',
            'quantity' => 'required|numeric',
        ]);

        $variant = ProductVariant::with('product.seller')->where('sku', $request->sku)->first();

        if ($variant) {
            return view('seller.barcodes.print_new', ['data' => [
                'sellerName' => $variant->product->seller->business_name,
                'productName' => $variant->product->name,
                'variantName' => $variant->label,
                'sku' => $variant->sku,
                'price' => money($variant->price),
                'quantity' => $request->quantity,
            ]]);
        }

        $product = Product::where('sku', $request->sku)->first();
        if ($product) {
            return view('seller.barcodes.print_new', ['data' => [
                'sellerName' => $product->seller->business_name,
                'productName' => $product->name,
                'variantName' => '',
                'sku' => $product->sku,
                'price' => money($product->price),
                'quantity' => $request->quantity,
            ]]);
        }

        return redirect()->route('seller.products.printBarcode')->with('error', 'Product not found!');
    }

    public function inventory()
    {
        $products = Product::with('variants.color', 'variants.size')
            ->orderBy('name', 'ASC')
            ->where('seller_id', get_seller_id())
            ->paginate(25)
            ->through(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'quantity' => (int) $product->availableStock,
                'total_stock' => (int) $product->totalStock,
                'price' => removeZeroFromDecimal($product->price, 'int'),
                'compare_price' => removeZeroFromDecimal($product->compare_price, 'int'),
                'image' => is_null($product->thumbnail)
                    ? asset('assets/frontend/images/placeholder-img.jpg')
                    : storage_url($product->thumbnail),
                'variants' => $product->variants->map(fn ($variant) => [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'label' => $variant->label,
                    'quantity' => (int) $variant->availableStock,
                    'price' => removeZeroFromDecimal($variant->price, 'int'),
                    'compare_price' => removeZeroFromDecimal($variant->compare_price, 'int'),
                    'image' => is_null($variant->image) ? null : storage_url($variant->image),
                ]),
            ]);

        return view('seller.products.inventory', compact('products'));
    }
}
