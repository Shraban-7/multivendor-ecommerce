<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'value'                => 'required|string',
            'name'                 => 'nullable|string',
            'product_attribute_id' => 'nullable|exists:product_attributes,id',
        ]);

        $value       = trim($request->value);
        $attributeId = $request->product_attribute_id;

        if (! $attributeId) {
            if (! $request->name) {
                return redirect()->back()->with('error', 'Please provide an attribute name or select an existing one.');
            }

            $productAttribute = ProductAttribute::create([
                'category_id' => $product->category->id,
                'name'        => $request->name,
            ]);

            $attributeId = $productAttribute->id;
        }

        $exists = ProductAttributeOption::where('product_attribute_id', $attributeId)
            ->whereRaw('LOWER(value) = ?', [strtolower($value)])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This attribute value already exists.');
        }

        ProductAttributeOption::create([
            'product_attribute_id' => $attributeId,
            'value'                => $value,
        ]);

        return redirect()->back()->with('success', 'Attribute Added Successfully!');
    }



}
