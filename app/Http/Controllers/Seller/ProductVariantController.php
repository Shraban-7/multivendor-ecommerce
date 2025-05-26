<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // dd($request->all());
        $data = $request->validate([
            'sku'                  => 'nullable|string',
            'stock_in'             => 'required|numeric',
            'additional_price'       => 'required|numeric',
            'product_attribute_id' => 'required|numeric',
            'option_id'            => 'required|numeric',
            'image'                => 'required|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['product_id'] = $product->id;

        if (! $request->sku) {
            $data['sku'] = strtoupper(uniqid());
        }

        $data['image'] = upload_file($request->file('image'), 'images/products/variant');

        // dd($data);

        ProductVariant::create($data);

        return redirect()->back()->with('success', 'Variant Added Successfully');
    }

    public function destroy(ProductVariant $variant)
    {
        // dd($variant);
        $variant->delete();
        return redirect()->back()->with('success', 'Variant Deleted Successfully!');
    }
}
