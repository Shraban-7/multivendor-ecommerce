<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\SellerResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? '';
        $subcategory_id = $request->subcategory_id ?? '';
        $name = $request->name ?? '';

        $products = Product::query();

        if ($category_id != '') $products->where('category_id', $category_id);
        if ($subcategory_id != '') $products->where('subcategory_id', $subcategory_id);

        if ($name != '') {
            $products->where(function ($query) use ($name) {
                $query->where('name', $name)
                    ->orWhere('name', 'LIKE', "{$name}%")
                    ->orWhere('name', 'LIKE', "%{$name}%");
            });
        }

        $products = $products->paginate(15)->appends($request->query());

        return apiResourceResponse(ProductListResource::collection($products));
    }

    public function show(Product $product)
    {
        $variants = ProductVariant::with('option.product_attribute')
            ->where('product_id', $product->id)
            ->get();

        $product->formatted_variants = $this->formatVariants($variants);

        $product->load('images', 'category', 'subcategory');

        $data['product'] = ProductListResource::make($product);
        $data['seller'] = SellerResource::make($product->seller);

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(6)
            ->get();

        $data['related_products'] = ProductListResource::collection($relatedProducts);

        return apiResponse($data);
    }

    private function formatVariants($variants)
    {
        $grouped = collect($variants)->map(function ($variant) {
            return [
                'id' => $variant['id'],
                //'sku' => $variant['sku'],
                'value' => $variant['option']['value'] ?? null,
                'additional_price' => $variant['additional_price'],
                'available_stock' => $variant['stock_in'] - $variant['stock_out'],
                'attribute_name' => $variant['option']['product_attribute']['name'] ?? 'Unknown',
            ];
        })->groupBy('attribute_name')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    unset($item['attribute_name']);
                    return $item;
                });
            });

            return $grouped->toArray();
    }
}
