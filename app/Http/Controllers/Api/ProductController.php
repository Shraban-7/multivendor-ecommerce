<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\SellerResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? '';
        $subcategory_id = $request->subcategory_id ?? '';
        $name = $request->name ?? '';
        $seller_id = $request->seller_id ?? '';

        $products = Product::query();

        if ($category_id != '') {
            $products->where('category_id', $category_id);
        }

        if ($subcategory_id != '') {
            $products->where('subcategory_id', $subcategory_id);
        }

        if ($name != '') {
            $products->where(function ($query) use ($name) {
                $query->where('name', $name)
                    ->orWhere('name', 'LIKE', "{$name}%")
                    ->orWhere('name', 'LIKE', "%{$name}%");
            });
        }

        if ($seller_id != '') {
            $products->where('seller_id', $seller_id);
        }

        if ($request->sort_by === 'popular') {
            $products->orderBy('stock_out', 'desc');
        } elseif ($request->sort_by === 'low-to-high') {
            $products->orderBy('selling_price', 'asc');
        } elseif ($request->sort_by === 'high-to-low') {
            $products->orderBy('selling_price', 'desc');
        } else {
            $products->latest();
        }

        $limit = $request->limit ?? 15;
        if($request->limit > 100) $limit = 100;

        $products = $products->paginate($limit)->appends($request->query());

        return apiResourceResponse(ProductListResource::collection($products));
    }

    public function show(Product $product)
    {
        $product->load('images', 'category', 'subcategory');

        $data['product'] = ProductResource::make($product);
        $data['seller'] = SellerResource::make($product->seller);

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(6)
            ->get();

        $data['related_products'] = ProductListResource::collection($relatedProducts);

        $data['reviews'] = ReviewResource::collection(Review::with(['user', 'images'])->where('product_id', $product->id)->get());

        return apiResponse($data);
    }
}
