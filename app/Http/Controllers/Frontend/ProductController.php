<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use App\Models\Product;
use App\Enums\DiscountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\ProductVariant;
use App\Models\ProductVariantProductAttributeOption;
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

        $product = $productModel->toDetailsArray();

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

        $variantIds = $product['variants']->pluck('id');

        $variantAttributeOptionIds = ProductVariantProductAttributeOption::whereIn('product_variant_id', $variantIds)->pluck('product_attribute_option_id');

        $productAttributeOptions = ProductAttributeOption::whereIn('id', $variantAttributeOptionIds)->get();

        $productAttributeOptionIds = array_unique($productAttributeOptions->pluck('product_attribute_id')->toArray());

        $productAttributeModel = ProductAttribute::whereIn('id', $productAttributeOptionIds)->with('options')->get();

        $productAttributes = [];

        foreach ($productAttributeModel as $attribute) {
            $productAttributes[] = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'options' => $productAttributeOptions
                    ->where('product_attribute_id', $attribute->id)
                    ->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'value' => $option->value,
                        ];
                    })
                    ->values(),
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

        $variant = ProductVariant::where('product_id', $product->id)
            ->whereHas('attributeOptions', function ($query) use ($selectedOptionIds) {
                $query->whereIn('product_attribute_option_id', $selectedOptionIds);
            }, '=', count($selectedOptionIds))
            ->first();

        $additional_price = ProductVariantProductAttributeOption::whereIn('product_attribute_option_id', $selectedOptionIds)
            ->where('product_variant_id', $variant->id)
            ->sum('additional_price');

        if ($variant) {
            return response()->json([
                'id' => $variant->id,
                'price' => $additional_price+$product->selling_price,
                'discounted_price' => $additional_price + $product->discounted_price,
                'stock' => $variant->stock,
                'sku' => $variant->sku,
                'description' => $variant->description,
            ]);
        } else {
            return response()->json(['message' => 'Variant not found'], 404);
        }
    }
}
