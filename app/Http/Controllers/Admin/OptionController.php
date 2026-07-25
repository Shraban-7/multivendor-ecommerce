<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\OptionRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct(
        private readonly OptionRepositoryInterface $optionRepo,
        private readonly CategoryRepositoryInterface $categoryRepo,
    ) {}

    public function index()
    {
        $options = \App\Domain\Product\Models\Option::with('categories', 'option_values')->paginate(15);
        $categories = $this->categoryRepo->getParentCategories();

        return view('admin.options.index', compact('options', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'values' => 'required|array',
            'categories' => 'nullable|array',
        ]);

        $option = $this->optionRepo->store(['name' => $data['name']]);

        foreach ($data['values'] as $value) {
            $this->optionRepo->storeValue([
                'option_id' => $option->id,
                'value' => $value,
            ]);
        }

        foreach ($data['categories'] as $category) {
            \App\Domain\Product\Models\CategoryOption::create([
                'category_id' => $category,
                'option_id' => $option->id,
            ]);
        }

        return redirect()->back()->with('success', 'Option Added Successfully!');
    }

    public function update(Request $request, \App\Domain\Product\Models\Option $option)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'values' => 'required|array',
            'categories' => 'nullable|array',
        ]);

        $this->optionRepo->update($option, ['name' => $data['name']]);

        $existingValues = $option->option_values->pluck('value')->toArray();

        \App\Domain\Product\Models\OptionValue::where('option_id', $option->id)
            ->whereNotIn('value', $data['values'])
            ->delete();

        foreach ($data['values'] as $value) {
            if (! in_array($value, $existingValues)) {
                $this->optionRepo->storeValue([
                    'option_id' => $option->id,
                    'value' => $value,
                ]);
            }
        }

        \App\Domain\Product\Models\CategoryOption::where('option_id', $option->id)->delete();

        $option->categories()->sync($data['categories'] ?? []);

        return redirect()->back()->with('success', 'Option Updated Successfully!');
    }

    public function optionValueUpdate(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $optionValue = \App\Domain\Product\Models\OptionValue::findOrFail($id);
        $optionValue->update(['value' => $request->value]);

        return redirect()->back()->with('success', 'Option value updated successfully');
    }

    public function deleteValue(\App\Domain\Product\Models\OptionValue $value)
    {
        $this->optionRepo->deleteValue($value);

        return redirect()->back()->with('success', 'Option value deleted successfully.');
    }

    public function destroy(\App\Domain\Product\Models\Option $option)
    {
        $optionValueIds = \App\Domain\Product\Models\OptionValue::where('option_id', $option->id)->pluck('id')->toArray();
        if (! empty($optionValueIds)) {
            \App\Domain\Product\Models\ProductVariantOption::whereIn('option_value_id', $optionValueIds)->delete();
        }

        $option->option_values()->delete();
        $option->categories()->detach();

        $this->optionRepo->delete($option);

        return redirect()->back()->with('success', 'Option removed successfully.');
    }
}
