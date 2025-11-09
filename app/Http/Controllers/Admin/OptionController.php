<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryOption;

class OptionController extends Controller
{
    public function index()
    {
        $productOptions = Option::with('categories')->paginate(15);
        $categories = Category::category()->get();

        return view('admin.options.index', compact('productOptions', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string',
            'values'     => 'required|array',
            'categories' => 'nullable|array'
        ]);

        $option = Option::create([
            'name' => $data['name'],
        ]);

        foreach ($data['values'] as $value) {
            OptionValue::create([
                'option_id' => $option->id,
                'value'     => $value,
            ]);
        }

        foreach ($data['categories'] as $category) {
            CategoryOption::create([
                'category_id' => $category,
                'option_id' => $option->id
            ]);
        }

        return redirect()->back()->with('success', 'Option Added Successfully!');
    }

    public function update(Request $request, Option $option)
    {
        $data = $request->validate([
            'name'       => 'required|string',
            'values'     => 'required|array',
            'categories' => 'nullable|array',
        ]);

        $option->update([
            'name' => $data['name'],
        ]);

        $existingValues = $option->options->pluck('value')->toArray();

        OptionValue::where('option_id', $option->id)
            ->whereNotIn('value', $data['values'])
            ->delete();

        foreach ($data['values'] as $value) {
            if (!in_array($value, $existingValues)) {
                OptionValue::create([
                    'option_id' => $option->id,
                    'value'     => $value,
                ]);
            }
        }

        CategoryOption::where('option_id', $option->id)->delete();

        $option->categories()->sync($data['categories'] ?? []);

        return redirect()->back()->with('success', 'Option Updated Successfully!');
    }


    public function optionDelete(OptionValue $value)
    {
        $value->delete();

        return redirect()->back()->with('success', 'Option values removed successfully.');
    }

    public function destroy(Option $option)
    {
        $option->options()->delete();

        $option->categories()->detach();

        $option->delete();

        return redirect()->back()->with('success', 'Option removed successfully.');
    }
}
