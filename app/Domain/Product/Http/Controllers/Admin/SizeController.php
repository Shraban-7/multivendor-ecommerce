<?php

namespace App\Domain\Product\Http\Controllers\Admin;

use App\Domain\Product\Models\Size;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('sort_order')->paginate(20);

        return view('admin.sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 50;

        Size::create($data);

        return redirect()->back()->with('success', 'Size added successfully.');
    }

    public function update(Request $request, Size $size)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $size->update($data);

        return redirect()->back()->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->back()->with('success', 'Size deleted successfully.');
    }
}