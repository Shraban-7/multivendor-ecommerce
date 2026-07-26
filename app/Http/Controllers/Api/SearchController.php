<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('keyword', '');

        $data = [];

        if (strlen($query) < 2) {
            return apiResponse($data);
        }

        $products = Product::where('name', 'like', '%'.$query.'%')
            ->take(5)
            ->get();

        $sellers = Seller::where('business_name', 'like', '%'.$query.'%')
            ->take(5)
            ->get();

        $data['products'] = $this->formatProducts($products);
        $data['sellers'] = $this->formatSellers($sellers);

        return apiResponse($data);
    }

    private function formatProducts($products): array
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'thumbnail' => storage_url($product->thumbnail),
                'price' => is_null($product->price) ? null : money($product->price),
                'compare_price' => is_null($product->compare_price) ? null : money($product->compare_price),
            ];
        }

        return $data;
    }

    private function formatSellers($sellers): array
    {
        $data = [];
        foreach ($sellers as $seller) {
            $data[] = [
                'id' => $seller->id,
                'name' => $seller->business_name,
                'image' => storage_url($seller->business_logo),
            ];
        }

        return $data;
    }
}
