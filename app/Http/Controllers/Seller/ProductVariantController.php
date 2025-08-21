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
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
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
                'sku' => strtoupper(uniqid()),
                'buying_price' => $data['buying_price'],
                'selling_price' => $data['selling_price'],
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'discount_amount' => calculate_discount_amount($data['selling_price'], $data['discount_type'], $data['discount_value']),
                'discounted_price' => calculate_discounted_price($data['selling_price'], $data['discount_type'], $data['discount_value']),
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
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'low_stock_quantity' => 'required|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        $data['product_id'] = $product->id;

        $data['discount_amount']  = calculate_discount_amount($data['selling_price'], $data['discount_type'], $data['discount_value']);
        $data['discounted_price'] = calculate_discounted_price($data['selling_price'], $data['discount_type'], $data['discount_value']);


        $data['is_default'] = $request->input('is_default', 0);

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
