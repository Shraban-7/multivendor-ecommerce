<?php

namespace App\Http\Controllers\Seller;

use App\Enums\StockType;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductUnit;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::where('seller_id', get_seller_id())->latest('id')->paginate(50);
        $categories = Category::category()->with('subcategories')->get();
        $brands     = Brand::all();

        return view('seller.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::category()->with('subcategories')->get();
        $brands     = Brand::all();
        $units      = ProductUnit::all();

        return view('seller.products.create', compact('categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $seller = seller();

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand' => 'nullable',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'vat_percent' => 'required|numeric',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'payment_type' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'unit_value' => 'required|string',
            'is_trending' => 'nullable|boolean',
            'best_selling' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'low_stock_quantity' => 'required|numeric',
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'file|max:4096|mimetypes:image/*',
            'meta_title' => 'nullable|string',
        ]);

        $brandId = null;

        if (!empty($validated['brand'])) {
            if (is_numeric($validated['brand'])) {
                $brandId = (int) $validated['brand'];
            } else {
                $brand = Brand::firstOrCreate(
                    ['name' => trim($validated['brand'])],
                    ['slug' => str_slug('brands', 'slug', $validated['brand'])]
                );
                $brandId = $brand->id;
            }
        }

        $validated['brand_id'] = $brandId;

        unset($validated['brand']);

        $imageFolder = "images/{$seller->username}/products";

        // $validated['thumbnail'] = upload_with_watermark($request->file('thumbnail'), "$imageFolder/thumb");

        if ($request->hasFile('video')) {
            $validated['video'] = upload_file($request->file('video'), "videos/{$seller->username}/products");
        }

        $validated['seller_id'] = $seller->id;
        $validated['slug'] = str_slug('products', 'slug', $validated['name']);


        $product = Product::create($validated);

        $variantData = [
            'product_id' => $product->id,
            'sku' => $validated['sku'] ?? ProductVariant::gernerate_sku(),
            'buying_price' => $validated['buying_price'],
            'selling_price' => $validated['selling_price'],
            'discount_type' => $validated['discount_type'] ?? null,
            'discount_value' => $validated['discount_value'] ?? null,
            'discount_amount' => (isset($validated['discount_type'], $validated['discount_value']) && $validated['discount_type'] && $validated['discount_value'])
                ? calculate_discount_amount($validated['selling_price'], $validated['discount_type'], $validated['discount_value'])
                : 0,
            'discounted_price' => (isset($validated['discount_type'], $validated['discount_value']) && $validated['discount_type'] && $validated['discount_value'])
                ? calculate_discounted_price($validated['selling_price'], $validated['discount_type'], $validated['discount_value'])
                : $validated['selling_price'],
            'is_default' => 1,
        ];

        $variant = ProductVariant::create($variantData);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => upload_file($file, $imageFolder),
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Product Added Successfully']);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (! $product) {
            abort(404, 'Product not found');
        }

        $product->load('variants.option_values', 'stock_history');

        $costPrice = $product->buying_price ?? 0;
        $sellingPrice = $product->selling_price ?? 0;

        $profitAmount  = $sellingPrice - $costPrice;
        $profitPercent = $costPrice > 0 ? ($profitAmount / $costPrice) * 100 : 0;
        $productStock  = $product->stock_in - $product->stock_out;

        $product->profit_amount  = $profitAmount;
        $product->profit_percent = $profitPercent;
        $product->stock          = $product->stock;

        foreach ($product->variants as $variant) {
            $variant->stock = ($variant->stock_in ?? 0) - ($variant->stock_out ?? 0);
        }

        $product_options = Option::with('options')->get();

        return view('seller.products.details', compact('product', 'product_options'));
    }

    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->first();

        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();
        $units = ProductUnit::all();

        return view('seller.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update($slug, Request $request)
    {
        $seller = seller();

        $product = Product::where('slug', $slug)->first();

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand' => 'nullable',
            'name' => 'required|string|max:255',
            'short_description'  => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'vat_percent' => 'required|numeric',
            'payment_type' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'unit_value' => 'required|string',
            'is_trending' => 'nullable|boolean',
            'best_selling' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|max:4096',
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'mimetypes:image/*',
        ]);


        $brandId = null;

        if (!empty($validated['brand'])) {
            if (is_numeric($validated['brand'])) {
                $brandId = (int) $validated['brand'];
            } else {
                $brand = Brand::firstOrCreate(
                    ['name' => trim($validated['brand'])],
                    ['slug' => str_slug('brands', 'slug', $validated['brand'])]
                );
                $brandId = $brand->id;
            }
        }

        $validated['brand_id'] = $brandId;

        unset($validated['brand']);

        $imageFolder = "images/{$seller->username}/products";

        if ($validated['name'] && $validated['name'] !== $product->name) {
            $validated['slug'] = str_slug('products', 'slug', $validated['name']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail != null) {
                delete_file($product->thumbnail);
            }

            $validated['thumbnail'] = upload_file($request->file('thumbnail'), "$imageFolder/thumb");
            //$validated['thumbnail'] = upload_with_watermark($request->file('thumbnail'), "$imageFolder/thumb");
        }

        if ($request->hasFile('video')) {
            if ($product->video != null) {
                delete_file($product->video);
            }

            $validated['video'] = upload_file($request->file('video'), "videos/{$seller->username}/products");
        }
        $product->update($validated);

        if ($request->hasFile('files')) {
            $product->images->each(function ($image) {
                delete_file($image->image);
                $image->delete();
            });

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => upload_file($file, $imageFolder),
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product Updated Successfully',
            'redirect' => route('seller.products.edit', $product->slug),
        ]);
    }

    public function delete(Product $product)
    {

        if ($product->thumbnail != null) {
            delete_file($product->thumbnail);
        }

        $product->images->each(function ($image) {
            delete_file($image->image);
            $image->delete();
        });

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product Removed Successfully');
    }

    public function deleteImage(ProductImage $image)
    {
        delete_file($image->image);

        $image->delete();

        return successResponse("Product image deleted successfully!");
    }

    public function stockUpdate(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity.*' => 'nullable|numeric|min:0',
            'stock_action.*'   => 'nullable|numeric',
            'stock_note.*'     => 'nullable|string',
        ]);

        $stockQuantities = $request->input('stock_quantity', []);
        $stockActions    = $request->input('stock_action', []);
        $stockNotes      = $request->input('stock_note', []);

        foreach ($stockQuantities as $variantId => $quantity) {
            if (!$quantity) continue;
            $action = $stockActions[$variantId] ?? null;
            $note   = $stockNotes[$variantId] ?? null;

            $variant = ProductVariant::find($variantId);

            if (! $variant) continue;

            $currentStock = ($variant->stock_in ?? 0) - ($variant->stock_out ?? 0);

            if ($action == StockType::REMOVE_STOCK->value && $quantity > $currentStock) {
                continue; 
            }

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
        }

        return redirect()->back()->with('success', 'Stock updated successfully for all variants!');
    }
}
