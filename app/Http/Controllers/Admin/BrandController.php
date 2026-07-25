<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Product\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['slug'] = str_slug('brands', 'slug', $data['name']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/brands');
        }

        $data['image'] = $imagePath;

        Brand::create($data);

        return redirect()->back()->with('success', 'Brand Added Successfully');
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['slug'] = str_slug('brands', 'slug', $data['name']);

        if ($request->hasFile('image')) {
            if ($brand->image != null) {
                delete_file($brand->image);
            }

            $data['image'] = upload_file($request->file('image'), 'images/brands');
        }

        $brand->update($data);

        return redirect()->back()->with('success', 'Brand Updated Successfully');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->status = ! $brand->status;
        $brand->save();

        return redirect()->back()->with('success', 'Brand Status Updated Successfully');
    }
}
