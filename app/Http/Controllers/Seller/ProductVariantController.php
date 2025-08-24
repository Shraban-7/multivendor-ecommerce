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
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'option_values' => 'nullable|array|min:1',
            'option_values.*' => 'nullable|array|min:1',
        ]);

        $data['product_id'] = $product->id;

        $optionValues = $request->option_values ?? [];

        $valuesArrays = array_values($optionValues);

        if (!empty($valuesArrays)) {
            $combinations = $this->cartesianProduct($valuesArrays);
        } else {
            $combinations = [[]];
        }

        $first = true;

        foreach ($combinations as $combination) {
            $variantData = [
                'product_id' => $product->id,
                'sku' => ProductVariant::gernerate_sku(),
                'buying_price' => $data['buying_price'],
                'selling_price' => $data['selling_price'],
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? null,
                'discount_amount' => (isset($data['discount_type'], $data['discount_value']) && $data['discount_type'] && $data['discount_value'])
                    ? calculate_discount_amount($data['selling_price'], $data['discount_type'], $data['discount_value'])
                    : 0,
                'discounted_price' => (isset($data['discount_type'], $data['discount_value']) && $data['discount_type'] && $data['discount_value'])
                    ? calculate_discounted_price($data['selling_price'], $data['discount_type'], $data['discount_value'])
                    : $data['selling_price'],

                'is_default' => $first ? 1 : 0,
            ];

            $variant = ProductVariant::create($variantData);

            foreach ($combination as $optionValueId) {
                ProductVariantOption::create([
                    'product_variant_id' => $variant->id,
                    'option_value_id' => $optionValueId,
                ]);
            }

            $first = false;
        }

        return response()->json(['success' => true, 'message' => 'Variants added successfully']);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $data = $request->validate([
            'buying_price' => 'required|string',
            'selling_price' => 'required|string',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'low_stock_quantity' => 'required|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        $data['product_id'] = $product->id;

        if (!empty($data['discount_type']) && !empty($data['discount_value'])) {
            $data['discount_amount']  = calculate_discount_amount($data['selling_price'], $data['discount_type'], $data['discount_value']);
            $data['discounted_price'] = calculate_discounted_price($data['selling_price'], $data['discount_type'], $data['discount_value']);
        } else {
            $data['discount_amount']  = 0;
            $data['discounted_price'] = $data['selling_price'];
            $data['discount_type']    = null; 
            $data['discount_value']   = null;
        }

        $data['is_default'] = $request->has('is_default') ? 1 : 0;

        $variant->update($data);

        return redirect()->back()->with('success', 'Variant Updated Successfully');
    }


    public function destroy(ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->back()->with('success', 'Variant Deleted Successfully!');
    }

    /**
     * Generate cartesian product of multiple arrays
     */
    private function cartesianProduct($arrays)
    {
        $result = [[]];

        foreach ($arrays as $property => $propertyValues) {
            $tmp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $tmp[] = array_merge($resultItem, [$propertyValue]);
                }
            }
            $result = $tmp;
        }

        return $result;
    }
}
