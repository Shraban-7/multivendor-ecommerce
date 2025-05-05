<?php

namespace App\Http\Controllers\Seller;

use App\Models\Brand;
use App\Models\Product;
use App\Enums\StockType;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\ProductUnit;
use App\Models\ProductImage;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;
use App\Models\ProductAttributeOption;
use App\Models\ProductVariantProductAttributeOption;

class ProductController extends Controller
{
    public function index()
    {
        $seller_id = seller()->id;

        $products = Product::where('seller_id', $seller_id)->latest('id')->get();
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();

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
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand_id' => 'nullable',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'tax' => 'required|numeric',
            'discount_type' => 'required|string',
            'discount_amount' => 'required|numeric',
            'stock_in' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'is_trending' => 'required|boolean',
            'best_selling' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_interest' => 'required|boolean',
            'is_community' => 'required|boolean',
            'is_lightdeal' => 'required|boolean',
            'lightdeal_expired_at' => 'nullable|date|date_format:Y-m-d',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $validated['thumbnail'] = upload_file($request->file('thumbnail'), 'images/products/thumb');
        if ($request->hasFile('video')) {
            $validated['video'] = upload_file($request->file('video'), 'videos/products');
        }
        $validated['seller_id'] = seller()->id;
        $validated['slug'] = str_slug('products', 'slug', $validated['name']);
        $validated['quantity'] = $validated['stock_in'];
        $validated['sku'] = $validated['sku'] ?? strtoupper(uniqid());

        $product = Product::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => upload_file($file, 'images/products')
                ]);
            }
        }

        session()->flash('success', 'Product added successfully');

        return successResponse("Product added successfully");
    }

    public function details(Product $product)
    {
        $product = $product->toDetailsArray();

        $productAttributes = ProductAttribute::get();

        return view('seller.products.details', compact('product', 'productAttributes'));
    }

    public function edit(Product $product)
    {
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();
        $units = ProductUnit::all();

        return view('seller.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update(Product $product, Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand_id' => 'nullable',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_in' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'is_trending' => 'required|boolean',
            'best_selling' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_interest' => 'required|boolean',
            'is_community' => 'required|boolean',
            'is_lightdeal' => 'required|boolean',
            'lightdeal_expired_at' => 'nullable|date|date_format:Y-m-d',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $validated['slug'] = str_slug('products', 'slug', $validated['name']);
        $validated['quantity'] = $validated['stock_in'];
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
                    'image' => upload_file($file, 'images/products')
                ]);
            }
        }

        session()->flash('success', 'Product Updated successfully!');

        return successResponse("Product Updated successfully!");
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
            'stock_action' => 'required|numeric',
        ]);

        if ($request->stock_quantity > $product->stock_in) {
            session()->flash('error', 'Not enough stock to remove.');
            return errorResponse('Not enough stock to remove.');
        }
        $log = StockHistory::create([
            'product_id' => $product->id,
            'quantity' => $request->stock_quantity,
            'type' => $request->stock_action,
            'note' => $request->stock_note,
        ]);
        $new_stock = 0;
        if ($log->type == StockType::SET_EXACT_STOCK) {
            $new_stock = $request->stock_quantity;
        } elseif ($log->type == StockType::ADD_STOCK) {
            $new_stock = $product->stock_in + $request->stock_quantity;
        } elseif ($log->type == StockType::REMOVE_STOCK) {
            $new_stock = $product->stock_in - $request->stock_quantity;
        }


        $product->update(['stock_in' => $new_stock]);

        session()->flash('success', 'Quantity Updated successfully!');

        return successResponse("Quantity Update successfully!");
    }

    public function addAttributes(Request $request, Product $product)
    {
        $productAttributes = ProductAttribute::with('options')->where('product_id', $product->id)->get();

        if ($request->isMethod('GET')) {
            return view('seller.products.attributes.index', compact('product', 'productAttributes'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array',
            'options.*.value' => 'required|string|max:255',
            'options.*.additional_price' => 'required|numeric',
        ]);

        $existingAttribute = ProductAttribute::where('product_id', $product->id)->where('name', $request->name)->first();
        if ($existingAttribute) {
            session()->flash('warning', 'This Attribute Already Exist');
            return successResponse('This Attribute Already Exist');
        }

        $productAttribute = ProductAttribute::create([
            'product_id' => $product->id,
            'name' => $request->name
        ]);

        foreach ($request->options as $option) {
            $productAttribute->options()->create([
                'product_attribute_id' => $productAttribute->id,
                'name' => $productAttribute->name,
                'value' => $option['value'],
                'additional_price' => $option['additional_price'],
            ]);
        }

        session()->flash('success', 'Attribute Added Successfully!');

        return successResponse('Attribute Added Successfully!');
    }

    public function updateAttributes(Request $request, ProductAttribute $productAttribute)
    {
        if ($request->isMethod('GET')) {
            return view('seller.products.attributes.edit', compact('productAttribute'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array',
            'options.*.value' => 'required|string|max:255',
            'options.*.additional_price' => 'required|numeric',
        ]);

        $productAttribute->update(['name' => $request->name]);

        $existingOptionIds = $productAttribute->options->pluck('id')->toArray();
        $newOptionIds = [];

        foreach ($request->options as $option) {
            if (isset($option['id'])) {
                $productAttributeOption = ProductAttributeOption::find($option['id']);
                $productAttributeOption->update($option);
                $newOptionIds[] = $option['id'];
            } else {
                $newOption = $productAttribute->options()->create($option);
                $newOptionIds[] = $newOption->id;
            }
        }

        $optionsToDelete = array_diff($existingOptionIds, $newOptionIds);
        ProductAttributeOption::destroy($optionsToDelete);

        session()->flash('success', 'Attribute Updated Successfully!');

        return successResponse('Attribute Updated Successfully!');
    }

    public function deleteAttributes(Request $request, ProductAttribute $productAttribute)
    {
        $product_id = $productAttribute->product_id;
        foreach ($productAttribute->options as $option) {
            $option->delete();
        }

        $productAttribute->delete();

        return redirect()->route('seller.products.addAttributes', $product_id)->with('success', 'Product Removed Successfully');
    }

    public function addVariant(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => 'nullable|string',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'description' => 'nullable|string'
        ]);

        $data['product_id'] = $product->id;

        if (!$request->sku) {
            $data['sku'] = strtoupper(uniqid());
        }

        $data['price'] = $product->selling_price + $request->price;

        $variant = ProductVariant::create($data);

        foreach ($data['attributes'] as $attributeName => $attributeValue) {
            $attributeOption = ProductAttributeOption::where('value', $attributeValue)->first();

            if ($attributeOption) {
                ProductVariantProductAttributeOption::create([
                    'product_variant_id' => $variant->id,
                    'product_attribute_option_id' => $attributeOption->id,
                ]);
            }
        }

        session()->flash('success', 'Variant Added Successfully!');

        return successResponse('Variant Added Successfully!');
    }

    public function updateVariant(Request $request, Product $product, ProductVariant $variant)
    {
        $data = $request->validate([
            'sku' => 'nullable|string',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'description' => 'nullable|string'
        ]);

        if (!$request->sku) {
            $data['sku'] = strtoupper(uniqid());
        }

        $data['price'] = $product->selling_price + $request->price;

        $variant->update($data);

        $variant->attributeOptions()->detach();

        foreach ($data['attributes'] as $attributeName => $attributeValue) {
            $attributeOption = ProductAttributeOption::where('value', $attributeValue)->first();

            if ($attributeOption) {
                $variant->attributeOptions()->attach($attributeOption->id);
            }
        }

        return redirect()->route('seller.products.details', $product->id)->with('success', 'Variant Updated Successfully!');
    }

    public function deleteVariant(ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->back()->with('success', 'Variant Deleted Successfully!');
    }
}
