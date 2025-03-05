<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $seller_id = seller()->id;

        $products = Product::where('seller_id', $seller_id)->latest('id')->paginate(15);
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();

        return view('seller.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();

        return view('seller.products.create', compact('categories', 'brands'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand_id' => 'nullable',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_in' => 'required|numeric',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
            'files' => 'nullable|array',
            'files.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $validated['thumbnail'] = upload_file($request->file('thumbnail'), 'images/products');
        $validated['seller_id'] = seller()->id ;

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

    public function edit(Product $product)
    {
        $categories = Category::category()->with('subcategories')->get();
        $brands = Brand::all();

        return view('seller.products.edit', compact('product','categories', 'brands'));

    }
    
    public function update(Product $product , Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand_id' => 'nullable',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_in' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'files' => 'nullable|array',
            'files.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        if ($request->hasFile('thumbnail')) {
            if($product->thumbnail != null) {
                delete_file($product->thumbnail);
            }    
            
            $validated['thumbnail'] = upload_file($request->file('thumbnail'), 'images/products');
        }
        $product->update($validated);

        if ($request->hasFile('files')) {
            if($product->images() != null){
                foreach ($product->images() as $image){
                    delete_file($image);
                }

                foreach ($request->file('files') as $file) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => upload_file($file, 'images/products')
                    ]);
                }
            }
            
        }

        if ($request->hasFile('files')){
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

    public function deleteImage(ProductImage $productImage)
    {
         
        delete_file($productImage->image);

        $productImage->delete();

        return successResponse("Product image deleted successfully!");

    }
}
