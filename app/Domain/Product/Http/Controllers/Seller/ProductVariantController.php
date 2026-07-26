<?php

namespace App\Domain\Product\Http\Controllers\Seller;

use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Models\PosCartItem;
use App\Domain\Product\Models\Color;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\Size;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $variants = json_decode($request->variants, true);

        $validator = Validator::make([
            'variants' => $variants,
        ], [
            'variants' => 'required|array|min:1',
            'variants.*.color_id' => 'nullable|integer|exists:colors,id',
            'variants.*.size_id' => 'nullable|integer|exists:sizes,id',
            'variants.*.buying_price' => 'required|numeric|min:0',
            'variants.*.selling_price' => 'required|numeric|min:0|gte:variants.*.buying_price',
            'variants.*.discount_type' => 'nullable|in:flat,percentage',
            'variants.*.discount_value' => 'nullable|numeric|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $imageFolder = "{$product->seller->username}/products";

        $defaultExists = ProductVariant::where('product_id', $product->id)->where('is_default', 1)->exists();

        $colorIds = collect($variants)->pluck('color_id')->filter()->unique()->values();
        $sizeIds = collect($variants)->pluck('size_id')->filter()->unique()->values();
        $colorsById = Color::whereIn('id', $colorIds)->get()->keyBy('id');
        $sizesById = Size::whereIn('id', $sizeIds)->get()->keyBy('id');

        if (! empty($variants) && is_array($variants)) {
            foreach ($variants as $index => $v) {
                if (empty($v['buying_price']) || empty($v['selling_price'])) {
                    continue;
                }

                $colorSlug = ! empty($v['color_id']) ? $colorsById->get($v['color_id'])?->slug : null;
                $sizeSlug = ! empty($v['size_id']) ? $sizesById->get($v['size_id'])?->slug : null;
                $skuParts = array_filter([$product->slug, $colorSlug, $sizeSlug]);
                $sku = strtoupper(Str::slug(implode('-', $skuParts)));

                $variant = new ProductVariant;
                $variant->product_id = $product->id;
                $variant->color_id = $v['color_id'] ?? null;
                $variant->size_id = $v['size_id'] ?? null;
                $variant->sku = $sku;
                $variant->buying_price = $v['buying_price'];
                $variant->selling_price = $v['selling_price'];
                $variant->stock_in = $v['stock'] ?? 0;

                $variant->discount_type = ($v['discount_type'] ?? 'none') !== 'none' ? $v['discount_type'] : null;
                $variant->discount_value = ! empty($v['discount_value']) ? $v['discount_value'] : null;
                $hasDiscount = ! empty($variant->discount_type) && ! empty($variant->discount_value);

                $variant->discount_amount = $hasDiscount
                    ? calculate_discount_amount($v['selling_price'], $variant->discount_type, $variant->discount_value) : null;

                $variant->discounted_price = $hasDiscount
                    ? calculate_discounted_price($v['selling_price'], $variant->discount_type, $variant->discount_value) : null;

                if (! $defaultExists) {
                    $variant->is_default = $index === 0 ? 1 : 0;
                }

                if (isset($v['image']) && $request->hasFile("variants.$index.image")) {
                    $imageService = new ImageOptimizerService;
                    $variant->image = $imageService->uploadAndOptimize($request->file("variants.$index.image"), "$imageFolder");
                }

                $variant->save();
            }
        }

        return successResponse('Variants added successfully');
    }

    public function update(ProductVariant $variant, Request $request)
    {
        $variant->loadMissing('product.seller');

        $request->validate([
            'buying_price' => 'required',
            'selling_price' => 'required',
            'discount_type' => 'nullable',
            'discount_value' => 'nullable',
            'low_stock_quantity' => 'required',

            'is_default' => 'nullable',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:4000',
        ]);

        if ($request->hasFile('image')) {
            $imageFolder = "images/{$variant->product->seller->username}/products";
            $imageService = new ImageOptimizerService;
            $variant->image = $imageService->uploadAndOptimize($request->file('image'), "$imageFolder");
            // $variant->image = upload_file($request->file('image'), $imageFolder);
        }

        if ($request->is_default && ! $variant->is_default) {
            ProductVariant::where('product_id', $variant->product_id)->where('is_default', 1)->update(['is_default' => 0]);
        }

        $hasDiscount = ! empty($request->discount_type) && ! empty($request->discount_value);

        $variant->is_default = $request->is_default ? 1 : 0;
        $variant->low_stock_quantity = $request->low_stock_quantity;
        $variant->buying_price = $request->buying_price;
        $variant->selling_price = $request->selling_price;
        $variant->discount_type = $request->discount_type;
        $variant->discount_value = $request->discount_value;
        $variant->discount_amount = $hasDiscount ? calculate_discount_amount($request->selling_price, $request->discount_type, $request->discount_value) : null;
        $variant->discounted_price = $hasDiscount ? calculate_discounted_price($request->selling_price, $request->discount_type, $request->discount_value) : null;
        $variant->save();

        if (! $request->is_default) {
            $defaultExists = ProductVariant::where('product_id', $variant->product_id)->where('is_default', 1)->exists();
            if (! $defaultExists) {
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
