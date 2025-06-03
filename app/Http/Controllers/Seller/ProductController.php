<?php
namespace App\Http\Controllers\Seller;

use App\Enums\StockType;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\ProductImage;
use App\Models\ProductUnit;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $seller_id = seller()->id;

        $products   = Product::where('seller_id', $seller_id)->latest('id')->get();
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
            'category_id'          => 'required|integer|exists:categories,id',
            'subcategory_id'       => 'nullable',
            'brand_id'             => 'nullable',
            'name'                 => 'required|string|max:255',
            'short_description'    => 'nullable|string',
            'description'          => 'nullable|string',
            'sku'                  => 'nullable|string|max:255',
            'buying_price'         => 'required|numeric',
            'selling_price'        => 'required|numeric',
            'tax'                  => 'required|numeric',
            'discount_type'        => 'required|string',
            'discount_amount'      => 'required|numeric',
            'unit_id'              => 'required|numeric',
            'unit_value'           => 'required|string',
            'is_trending'          => 'required|boolean',
            'best_selling'         => 'required|boolean',
            'is_featured'          => 'required|boolean',
            'is_interest'          => 'required|boolean',
            'is_community'         => 'required|boolean',
            'is_lightdeal'         => 'required|boolean',
            'lightdeal_expired_at' => 'nullable|date|date_format:Y-m-d',
            'thumbnail'            => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
            'video'                => 'nullable|file',
            'files'                => 'nullable|array',
            'files.*'              => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $validated['thumbnail'] = upload_file($request->file('thumbnail'), 'images/products/thumb');
        if ($request->hasFile('video')) {
            $validated['video'] = upload_file($request->file('video'), 'videos/products');
        }
        $validated['seller_id'] = seller()->id;
        $validated['slug']      = str_slug('products', 'slug', $validated['name']);
        $validated['sku']       = $validated['sku'] ?? strtoupper(uniqid());

        $product = Product::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => upload_file($file, 'images/products'),
                ]);
            }
        }

        session()->flash('success', 'Product added successfully');

        return successResponse("Product added successfully");
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $product = $product->toDetailsArray();

        $productAttributes   = ProductAttribute::where('category_id', $product['category_id'])->get();
        $productAttributeIds = $productAttributes->pluck('id');

        $productAttributeOptions = ProductAttributeOption::whereIn('product_attribute_id', $productAttributeIds)->get();

        return view('seller.products.details', compact('product', 'productAttributes', 'productAttributeOptions'));
    }

    public function edit(Product $product)
    {
        $categories = Category::category()->with('subcategories')->get();
        $brands     = Brand::all();
        $units      = ProductUnit::all();

        return view('seller.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update(Product $product, Request $request)
    {
        $validated = $request->validate([
            'category_id'          => 'required|integer|exists:categories,id',
            'subcategory_id'       => 'nullable',
            'brand_id'             => 'nullable',
            'name'                 => 'required|string|max:255',
            'short_description'    => 'nullable|string',
            'description'          => 'nullable|string',
            'sku'                  => 'nullable|string|max:255',
            'buying_price'         => 'required|numeric',
            'selling_price'        => 'required|numeric',
            'unit_id'              => 'required|numeric',
            'unit_value'           => 'required|string',
            'is_trending'          => 'required|boolean',
            'best_selling'         => 'required|boolean',
            'is_featured'          => 'required|boolean',
            'is_interest'          => 'required|boolean',
            'is_community'         => 'required|boolean',
            'is_lightdeal'         => 'required|boolean',
            'lightdeal_expired_at' => 'nullable|date|date_format:Y-m-d',
            'thumbnail'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'video'                => 'nullable|file',
            'files'                => 'nullable|array',
            'files.*'              => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $validated['slug'] = str_slug('products', 'slug', $validated['name']);

        $validated['sku'] = $validated['sku'] ?? strtoupper(uniqid());

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail != null) {
                delete_file($product->thumbnail);
            }

            $validated['thumbnail'] = upload_file($request->file('thumbnail'), 'images/products/thumb');
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


        return redirect()->back()->with('success',"Product Updated successfully!");
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
            'stock_quantity' => 'required|numeric',
            'stock_action'   => 'required|numeric',
        ]);

        if (($product->stock_in > 0) && ($request->stock_quantity > $product->stock_in) && ($request->stock_action==StockType::REMOVE_STOCK->value)) {
            return redirect()->back()->with('error', 'Not enough stock to remove.');
        }

        $new_stock = $product->stock_in;

        $log = StockHistory::create([
            'product_id' => $product->id,
            'quantity'   => $request->stock_quantity,
            'type'       => $request->stock_action,
            'note'       => $request->stock_note,
        ]);

        if ($log->type->value == StockType::SET_EXACT_STOCK->value) {
            $new_stock = $request->stock_quantity;
        } elseif ($log->type->value == StockType::ADD_STOCK->value) {
            $new_stock = $product->stock_in + $request->stock_quantity;
        } elseif ($log->type->value == StockType::REMOVE_STOCK->value) {
            $new_stock = $product->stock_in - $request->stock_quantity;
        }

        $product->update(['stock_in' => $new_stock]);

        return redirect()->back()->with('success', "Quantity Update successfully!");
    }

    public function getOptions($attributeId)
    {
        $options = ProductAttributeOption::where('product_attribute_id', $attributeId)->get();

        return response()->json($options);
    }

}
