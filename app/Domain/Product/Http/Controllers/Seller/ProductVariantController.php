<?php

namespace App\Domain\Product\Http\Controllers\Seller;

use App\Domain\Order\Models\CartItem;
use App\Domain\Product\Models\Color;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\Size;
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
            'variants.*.cost_price' => 'required|numeric|min:0',
            'variants.*.price' => 'required|numeric|min:0|gte:variants.*.cost_price',
            'variants.*.compare_price' => 'nullable|numeric|min:0|lt:variants.*.price',
            'variants.*.barcode' => 'nullable|string|max:100|unique:product_variants,barcode',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $imageFolder = "{$product->seller->username}/products";

        $colorIds = collect($variants)->pluck('color_id')->filter()->unique()->values();
        $sizeIds = collect($variants)->pluck('size_id')->filter()->unique()->values();
        $colorsById = Color::whereIn('id', $colorIds)->get()->keyBy('id');
        $sizesById = Size::whereIn('id', $sizeIds)->get()->keyBy('id');

        if (! empty($variants) && is_array($variants)) {
            foreach ($variants as $index => $v) {
                if (empty($v['cost_price']) || empty($v['price'])) {
                    continue;
                }

                $colorId = $v['color_id'] ?? null;
                $sizeId = $v['size_id'] ?? null;

                $exists = ProductVariant::where('product_id', $product->id)
                    ->where('color_id', $colorId)
                    ->where('size_id', $sizeId)
                    ->exists();

                if ($exists) {
                    return sendValidationError("Duplicate variant: this combination already exists.");
                }

                $colorSlug = ! empty($colorId) ? $colorsById->get($colorId)?->slug : null;
                $sizeSlug = ! empty($sizeId) ? $sizesById->get($sizeId)?->slug : null;
                $skuParts = array_filter([$product->slug, $colorSlug, $sizeSlug]);
                $sku = strtoupper(Str::slug(implode('-', $skuParts)));

                $skuExists = ProductVariant::where('product_id', $product->id)
                    ->where('sku', $sku)
                    ->exists();

                if ($skuExists) {
                    $sku = $sku . '-' . Str::random(4);
                }

                $variant = new ProductVariant;
                $variant->product_id = $product->id;
                $variant->color_id = $colorId;
                $variant->size_id = $sizeId;
                $variant->sku = $sku;
                $variant->barcode = $v['barcode'] ?? null;
                $variant->cost_price = $v['cost_price'];
                $variant->price = $v['price'];
                $variant->compare_price = ! empty($v['compare_price']) ? $v['compare_price'] : null;
                $variant->weight = $v['weight'] ?? null;
                $variant->stock_in = $v['stock'] ?? 0;
                $variant->status = true;

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
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0|gte:cost_price',
            'compare_price' => 'nullable|numeric|min:0|lt:price',
            'low_stock_quantity' => 'required',
            'barcode' => 'nullable|string|max:100|unique:product_variants,barcode,' . $variant->id,
            'weight' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:4000',
        ]);

        if ($request->hasFile('image')) {
            $imageFolder = "images/{$variant->product->seller->username}/products";
            $imageService = new ImageOptimizerService;
            $variant->image = $imageService->uploadAndOptimize($request->file('image'), "$imageFolder");
        }

        $variant->low_stock_quantity = $request->low_stock_quantity;
        $variant->cost_price = $request->cost_price;
        $variant->price = $request->price;
        $variant->compare_price = $request->filled('compare_price') ? $request->compare_price : null;
        // Preserve existing barcode unless the user explicitly submits a new one.
        $variant->barcode = $request->filled('barcode') ? $request->barcode : ($variant->barcode ?: ProductVariant::generateBarcode());
        $variant->weight = $request->filled('weight') ? $request->weight : null;

        if ($request->has('status')) {
            $variant->status = $request->boolean('status');
        }

        $variant->save();

        return redirect()->back()->with('success', 'Variant updated successfully.');
    }

    public function toggleStatus(ProductVariant $variant)
    {
        $variant->status = ! $variant->status;
        $variant->save();

        $state = $variant->status ? 'enabled' : 'disabled';

        return redirect()->back()->with('success', "Variant {$state} successfully.");
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->stock_out > 0) {
            return redirect()->back()->with('warning', 'This variant cannot be deleted because it has existing orders.');
        }

        CartItem::where('product_variant_id', $variant->id)->delete();

        $variant->variantImages()->delete();
        $variant->delete();

        return redirect()->back()->with('success', 'Variant Deleted Successfully!');
    }

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
