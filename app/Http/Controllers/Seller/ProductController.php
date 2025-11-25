<?php

namespace App\Http\Controllers\Seller;

use App\Models\Brand;
use App\Models\Option;
use App\Models\Seller;
use App\Models\Product;
use App\Enums\StockType;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\ProductSeo;
use App\Models\OptionValue;
use App\Models\PosCartItem;
use App\Models\ProductUnit;
use App\Models\ProductImage;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\CategoryOption;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Models\ProductVariantOption;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::category()->with('subcategories')->get();

        $brands = Brand::all();

        $products = Product::with('variants.option_values', 'unit')->where('seller_id', get_seller_id())->latest('id')->paginate(25);

        return view('seller.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::category()->with('subcategories', 'options.option_values')->get();
        $brands = Brand::all();
        $units = ProductUnit::all();
        $categoryAttributes = $this->categorizedAttributes($categories);

        return view('seller.products.create', compact('categories', 'brands', 'units', 'categoryAttributes'));
    }

    private function categorizedAttributes($categories, $category_id = null)
    {
        $data = [];
        foreach ($categories as $cat) {
            if (!is_null($category_id) && $cat->id != $category_id) {
                continue;
            }
            foreach ($cat->options as $option) {
                $data[$cat->id][] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'values' => $option->option_values->select('id', 'value')
                ];
            }
        }

        return $data;
    }

    public function store(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:categories,id',
            'brand' => 'nullable',
            'sku' => 'required',
            'name' => 'required|string|max:255',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'vat_percent' => 'required|numeric',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'payment_type' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'unit_value' => 'required|string',
            'low_stock_quantity' => 'required|numeric',
            'thumbnail' => 'nullable|image|max:4096',
            'variants' => 'nullable|string',
        ]);

        $hasDiscount = !empty($validated['discount_type']) && !empty($validated['discount_value']);

        $validated['discount_amount'] = $hasDiscount ? calculate_discount_amount($validated['selling_price'], $validated['discount_type'], $validated['discount_value']) : null;

        $validated['discounted_price'] = $hasDiscount ? calculate_discounted_price($validated['selling_price'], $validated['discount_type'], $validated['discount_value']) : null;

        $brandId = null;
        if (!empty($validated['brand'])) {
            if (is_numeric($validated['brand'])) {
                $brandId = (int) $validated['brand'];
            } else {
                $brand = Brand::firstOrCreate(
                    ['name' => trim($validated['brand'])],
                    ['slug' => str_slug('brands', 'slug', trim($validated['brand']))]
                );
                $brandId = $brand->id;
            }
        }
        $validated['brand_id'] = $brandId;
        unset($validated['brand']);

        $imageFolder = "images/{$seller->username}/products";
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = upload_file($request->file('thumbnail'), "$imageFolder/thumb");
        }

        $validated['seller_id'] = $seller->id;
        $validated['slug'] = str_slug('products', 'slug', trim($validated['name']));

        $product = Product::create($validated);

        $variants = json_decode($request->variants, true);

        if (!empty($variants) && is_array($variants)) {
            foreach ($variants as $index => $v) {
                if (empty($v['buying_price']) || empty($v['selling_price'])) {
                    continue;
                }

                $variant = new ProductVariant();
                $variant->product_id = $product->id;
                $variant->sku = ProductVariant::generate_sku();
                $variant->buying_price = $v['buying_price'];
                $variant->selling_price = $v['selling_price'];
                $variant->stock_in = $v['stock'] ?? 0;

                $variant->discount_type = ($v['discount_type'] ?? 'none') !== 'none'
                    ? $v['discount_type']
                    : null;

                $variant->discount_value = !empty($v['discount_value'])
                    ? (float) $v['discount_value']
                    : null;

                $hasDiscount = !empty($variant->discount_type) && !empty($variant->discount_value);

                $variant->discount_amount = $hasDiscount
                    ? calculate_discount_amount(
                        $v['selling_price'],
                        $variant->discount_type,
                        $variant->discount_value
                    )
                    : null;

                $variant->discounted_price = $hasDiscount
                    ? calculate_discounted_price(
                        $v['selling_price'],
                        $variant->discount_type,
                        $variant->discount_value
                    )
                    : null;

                $variant->is_default = $index === 0 ? 1 : 0;

                if (isset($v['image']) && $request->hasFile("variants.$index.image")) {
                    $variant->image = upload_file($request->file("variants.$index.image"), "$imageFolder/variants");
                }

                $variant->save();

                if (!empty($v['attributes']) && is_array($v['attributes'])) {
                    foreach ($v['attributes'] as $key => $value) {
                        $key = trim($key);
                        $value = trim($value);
                        if (!$key || !$value)
                            continue;

                        $option = Option::firstOrCreate(['name' => $key]);

                        $optionValue = OptionValue::firstOrCreate([
                            'option_id' => $option->id,
                            'value' => $value,
                        ]);

                        ProductVariantOption::create([
                            'product_variant_id' => $variant->id,
                            'option_value_id' => $optionValue->id,
                        ]);
                    }
                }
            }
        }

        return successResponse('Product Added Successfully');
    }

    public function show(Product $product)
    {
        $product->load('variants.option_values', 'stock_history', 'seo');

        $costPrice = $product->buying_price ?? 0;
        $sellingPrice = $product->selling_price ?? 0;

        $profitAmount = $sellingPrice - $costPrice;
        $profitPercent = $costPrice > 0 ? ($profitAmount / $costPrice) * 100 : 0;
        $productStock = $product->stock_in - $product->stock_out;

        $product->profit_amount = $profitAmount;
        $product->profit_percent = $profitPercent;
        $product->stock = $productStock;

        foreach ($product->variants as $variant) {
            $variant->stock = ($variant->stock_in ?? 0) - ($variant->stock_out ?? 0);
        }

        $optionIds = CategoryOption::where('category_id', $product->category_id)->pluck('option_id')->toArray();
        $product_options = Option::whereIn('id', $optionIds)->with('option_values')->get();
        $categories = Category::where('id', $product->category_id)->with('options.option_values')->get();
        $categoryAttributes = $this->categorizedAttributes($categories, $product->category_id);

        return view('seller.products.details', compact('product', 'product_options', 'categoryAttributes'));
    }

    public function edit($slug)
    {
        $product = Product::where('slug', $slug)
            ->withCount('variants')
            ->withCount('images', 'seo')
            ->first();

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
            'best_selling' => 'nullable',
            'is_featured' => 'nullable',
            'thumbnail' => [
                'nullable',
                'image',
                'max:4096',
                // 'dimensions:ratio=1/1',
            ],
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'mimetypes:image/*',
        ]);

        $useMainPrices = $request->has('useMainPrices');
        $useMainDiscount = $request->has('useMainDiscount');

        $hasDiscount = !empty($product->discount_type) && !empty($product->discount_value);

        $validated['discount_amount'] = $hasDiscount ? calculate_discount_amount($validated['selling_price'], $validated['discount_type'], $validated['discount_value']) : null;

        $validated['discounted_price'] = $hasDiscount ? calculate_discounted_price($validated['selling_price'], $validated['discount_type'], $validated['discount_value']) : null;

        if ($request->best_selling) {
            $validated['best_selling'] = $validated['best_selling'] ? 1 : 0;
        }

        if ($request->is_featured) {
            $validated['is_featured'] = $validated['is_featured'] ? 1 : 0;
        }

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

        if ($useMainPrices) {
            foreach ($product->variants as $variant) {
                $variant->buying_price = $product->buying_price;
                $variant->selling_price = $product->selling_price;
                $variant->save();
            }
        }

        if ($useMainDiscount) {
            foreach ($product->variants as $variant) {
                $variant->discount_type = $product->discount_type;
                $variant->discount_value = $product->discount_value;
                $variant->discount_amount = $product->discount_amount;
                $variant->discounted_price = $product->discounted_price;
                $variant->save();
            }
        }

        if ($request->hasFile('files')) {
            // $product->images->each(function ($image) {
            //     delete_file($image->image);
            //     $image->delete();
            // });

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => upload_file($file, $imageFolder),
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

        CartItem::where('product_id', $product->id)->delete();
        PosCartItem::where('product_id', $product->id)->delete();

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product Removed Successfully');
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        $product = Product::find($request->product_id);
        $imageFolder = $imageFolder = "images/{$product->seller->username}/products";

        foreach ($request->file('images') as $file) {
            ProductImage::create([
                'product_id' => $request->product_id,
                'image' => upload_file($file, $imageFolder)
            ]);
        }

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

        $stockQuantities = $request->input('stock_quantity', []);
        $stockActions = $request->input('stock_action', []);
        $stockNotes = $request->input('stock_note', []);

        if ($request->has('stock_quantity_product') && $request->has('stock_action_product')) {
            $productCurrentStock = ($product->stock_in ?? 0) - ($product->stock_out ?? 0);
            $productAction = $request->stock_action_product;
            $productQuantity = $request->stock_quantity_product;
            $productNote = $request->stock_note_product;

            if ($productAction == StockType::REMOVE_STOCK->value && $productQuantity > $productCurrentStock) {
                return redirect()->back()->with('warning', 'Insufficient stock! You cannot remove more than the available quantity.');
            }

            StockHistory::create([
                'product_id' => $product->id,
                'quantity' => $productQuantity,
                'type' => $productAction,
                'note' => $productNote,
            ]);

            if ($productAction == StockType::SET_EXACT_STOCK->value) {
                $product->stock_in = $productQuantity;
                $product->stock_out = 0;
            } elseif ($productAction == StockType::ADD_STOCK->value) {
                $product->stock_in += $productQuantity;
            } elseif ($productAction == StockType::REMOVE_STOCK->value) {
                $product->stock_in -= $productQuantity;
                if ($product->stock_in < 0)
                    $product->stock_in = 0;
            }

            $product->save();

            return redirect()->back()->with('success', 'Stock updated successfully!');
        }

        foreach ($stockQuantities as $variantId => $quantity) {
            if (!$quantity)
                continue;
            $action = $stockActions[$variantId] ?? null;
            $note = $stockNotes[$variantId] ?? null;

            $variant = ProductVariant::find($variantId);

            if (!$variant)
                continue;

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
                if ($variant->stock_in < 0)
                    $variant->stock_in = 0;
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

        if ($variant) {
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

        $product = Product::where('sku', $request->sku)->first();
        if ($product) {
            $data = [
                'sellerName' => $product->seller->business_name,
                'productName' => $product->name,
                'variantName' => '',
                'sku' => $product->sku,
                'price' => money($product->selling_price),
                'quantity' => $request->quantity,
            ];

            return view('seller.barcodes.print', compact('data'));
        }

        return redirect()->route('seller.products.printBarcode')->with('error', 'Product not found!');
    }

    public function inventory()
    {
        $products = Product::with('variants.option_values')
            ->orderBy('name', 'ASC')
            ->where('seller_id', get_seller_id())
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $product->stock_in - $product->stock_out,
                    'price' => removeZeroFromDecimal($product->selling_price, 'int'),
                    'discounted_price' => removeZeroFromDecimal($product->discounted_price ?? $product->selling_price, 'int'),
                    'image' => is_null($product->thumbnail) ? asset('assets/frontend/images/placeholder-img.jpg') : storage_url($product->thumbnail),
                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'sku' => $variant->sku,
                            'fullName' => $variant->fullName,
                            'quantity' => $variant->stock_in - $variant->stock_out,
                            'price' => removeZeroFromDecimal($variant->selling_price, 'int'),
                            'discounted_price' => removeZeroFromDecimal($variant->discounted_price, 'int'),
                            'image' => is_null($variant->image) ? null : storage_url($variant->image)
                        ];
                    })
                ];
            });

        return view('seller.products.inventory', compact('products'));
    }
}
