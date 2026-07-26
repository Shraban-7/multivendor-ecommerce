<?php

namespace App\Domain\Product\Http\Controllers\Admin;

use App\Domain\Product\Models\Color;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::paginate(20);

        return view('admin.colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|string|max:7',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = upload_file($request->file('image'), 'images/colors');
        }

        Color::create($data);

        return redirect()->back()->with('success', 'Color added successfully.');
    }

    public function update(Request $request, Color $color)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|string|max:7',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = upload_file($request->file('image'), 'images/colors');
        }

        $color->update($data);

        return redirect()->back()->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        $color->delete();

        return redirect()->back()->with('success', 'Color deleted successfully.');
    }
}