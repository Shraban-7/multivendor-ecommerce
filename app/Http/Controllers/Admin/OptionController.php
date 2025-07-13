<?php
namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OptionController extends Controller
{
    public function index()
    {
        $productOptions = Option::get();
        return view('admin.options.index', compact('productOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value'     => 'required|string',
            'name'      => 'nullable|string',
            'option_id' => 'nullable|exists:options,id',
        ]);

        $value    = trim($request->value);
        $optionId = $request->option_id;

        if (! $optionId) {
            if (! $request->name) {
                return redirect()->back()->with('error', 'Please provide an option name or select an existing one.');
            }

            $option = Option::create([
                'name' => $request->name,
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
