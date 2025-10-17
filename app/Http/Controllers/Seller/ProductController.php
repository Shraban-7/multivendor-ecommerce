<?php

namespace App\Http\Controllers\Seller;

use App\Enums\StockType;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSeo;
use App\Models\ProductUnit;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::category()->with('subcategories')->get();

        $brands = Brand::all();

        $products = Product::with('variants.option_values', 'unit')->where('seller_id', get_seller_id())->latest('id')->get();

        return view('seller.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();
        $units = ProductUnit::all();

        return view('seller.products.create', compact('categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $seller = Seller::find(get_seller_id());

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

        //$validated['thumbnail'] = upload_with_watermark($request->file('thumbnail'), "$imageFolder/thumb");

        $validated['thumbnail'] = upload_file($request->file('thumbnail'), "$imageFolder/thumb");

        if ($request->hasFile('video')) {
            $validated['video'] = upload_file($request->file('video'), "videos/{$seller->username}/products");
        }

        $validated['seller_id'] = $seller->id;
        $validated['slug'] = str_slug('products', 'slug', $validated['name']);


        $product = Product::create($validated);

        $variantData = [
            'product_id' => $product->id,
            'sku' => $validated['sku'] ?? ProductVariant::generate_sku(),
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

        ProductVariant::create($variantData);

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

    public function show(Product $product)
    {
        $product->load('variants.option_values', 'stock_history', 'seo');

        $seo = $product->seo;

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

        return view('seller.products.details', compact('product', 'product_options', 'seo'));
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
        $product = Product::where('slug', $slug)->first();

        $seller = $product->seller;

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
            // $product->images->each(function ($image) {
            //     delete_file($image->image);
            //     $image->delete();
            // });

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

    public function updateSeo(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $seller = $product->seller;

        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                // 'dimensions:min_width=1200,min_height=630'
            ]
        ]);

        $imageFolder = "images/{$seller->username}/products";

        if ($request->hasFile('og_image')) {
            if ($product->seo && $product->seo->og_image) {
                delete_file($product->seo->og_image);
            }
            $validated['og_image'] = upload_file($request->file('og_image'), "$imageFolder/og_image");
        }

        if ($product->seo) {
            $product->seo->update($validated);
        } else {
            $validated['product_id'] = $product->id;
            ProductSeo::create($validated);
        }

        return successResponse("Product SEO Updated Successfully");
    }

    public function stockHistory()
    {
        $seller = Seller::find(get_seller_id());

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
            ->with('variants.option_values')
            ->get();

        $seller = Seller::find(get_seller_id());

        return view('seller.barcodes.index', compact('products', 'seller'));
    }

    public function printBarcodeLabels(Request $request)
    {
        $request->validate([
            'sku' => 'required',
            'quantity' => 'required|numeric'
        ]);

        $variant = ProductVariant::with('product.seller')->where('sku', $request->sku)->first();

        if (!$variant) {
            return redirect()->route('seller.products.printBarcode')->with('error', 'Product not found!');
        }

        //$price = is_null($variant->discounted_price) ? $variant->selling_price : $variant->discounted_price;
        $price = $variant->selling_price;

        $data = [
            'sellerName' => $variant->product->seller->business_name,
            'productName' => $variant->product->name,
            'variantName' => $variant->fullName,
            'sku' => $variant->sku,
            'price' => money($price),
            'quantity' => $request->quantity,
        ];

        return view('seller.barcodes.print', compact('data'));
    }

    public function inventory()
    {
        $products = Product::whereHas('variants')
            ->with('variants.option_values')
            ->orderBy('name', 'ASC')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => is_null($product->thumbnail) ? asset('assets/frontend/images/placeholder-img.jpg') : storage_url($product->thumbnail),
                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'sku' => $variant->sku,
                            'fullName' => $variant->fullName,
                            'quantity' => $variant->stock_in = $variant->stock_out,
                            'price' => removeZeroFromDecimal($variant->selling_price),
                            'image' => is_null($variant->image) ? null : storage_url($variant->image)
                        ];
                    })
                ];
            });

        return view('seller.products.inventory', compact('products'));
    }
}
