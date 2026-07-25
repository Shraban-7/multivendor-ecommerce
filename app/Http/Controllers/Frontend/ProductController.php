<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Affiliate\Models\AffiliateClick;
use App\Domain\Auth\Models\User;
use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\OptionValue;
use App\Domain\Product\Models\Product;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::withDefaultRelations();

        $selectedCategories = $request->category ? explode(',', $request->category) : [];
        $selectedBrands = $request->brand ? explode(',', $request->brand) : [];
        $sortFilter = $request->sort ?? 'newest';
        $priceMin = $request->price_min ?? 0;
        $priceMax = $request->price_max ?? 50000;

        if (! empty($selectedCategories)) {
            $query->whereHas('category', function ($q) use ($selectedCategories) {
                $q->whereIn('slug', $selectedCategories);
            });
        }

        if (! empty($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        if ($request->has('price_min') || $request->has('price_max')) {
            $query->whereBetween('selling_price', [$priceMin, $priceMax]);
        }

        $subcategorySlug = $request->query('subcategory');

        if (! empty($subcategorySlug)) {

            $subcategory = Category::where('slug', $subcategorySlug)->first();

            if ($subcategory) {
                $query->where('subcategory_id', $subcategory->id);
            }
        }

        $productOptionFilters = $request->except(['category', 'brand', 'sort', 'price_min', 'price_max', 'subcategory']);

        foreach ($productOptionFilters as $optionKey => $values) {
            $valuesArray = is_array($values) ? $values : explode(',', $values);

            $query->whereHas('variants.option_values', function ($q) use ($valuesArray) {
                $q->whereIn('option_values.id', $valuesArray);
            });
        }

        switch ($sortFilter) {

            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $query->orderBy('stock_out', 'desc');
                break;

            case 'low_high':
                $query->orderBy('selling_price', 'asc');
                break;

            case 'high_low':
                $query->orderBy('selling_price', 'desc');
                break;

            default:
                $query->orderBy('stock_out', 'desc');
                break;
        }

        $products = $query->simplePaginate(16)->appends($request->query());

        $categories = Category::category()
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        $optionValues = OptionValue::whereHas('variants')->with('option')->get();
        $productOptions = [];
        foreach ($optionValues as $optionValue) {
            $productOptions[$optionValue->option->name][] = [
                'id' => $optionValue->id,
                'value' => $optionValue->value,
            ];
        }

        $brands = Brand::withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        if ($request->ajax()) {
            $html = view('components.frontend.products-page', compact(
                'products',
                'categories',
                'brands',
                'productOptions',
                'selectedCategories',
                'selectedBrands',
                'sortFilter',
                'productOptionFilters'
            ))->render();

            return response()->json([
                'html' => $html,
            ]);
        }

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'brands',
            'productOptions',
            'selectedCategories',
            'selectedBrands',
            'sortFilter',
            'productOptionFilters'
        ));
    }

    public function details($slug, Request $request)
    {
        $limit = 10;
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;

        $productModel = Product::where('slug', $slug)->withDefaultRelations()->firstOrFail();

        $categoryId = $productModel->category->id;
        $sellerId = $productModel->seller->id;

        $product = $productModel->toDetailsArray();

        if ($request->has('ref')) {
            $refCode = $request->query('ref');

            $cookieValue = Cookie::get('affiliate_refs');
            $affiliateRefs = json_decode($cookieValue, true) ?: [];

            $affiliateUser = User::where('referral_code', $refCode)->first();

            $affiliateRefs[$slug][] = $refCode;
            $affiliateRefs[$slug] = array_unique($affiliateRefs[$slug]);

            Cookie::queue('affiliate_refs', json_encode($affiliateRefs), 60 * 24 * 7);

            AffiliateClick::create([
                'affiliate_id' => $affiliateUser->id,
                'product_id' => $product['id'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'clicked_at' => now(),
            ]);
        }

        $interest_products = Product::withDefaultRelations()->where('category_id', $categoryId)
            ->active()
            ->where('id', '!=', $product['id'])
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();

        $products = $interest_products;

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

        // $sellerModel = Seller::where('id', $sellerId)->with('followers')->first();

        // $seller = [
        //     'username'        => $sellerModel->username,
        //     'business_name'       => $sellerModel->business_name,
        //     'business_logo'       => $sellerModel->business_logo,
        //     'total_followers' => number_shorten_format($sellerModel->followers->count()),
        //     'rating'          => round($averageRating),
        //     'total_products'  => Product::active()->where('seller_id', $sellerId)->count(),
        // ];

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
            'product' => $product,
            'products' => $products,
            'ratings' => $ratings,
            'totalReviews' => $totalReviews,
            'averageRating' => round($averageRating, 1),
            // 'seller' => $seller,
            'seo' => $productModel->seo,
        ]);
    }

    public function quickView(Product $product)
    {
        $product = $product->toDetailsArray();

        return view('components.frontend.product-contents', compact('product'))->render();
    }

    public function loadReview(Request $request)
    {
        if ($request->ajax()) {
            $productId = $request->product_id;
            $page = $request->page ?? 1;

            $reviews = Review::where('product_id', $productId)
                ->latest()
                ->paginate(5, ['*'], 'page', $page);

            if ($reviews->isEmpty()) {
                return response()->json(['html' => '']);
            }

            $view = view('frontend.partials.review-card', compact('reviews'))->render();

            return response()->json([
                'html' => $view,
                'next_page' => $reviews->currentPage() + 1,
                'has_more' => $reviews->hasMorePages(),
            ]);
        }
    }
}
