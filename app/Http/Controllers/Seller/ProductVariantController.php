<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku'                => 'nullable|string',
            'buying_price'       => 'required|string',
            'selling_price'      => 'required|string',
            'discount_type'      => 'required|string',
            'discount_value'     => 'required|numeric',
            'low_stock_quantity' => 'required|numeric',
            'image' => 'required|image|max:4000',

            'option_values'      => 'nullable|array|min:1',
            'option_values.*'    => 'nullable|exists:option_values,id',
            'is_default'         => 'nullable|boolean',
        ]);

        $data['product_id'] = $product->id;

        $optionValues = collect($request->option_values)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (! $request->sku) {
            $data['sku'] = strtoupper(uniqid());
        }

        $data['discount_amount']  = calculate_discount_amount($data['selling_price'], $data['discount_type'], $data['discount_value']);
        $data['discounted_price'] = calculate_discounted_price($data['selling_price'], $data['discount_type'], $data['discount_value']);
        $data['image']            = upload_file($request->file('image'), 'images/products/variant');

        $variant = ProductVariant::create($data);
        foreach ($optionValues as $valueId) {
            ProductVariantOption::create([
                'product_variant_id' => $variant->id,
                'option_value_id'    => $valueId,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Variant Added Successfully']);
    }
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $data = $request->validate([
            'buying_price'       => 'required|string',
            'selling_price'      => 'required|string',
            'discount_type'      => 'required|string',
            'discount_value'     => 'required|numeric',
            'low_stock_quantity' => 'required|numeric',
            'image' => 'nullable|image|max:4000',
            'is_default'         => 'nullable|boolean',
        ]);

        $data['product_id'] = $product->id;

        $data['discount_amount']  = calculate_discount_amount($data['selling_price'], $data['discount_type'], $data['discount_value']);
        $data['discounted_price'] = calculate_discounted_price($data['selling_price'], $data['discount_type'], $data['discount_value']);

        if ($request->hasFile('image')) {
            if ($variant->image != null) {
                delete_file($variant->image);
            }

            $data['image'] = upload_file($request->file('image'), 'images/products/variant');
        }

        $data['is_default'] = $request->input('is_default', 0);

        $variant->update($data);

        return redirect()->back()->with('success', 'Variant Updated Successfully');

    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->back()->with('success', 'Variant Deleted Successfully!');
    }
}
