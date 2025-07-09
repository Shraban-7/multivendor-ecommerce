<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function details($slug, Request $request)
    {
        $limit = 8;
        $page  = $request->get('page', 1);
        $skip  = ($page - 1) * $limit;

        $productModel = Product::where('slug', $slug)
            ->with([
                'category.subcategories',
                'subcategory',
                'images',
                'seller',
                'reviews',
                'variants.option_values.option',
            ])->firstOrFail();

        $categoryId = $productModel->category->id;
        $sellerId   = $productModel->seller->id;

        $product = $productModel->toDetailsArray();

        $interest_products = Product::where('category_id', $categoryId)
            ->where('id', '!=', $product['id'])
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();

        $products = $interest_products->map(fn($product) => $product->toDetailsArray());

        $reviewStats = Review::where('product_id', $product['id'])
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $totalReviews  = array_sum($reviewStats);
        $averageRating = $product['rating'];

        $ratings = collect(range(1, 5))->mapWithKeys(function ($star) use ($reviewStats) {
            return [$star => $reviewStats[$star] ?? 0];
        });

        $sellerModel = Seller::where('id', $sellerId)->with('followers')->first();

        $seller = [
            'username'        => $sellerModel->username,
            'shop_name'       => $sellerModel->business_name,
            'shop_logo'       => $sellerModel->business_logo,
            'total_followers' => number_shorten_format($sellerModel->followers->count()),
            // 'total_sell'      => number_shorten_format(Product::where('seller_id', $sellerId)->sum('stock_out')),
            'rating'          => round($averageRating),
            'total_products'  => Product::where('seller_id', $sellerId)->count(),
        ];

        if ($request->ajax()) {
            $type = $request->get('type');

            if ($type === 'products') {
                if ($products->isEmpty()) {
                    return '';
                }

                return view('frontend.partials.product-card-load', [
                    'products' => $products,
                ])->render();
            }

            if ($type === 'reviews') {
                $reviews = Review::with('user', 'images')->where('product_id', $product['id'])
                    ->latest()
                    ->skip($request->offset ?? 0)
                    ->take(2)
                    ->get();

                if ($reviews->isEmpty()) {
                    return '';
                }

                return view('frontend.partials.review-card', [
                    'reviews' => $reviews,
                ])->render();
            }
        }

        return view('frontend.products.details', [
            'product'       => $product,
            'products'      => $products,
            'ratings'       => $ratings,
            'totalReviews'  => $totalReviews,
            'averageRating' => round($averageRating, 1),
            'seller'        => $seller
        ]);
    }

    // public function details($slug, Request $request)
    // {
    //     $product = Product::with([
    //         'variants.optionValues.option',
    //     ])->where('slug', $slug)->firstOrFail();

    //     $options = $product->variants
    //         ->flatMap(fn($variant) => $variant->optionValues)
    //         ->groupBy(fn($val) => $val->option->id)
    //         ->map(function ($group) {
    //             $option = $group->first()->option;
    //             return [
    //                 'id' => $option->id,
    //                 'name' => $option->name,
    //                 'values' => $group->unique('id')->map(fn($v) => [
    //                     'id' => $v->id,
    //                     'value' => $v->value,
    //                 ])->values()->toArray(),
    //             ];
    //         })
    //         ->values()
    //         ->toArray();

    //     $variants = $product->variants->map(fn($variant) => [
    //         'id' => $variant->id,
    //         'sku' => $variant->sku,
    //         'price' => $variant->selling_price,
    //         'stock' => max(0, $variant->stock_in - $variant->stock_out),
    //         'value_ids' => $variant->optionValues->pluck('id')->sort()->values()->toArray(),
    //     ])->toArray();

    //     $productArray = [
    //         'name' => $product->name,
    //         'options' => $options,
    //         'variants' => $variants,
    //     ];

    //     // return $productArray;

    //     $defaultVariant = collect($productArray['variants'])->firstWhere('stock', '>', 0);

    //     return view('product-variant',[
    //         'product'=> $productArray,
    //         'defaultVariant' => $defaultVariant
    //     ]);

    // }

    public function loadReview(Request $request)
    {
        if ($request->ajax()) {
            $productId = $request->product_id;
            $page      = $request->page ?? 1;

            $reviews = Review::where('product_id', $productId)
                ->latest()
                ->paginate(5, ['*'], 'page', $page);

            if ($reviews->isEmpty()) {
                return response()->json(['html' => '']);
            }

            $view = view('frontend.partials.review-card', compact('reviews'))->render();

            return response()->json([
                'html'      => $view,
                'next_page' => $reviews->currentPage() + 1,
                'has_more'  => $reviews->hasMorePages(),
            ]);
        }
    }
}
