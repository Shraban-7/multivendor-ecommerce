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
        $seller_id = seller()->id;

        $products   = Product::where('seller_id', $seller_id)->latest('id')->paginate(50);
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
        $validated = $request->validate([
            'category_id'        => 'required|integer|exists:categories,id',
            'subcategory_id'     => 'nullable',
            'brand_id'           => 'nullable',
            'name'               => 'required|string|max:255',
            'short_description'  => 'nullable|string',
            'description'        => 'nullable|string',
            'sku'                => 'nullable|string|max:255',
            'buying_price'       => 'required|numeric',
            'selling_price'      => 'required|numeric',
            'tax'                => 'required|numeric',
            'discount_type'      => 'required|string',
            'discount_value'     => 'required|numeric',
            'unit_id'            => 'required|numeric',
            'unit_value'         => 'required|string',
            'is_trending'        => 'required|boolean',
            'best_selling'       => 'required|boolean',
            'is_featured'        => 'required|boolean',
            'is_interest'        => 'required|boolean',
            'is_community'       => 'required|boolean',
            'low_stock_quantity' => 'required|numeric',
            'thumbnail'          => 'required|image|max:4096',
            'video'              => 'nullable|file',
            'files'              => 'nullable|array',
            'files.*'            => 'file|max:4096|mimetypes:image/*',
            'meta_title'         => 'nullable|string',
        ]);

        $validated['thumbnail'] = upload_with_watermark($request->file('thumbnail'), 'images/products/thumb');

        if ($request->hasFile('video')) {
            $validated['video'] = upload_file($request->file('video'), 'videos/products');
        }
        $validated['seller_id']        = seller()->id;
        $validated['slug']             = str_slug('products', 'slug', $validated['name']);
        $validated['sku']              = $validated['sku'] ?? strtoupper(uniqid());
        $validated['discount_amount']  = calculate_discount_amount($validated['selling_price'], $validated['discount_type'], $validated['discount_value']);
        $validated['discounted_price'] = calculate_discounted_price($validated['selling_price'], $validated['discount_type'], $validated['discount_value']);

        $product = Product::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => upload_file($file, 'images/products'),
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

        $costPrice    = $product->buying_price ?? 0;
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
        $brands     = Brand::all();
        $units      = ProductUnit::all();

        return view('seller.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();

        $validated = $request->validate([
            'category_id'        => 'required|integer|exists:categories,id',
            'subcategory_id'     => 'nullable',
            'brand_id'           => 'nullable',
            'name'               => 'required|string|max:255',
            'short_description'  => 'nullable|string',
            'description'        => 'nullable|string',
            'sku'                => 'nullable|string|max:255',
            'buying_price'       => 'required|numeric',
            'selling_price'      => 'required|numeric',
            'unit_id'            => 'required|numeric',
            'unit_value'         => 'required|string',
            'is_trending'        => 'required|boolean',
            'best_selling'       => 'required|boolean',
            'is_featured'        => 'required|boolean',
            'is_interest'        => 'required|boolean',
            'is_community'       => 'required|boolean',
            'low_stock_quantity' => 'required|numeric',
            'thumbnail'          => 'nullable|image|max:4096',
            'video'              => 'nullable|file',
            'files'              => 'nullable|array',
            'files.*'            => 'mimetypes:image/*',
        ]);

        if ($validated['name'] && $validated['name'] !== $product->name) {
            $validated['slug'] = str_slug('products', 'slug', $validated['name']);
        }

        $validated['sku'] = $validated['sku'] ?? strtoupper(uniqid());

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail != null) {
                delete_file($product->thumbnail);
            }

            $validated['thumbnail'] = upload_with_watermark($request->file('thumbnail'), 'images/products/thumb');
        }

        if ($request->hasFile('video')) {
            if ($product->video != null) {
                delete_file($product->video);
            }

            $validated['video'] = upload_file($request->file('video'), 'videos/products');
        }
        $product->update($validated);

        if ($request->hasFile('files')) {
            $product->images->each(function ($image) {
                delete_file($image->image);
                $image->delete();
            });

            foreach ($request->file('files') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => upload_file($file, 'images/products'),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product Updated Successfully',
            'redirect' => route('seller.products.edit', $product->slug)
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
            'stock_quantity'     => 'required|numeric|min:0',
            'stock_action'       => 'required|numeric',
            'stock_note'         => 'nullable|string',
            'product_variant_id' => 'nullable|numeric',
        ]);

        $stockQuantity = $request->stock_quantity;
        $stockAction   = $request->stock_action;
        $variantId     = $request->product_variant_id;

        $newStock = 0;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);

            if (! $variant) {
                return redirect()->back()->with('error', 'Invalid product variant.');
            }

            $currentStock = ($variant->stock_in ?? 0) - ($variant->stock_out ?? 0);

            if ($stockAction == StockType::REMOVE_STOCK->value && $stockQuantity > $currentStock) {
                return redirect()->back()->with('error', 'Not enough variant stock to remove.');
            }

            $log = StockHistory::create([
                'product_id'         => $product->id,
                'product_variant_id' => $variant->id,
                'quantity'           => $stockQuantity,
                'type'               => $stockAction,
                'note'               => $request->stock_note,
            ]);

            if ($log->type->value == StockType::SET_EXACT_STOCK->value) {
                $newStock           = $stockQuantity;
                $variant->stock_in  = $newStock;
                $variant->stock_out = 0;
            } elseif ($log->type->value == StockType::ADD_STOCK->value) {
                $variant->stock_in += $stockQuantity;
            } elseif ($log->type->value == StockType::REMOVE_STOCK->value) {
                $variant->stock_in -= $stockQuantity;
                if ($variant->stock_in < 0) {
                    $variant->stock_in = 0;
                }
            }

            $variant->save();
        } else {
            $currentStock = ($product->stock_in ?? 0) - ($product->stock_out ?? 0);

            if ($stockAction == StockType::REMOVE_STOCK->value && $stockQuantity > $currentStock) {
                return redirect()->back()->with('error', 'Not enough product stock to remove.');
            }

            $log = StockHistory::create([
                'product_id' => $product->id,
                'quantity'   => $stockQuantity,
                'type'       => $stockAction,
                'note'       => $request->stock_note,
            ]);

            if ($log->type->value == StockType::SET_EXACT_STOCK->value) {
                $newStock           = $stockQuantity;
                $product->stock_in  = $newStock;
                $product->stock_out = 0;
            } elseif ($log->type->value == StockType::ADD_STOCK->value) {
                $product->stock_in += $stockQuantity;
            } elseif ($log->type->value == StockType::REMOVE_STOCK->value) {
                $product->stock_in -= $stockQuantity;
                if ($product->stock_in < 0) {
                    $product->stock_in = 0;
                }
            }

            $product->save();
        }

        return redirect()->back()->with('success', 'Quantity updated successfully!');
    }
}
