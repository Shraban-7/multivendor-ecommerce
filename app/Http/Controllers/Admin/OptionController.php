<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\CategoryOption;
use App\Domain\Product\Models\Option;
use App\Domain\Product\Models\OptionValue;
use App\Domain\Product\Models\ProductVariantOption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $options = Option::with('categories', 'option_values')->paginate(15);
        $categories = Category::category()->get();

        return view('admin.options.index', compact('options', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'values' => 'required|array',
            'categories' => 'nullable|array',
        ]);

        $option = Option::create([
            'name' => $data['name'],
        ]);

        foreach ($data['values'] as $value) {
            OptionValue::create([
                'option_id' => $option->id,
                'value' => $value,
            ]);
        }

        foreach ($data['categories'] as $category) {
            CategoryOption::create([
                'category_id' => $category,
                'option_id' => $option->id,
            ]);
        }

        return redirect()->back()->with('success', 'Option Added Successfully!');
    }

    public function update(Request $request, Option $option)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'values' => 'required|array',
            'categories' => 'nullable|array',
        ]);

        $option->update([
            'name' => $data['name'],
        ]);

        $existingValues = $option->option_values->pluck('value')->toArray();

        OptionValue::where('option_id', $option->id)
            ->whereNotIn('value', $data['values'])
            ->delete();

        foreach ($data['values'] as $value) {
            if (! in_array($value, $existingValues)) {
                OptionValue::create([
                    'option_id' => $option->id,
                    'value' => $value,
                ]);
            }
        }

        CategoryOption::where('option_id', $option->id)->delete();

        $option->categories()->sync($data['categories'] ?? []);

        return redirect()->back()->with('success', 'Option Updated Successfully!');
    }

    public function optionValueUpdate(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $optionValue = OptionValue::findOrFail($id);
        $optionValue->update([
            'value' => $request->value,
        ]);

        return redirect()->back()->with('success', 'Option value updated successfully');
    }

    public function deleteValue(OptionValue $value)
    {
        $value->delete();

        return redirect()->back()->with('success', 'Option value deleted successfully.');
    }

    public function destroy(Option $option)
    {
        $optionValueIds = OptionValue::where('option_id', $option->id)->pluck('id')->toArray();
        if (! empty($optionValueIds)) {
            ProductVariantOption::whereIn('option_value_id', $optionValueIds)->delete();
        }

        $option->option_values()->delete();

        $option->categories()->detach();

        $option->delete();

        return redirect()->back()->with('success', 'Option removed successfully.');
    }
}
