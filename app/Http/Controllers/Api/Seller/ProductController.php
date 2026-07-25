<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
use App\Domain\Product\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('seller_id', Auth::id())
            ->with(['category', 'subcategory', 'variants']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $products = $query->latest()->paginate($request->input('limit', 25));

        return apiResourceResponse($products->through(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'thumbnail' => $p->thumbnail,
            'category' => $p->category?->name,
            'subcategory' => $p->subcategory?->name,
            'selling_price' => (float) $p->selling_price,
            'discounted_price' => (float) ($p->discounted_price ?? 0),
            'stock_in' => (int) $p->stock_in,
            'stock_out' => (int) $p->stock_out,
            'available_stock' => $p->available_stock,
            'views' => (int) $p->views,
            'status' => $p->status,
            'variants_count' => $p->variants->count(),
            'created_at' => $p->created_at,
        ]));
    }

    public function create()
    {
        return apiResponse([
            'categories' => \App\Domain\Product\Models\Category::whereNull('parent_id')->get(['id', 'name']),
            'brands' => \App\Domain\Product\Models\Brand::get(['id', 'name']),
            'units' => \App\Domain\Product\Models\Unit::get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'unit_value' => 'nullable|numeric',
            'stock_in' => 'required|integer|min:0',
            'low_stock_quantity' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $validator->validated();
        $data['seller_id'] = Auth::id();
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);
        $data['sku'] = strtoupper(substr($request->name, 0, 3)) . '-' . Str::random(6);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = upload_file($request->file('thumbnail'), 'images/products/thumbnails');
        }

        $product = Product::create($data);

        return apiResponse([
            'product' => $product,
        ], 'Product created successfully.');
    }

    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->where('seller_id', Auth::id())
            ->with(['category', 'subcategory', 'brand', 'variants', 'images'])->firstOrFail();

        return apiResponse([
            'product' => $product,
            'categories' => \App\Domain\Product\Models\Category::whereNull('parent_id')->get(['id', 'name']),
            'subcategories' => \App\Domain\Product\Models\Category::where('parent_id', $product->category_id)->get(['id', 'name']),
            'brands' => \App\Domain\Product\Models\Brand::get(['id', 'name']),
            'units' => \App\Domain\Product\Models\Unit::get(['id', 'name']),
        ]);
    }

    public function update(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->where('seller_id', Auth::id())->firstOrFail();

        $validator = validateRequest($request, [
            'name' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'buying_price' => 'sometimes|numeric|min:0',
            'selling_price' => 'sometimes|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'stock_in' => 'sometimes|integer|min:0',
            'low_stock_quantity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product->update($validator->validated());

        return apiResponse([
            'product' => $product->fresh()->load(['category', 'subcategory']),
        ], 'Product updated successfully.');
    }

    public function updateSeo(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->where('seller_id', Auth::id())->firstOrFail();

        $validator = validateRequest($request, [
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product->update($validator->validated());

        return successResponse('SEO updated successfully.');
    }

    public function stockUpdate(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        $validator = validateRequest($request, [
            'type' => 'required|in:ADD,REMOVE,SET',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $type = $request->type;
        $quantity = (int) $request->quantity;

        DB::transaction(function () use ($product, $type, $quantity) {
            if ($type === 'ADD') {
                $product->increment('stock_in', $quantity);
            } elseif ($type === 'REMOVE') {
                if ($quantity > ($product->stock_in - $product->stock_out)) {
                    throw new \RuntimeException('Insufficient stock.');
                }
                $product->increment('stock_out', $quantity);
            } elseif ($type === 'SET') {
                $product->update(['stock_in' => $quantity, 'stock_out' => 0]);
            }
        });

        return apiResponse([
            'available_stock' => $product->fresh()->available_stock,
        ], 'Stock updated successfully.');
    }

    public function uploadImage(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required|exists:products,id',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product = Product::where('id', $request->product_id)->where('seller_id', Auth::id())->firstOrFail();

        $uploaded = [];
        foreach ($request->file('images') as $image) {
            $path = upload_file($image, 'images/products/gallery');
            $uploaded[] = $product->images()->create(['image' => $path]);
        }

        return apiResponse(['images' => $uploaded], 'Images uploaded successfully.');
    }

    public function deleteImage(ProductImage $image)
    {
        $product = Product::where('id', $image->product_id)->where('seller_id', Auth::id())->firstOrFail();
        delete_file($image->image);
        $image->delete();

        return successResponse('Image deleted successfully.');
    }

    public function deleteVariant(ProductVariant $variant)
    {
        $product = Product::where('id', $variant->product_id)->where('seller_id', Auth::id())->firstOrFail();
        $variant->delete();

        return successResponse('Variant deleted successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        $product->delete();

        return successResponse('Product deleted successfully.');
    }

    public function inventory(Request $request)
    {
        $products = Product::where('seller_id', Auth::id())
            ->with(['category'])
            ->select('id', 'name', 'slug', 'thumbnail', 'sku', 'stock_in', 'stock_out', 'low_stock_quantity', 'selling_price', 'category_id')
            ->latest()
            ->paginate($request->input('limit', 25));

        return apiResourceResponse($products->through(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'thumbnail' => $p->thumbnail,
            'category' => $p->category?->name,
            'stock_in' => (int) $p->stock_in,
            'stock_out' => (int) $p->stock_out,
            'available' => $p->available_stock,
            'low_stock_quantity' => (int) ($p->low_stock_quantity ?? 0),
            'is_low_stock' => $p->available_stock <= ($p->low_stock_quantity ?? 0),
            'selling_price' => (float) $p->selling_price,
        ]));
    }

    public function printBarcode()
    {
        return apiResponse([
            'products' => Product::where('seller_id', Auth::id())
                ->where('status', 1)
                ->get(['id', 'name', 'sku', 'selling_price']),
        ]);
    }

    public function printLabels()
    {
        return apiResponse([
            'products' => Product::where('seller_id', Auth::id())
                ->where('status', 1)
                ->get(['id', 'name', 'sku', 'selling_price']),
        ]);
    }

    public function stockHistory(Request $request)
    {
        $histories = \App\Domain\Product\Models\StockHistory::whereHas('product', fn ($q) => $q->where('seller_id', Auth::id()))
            ->with('product:id,name,sku')
            ->latest()
            ->paginate($request->input('limit', 25));

        return apiResourceResponse($histories);
    }

    public function bulkStockUpdate(Request $request)
    {
        $validator = validateRequest($request, [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.stock_in' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        foreach ($request->products as $item) {
            $product = Product::where('id', $item['id'])->where('seller_id', Auth::id())->first();
            if ($product) {
                $product->update(['stock_in' => $item['stock_in'], 'stock_out' => 0]);
            }
        }

        return successResponse('Stock updated successfully.');
    }
}
