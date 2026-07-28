<?php

namespace App\Domain\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable',
            'brand' => 'nullable',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0|gte:cost_price',
            'compare_price' => 'nullable|numeric|min:0|lt:price',
            'payment_type' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'unit_value' => 'required|string',
            'best_selling' => 'nullable',
            'is_featured' => 'nullable',
            'is_visible' => 'nullable',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'video' => 'nullable|file',
            'files' => 'nullable|array',
            'files.*' => 'image|mimes:jpeg,jpg,png,webp|max:4096',
            'specifications' => 'nullable|string',
            'country_of_origin' => 'nullable|string|max:100',
            'manufacturer_name' => 'nullable|string|max:255',
            'manufacturer_details' => 'nullable|string',
            'tags' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
        ];
    }
}
