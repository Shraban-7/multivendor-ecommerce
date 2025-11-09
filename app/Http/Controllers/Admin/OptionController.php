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
        $productOptions = Option::get();
        $categories = Category::category()->get();

        return view('admin.options.index', compact('productOptions','categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'value'     => 'required|string',
            'name'      => 'nullable|string',
            'option_id' => 'nullable|exists:options,id',
            'category_id' => 'nullable|array'
        ]);

        // dd($data);

        $value    = trim($data['value']);
        $optionId = $data['option_id'];

        if (! $optionId) {
            if (! $request->name) {
                return redirect()->back()->with('error', 'Please provide an option name or select an existing one.');
            }

            $option = Option::create([
                'name' => $data['name'],
            ]);

            $optionId = $option->id;
        }

        $exists = OptionValue::where('option_id', $optionId)
            ->whereRaw('LOWER(value) = ?', [strtolower($value)])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This option value already exists.');
        }

        OptionValue::create([
            'option_id' => $optionId,
            'value'     => $value,
        ]);

        foreach($data['category_id'] as $category)
        {
            CategoryOption::create([
                'category_id' => $category,
                'option_id' => $optionId
            ]);
        }

        return redirect()->back()->with('success', 'Option Added Successfully!');
    }

    public function optionDelete(OptionValue $value)
    {
        $value->delete();

        return redirect()->back()->with('success', 'Option values removed successfully.');
    }

    public function destroy(Option $option)
    {
        $option->delete();

        return redirect()->back()->with('success', 'Option removed successfully.');
    }
}
