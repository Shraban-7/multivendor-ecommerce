<?php

namespace App\Domain\Product\Http\Controllers\Seller;

use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Models\PosCartItem;
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

                $colorSlug = ! empty($v['color_id']) ? $colorsById->get($v['color_id'])?->slug : null;
                $sizeSlug = ! empty($v['size_id']) ? $sizesById->get($v['size_id'])?->slug : null;
                $skuParts = array_filter([$product->slug, $colorSlug, $sizeSlug]);
                $sku = strtoupper(Str::slug(implode('-', $skuParts)));

                $variant = new ProductVariant;
                $variant->product_id = $product->id;
                $variant->color_id = $v['color_id'] ?? null;
                $variant->size_id = $v['size_id'] ?? null;
                $variant->sku = $sku;
                $variant->cost_price = $v['cost_price'];
                $variant->price = $v['price'];
                $variant->compare_price = ! empty($v['compare_price']) ? $v['compare_price'] : null;
                $variant->stock_in = $v['stock'] ?? 0;

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
        $variant->save();

        return redirect()->back()->with('success', 'Variant updated successfully.');
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->stock_out > 0) {
            return redirect()->back()->with('warning', 'This variant cannot be deleted because it has existing orders.');
        }

        CartItem::where('product_variant_id', $variant->id)->delete();
        PosCartItem::where('product_variant_id', $variant->id)->delete();

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
