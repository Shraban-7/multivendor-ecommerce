<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\PosCartItem;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\ProductVariantOption;
use Illuminate\Support\Facades\Validator;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $variants = json_decode($request->variants, true);

        $validator = Validator::make([
            'variants' => $variants
        ], [
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|max:255|unique:product_variants,sku',
            'variants.*.attributes' => 'required|array',
            'variants.*.attributes.Size' => 'required|string',
            'variants.*.attributes.Color' => 'required|string',
            'variants.*.buying_price' => 'required|numeric|min:0',
            'variants.*.selling_price' => 'required|numeric|min:0|gte:variants.*.buying_price',
            'variants.*.discount_type' => 'nullable|in:flat,percentage',
            'variants.*.discount_value' => 'nullable|numeric|min:0',
            'variants.*.image' => 'nullable|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product = Product::find($request->product_id);

        $imageFolder = "images/{$product->seller->username}/products";

        $defaultExists = ProductVariant::where('product_id', $product->id)->where('is_default', 1)->exists();

        if (!empty($variants) && is_array($variants)) {
            foreach ($variants as $index => $v) {
                if (empty($v['buying_price']) || empty($v['selling_price'])) {
                    continue;
                }

                $variant = new ProductVariant();
                $variant->product_id = $product->id;
                $variant->sku = ProductVariant::generate_sku();
                $variant->buying_price = (float) $v['buying_price'];
                $variant->selling_price = (float) $v['selling_price'];
                $variant->stock_in = $v['stock'] ?? 0;

                $variant->discount_type = ($v['discount_type'] ?? 'none') !== 'none' ? $v['discount_type'] : null;
                $variant->discount_value = !empty($v['discount_value']) ? (float) $v['discount_value'] : null;
                $hasDiscount = !empty($variant->discount_type) && !empty($variant->discount_value);

                $variant->discount_amount = $hasDiscount
                    ? calculate_discount_amount((float) $v['selling_price'], $variant->discount_type, (float) $variant->discount_value) : 0.0;

                $variant->discounted_price = $hasDiscount
                    ? calculate_discounted_price((float) $v['selling_price'], $variant->discount_type, (float) $variant->discount_value) : (float) $v['selling_price'];

                if (!$defaultExists) {
                    $variant->is_default = $index === 0 ? 1 : 0;
                }

                if (isset($v['image']) && $request->hasFile("variants.$index.image")) {
                    $variant->image = upload_file($request->file("variants.$index.image"), "$imageFolder/variants");
                }

                $variant->save();

                if (!empty($v['attributes']) && is_array($v['attributes'])) {
                    foreach ($v['attributes'] as $key => $value) {
                        $key = trim($key);
                        $value = trim($value);
                        if (!$key || !$value) continue;

                        $option = Option::firstOrCreate(['name' => $key]);

                        $optionValue = OptionValue::firstOrCreate([
                            'option_id' => $option->id,
                            'value' => $value,
                        ]);

                        ProductVariantOption::create([
                            'product_variant_id' => $variant->id,
                            'option_value_id' => $optionValue->id,
                        ]);
                    }
                }
            }
        }

        return successResponse("Variants added successfully");
    }

    public function update(ProductVariant $variant, Request $request)
    {
        $request->validate([
            'buying_price' => 'required',
            'selling_price' => 'required',
            'discount_type' => 'nullable',
            'discount_value' => 'nullable',
            'low_stock_quantity' => 'required',
            'is_default' => 'nullable',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:4000'
        ]);

        if ($request->hasFile('image')) {
            $imageFolder = "images/{$variant->product->seller->username}/products";
            $variant->image = upload_file($request->file('image'), $imageFolder);
        }

        if ($request->is_default && !$variant->is_default) {
            ProductVariant::where('product_id', $variant->product_id)->where('is_default', 1)->update(['is_default' => 0]);
        }

        $variant->is_default = $request->is_default ? 1 : 0;
        $variant->low_stock_quantity = $request->low_stock_quantity;
        $variant->buying_price = $request->buying_price;
        $variant->selling_price = $request->selling_price;
        $variant->discount_type = $request->discount_type;
        $variant->discount_value = $request->discount_value;
        $variant->save();

        if (!$request->is_default) {
            $defaultExists = ProductVariant::where('product_id', $variant->product_id)->where('is_default', 1)->exists();
            if (!$defaultExists) {
                ProductVariant::where('product_id', $variant->product_id)->first()->update(['is_default' => 1]);
            }
        }

        return redirect()->back()->with('success', 'Variant updated successfully.');
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->stock_out > 0) {
            return redirect()->back()->with('warning', 'This variant cannot be deleted because it has existing orders.');
        }

        $product_id = $variant->product_id;

        CartItem::where('product_variant_id', $variant->id)->delete();
        PosCartItem::where('product_variant_id', $variant->id)->delete();

        $variant->delete();

        $default = ProductVariant::where('product_id', $product_id)->first();
        $variantCount = ProductVariant::where('product_id', $product_id)->count();

        if ($variantCount == 0) {
            return redirect()->back()->with('success', 'No variants remain for this product!');
        }

        $default->is_default = 1;
        $default->save();

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
