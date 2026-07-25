<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Product\Models\Option;
use App\Domain\Product\Models\OptionValue;
use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'value' => 'required|string',
            'name' => 'nullable|string',
            'option_id' => 'nullable|exists:options,id',
        ]);

        $value = trim($request->value);
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
            'value' => $value,
        ]);

        return redirect()->back()->with('success', 'Option Added Successfully!');
    }
}
