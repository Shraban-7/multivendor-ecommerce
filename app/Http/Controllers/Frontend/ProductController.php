<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use App\Models\Product;
use App\Enums\DiscountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\Seller;

class ProductController extends Controller
{
    public function details($slug, Request $request)
    {
        $limit = 8;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $productModel = Product::where('slug', $slug)
            ->with([
                'category.subcategories',
                'subcategory',
                'images',
                'seller',
                'reviews',
                'variants.attributeOptions',
            ])->firstOrFail();

        $categoryId = $productModel->category->id;
        $sellerId = $productModel->seller->id;

        $discount_price = $productModel->selling_price;
        if ($productModel->discount_type === DiscountType::FLAT) {
            $discount_price -= $productModel->discount_amount;
        } elseif ($productModel->discount_type === DiscountType::PERCENTAGE) {
            $discount_price -= ($productModel->selling_price * $productModel->discount_amount) / 100;
        }

        $product = [
            'id' => $productModel->id,
            'slug' => $productModel->slug,
            'name' => $productModel->name,
            'description' => $productModel->description,
            'price' => money(number_format($productModel->selling_price, 2)),
            'discount_price' => money(number_format($discount_price, 2)),
            'discount' => money(number_format(($productModel->discount_amount), 2)),
            'discount_percent' => money(number_format((($productModel->discount_amount / $productModel->selling_price) * 100), 2)),
            'sold_out' => number_shorten_format($productModel->stock_out),
            'stock_in' => $productModel->stock_in,
            'rating' => number_format($productModel->reviews->avg('rating'), 1),
            'total_reviews' => $productModel->reviews->count(),
            'category' => $productModel->category->name,
            'subcategory' => $productModel->subcategory?->name,
            'images' => $productModel->images->pluck('image'),
            'variants' => $productModel->variants->map(function ($variant) {
                return [
                    'sku' => $variant->sku,
                    'stock' => $variant->stock,
                    'price' => money(number_format($variant->price, 2)),
                    'attributes' => $variant->attributeOptions->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'value' => $option->value ?? null,
                        ];
                    }),
                ];
            }),
        ];

        $interest_products = Product::where('category_id', $categoryId)
            ->where('id', '!=', $product['id'])
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();

        $reviewStats = Review::where('product_id', $product['id'])
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $totalReviews = array_sum($reviewStats);
        $averageRating = $product['rating'];

        $ratings = collect(range(1, 5))->mapWithKeys(function ($star) use ($reviewStats) {
            return [$star => $reviewStats[$star] ?? 0];
        });

         $sellerModel = Seller::where('id', $sellerId)->with('followers')->first();

        $seller = [
            'username' => $sellerModel->username,
            'shop_name' => $sellerModel->business_name,
            'shop_logo' => $sellerModel->business_logo,
            'total_followers' => number_shorten_format($sellerModel->followers->count()),
            'total_sell' => number_shorten_format(Product::where('seller_id', $sellerId)->sum('stock_out')),
            'rating' => round($averageRating),
            'total_products' => Product::where('seller_id', $sellerId)->count(),
        ];

        $variantModel = ProductVariant::where('product_id', $product['id'])
            ->with('attributeOptions.productAttribute')
            ->get();

        $attributeIds = collect();

        foreach ($variantModel as $variant) {
            foreach ($variant->attributeOptions as $option) {
                if ($option->productAttribute) {
                    $attributeIds->push($option->productAttribute->id);
                }
            }
        }

        $uniqueAttributeIds = $attributeIds->unique()->values();

        $productAttributeModel = ProductAttribute::whereIn('id', $uniqueAttributeIds)
            ->with('options')
            ->get();

        $productAttributes = [];

        foreach ($productAttributeModel as $attribute) {
            $productAttributes[] = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'options' => $attribute->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'value' => $option->value,
                    ];
                }),
            ];
        }

        if ($request->ajax()) {
            if ($interest_products->isEmpty()) {
                return '';
            }

            return view('frontend.partials.product-card-load', [
                'products' => $interest_products
            ])->render();
        }

        return view('frontend.products.details', [
            'product' => $product,
            'products' => $interest_products,
            'interest_products' => $interest_products,
            'ratings' => $ratings,
            'totalReviews' => $totalReviews,
            'averageRating' => round($averageRating, 1),
            'seller' => $seller,
            'productAttributes' => $productAttributes
        ]);
    }

    public function getVariant(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $selectedOptionIds = $request->input('option_ids', []);

        if (empty($selectedOptionIds)) {
            return response()->json(['message' => 'No options selected'], 400);
        }

        $variant = ProductVariant::where('product_id', $product->id) // ✅ check inside this product only
            ->whereHas('attributeOptions', function ($query) use ($selectedOptionIds) {
                $query->whereIn('product_attribute_option_id', $selectedOptionIds);
            }, '=', count($selectedOptionIds))
            ->first();

        if ($variant) {
            return response()->json([
                'price' => $variant->price,
                'stock' => $variant->stock,
                'sku' => $variant->sku,
                'description' => $variant->description,
            ]);
        } else {
            return response()->json(['message' => 'Variant not found'], 404);
        }
    }
}
