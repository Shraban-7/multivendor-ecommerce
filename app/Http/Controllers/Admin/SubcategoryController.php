<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Product\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Category::subcategory()->with('subcategories')->latest()->paginate(15);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::category()->with('subcategories')->latest()->get();

        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'cover_title' => 'required|string|max:255',
            'cover_description' => 'required|string',
            'cover_bg_color' => 'required|string',
            'cover_text_color' => 'required|string',
            'cover_button_color' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['slug'] = str_slug('categories', 'slug', $data['name']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/categories/base');
        }

        $data['image'] = $imagePath;

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = upload_file($request->file('cover_image'), 'images/categories/cover');
        }

        $data['cover_image'] = $coverPath;

        Category::create($data);

        return redirect()->route('admin.subcategories.index')->with('success', 'Category Added Successfully');
    }

    public function edit(Category $subcategory)
    {
        $categories = Category::category()->with('subcategories')->latest()->get();

        return view('admin.subcategories.edit', compact('categories', 'subcategory'));
    }

    public function update(Request $request, Category $subcategory)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'cover_title' => 'required|string|max:255',
            'cover_description' => 'required|string',
            'cover_bg_color' => 'required|string',
            'cover_text_color' => 'required|string',
            'cover_button_color' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['slug'] = str_slug('categories', 'slug', $data['name']);

        if ($request->hasFile('image')) {
            if ($subcategory->image != null) {
                delete_file($subcategory->image);
            }

            $data['image'] = upload_file($request->file('image'), 'images/categories/base');
        }

        if ($request->hasFile('cover_image')) {
            if ($subcategory->cover_image != null) {
                delete_file($subcategory->cover_image);
            }

            $data['cover_image'] = upload_file($request->file('cover_image'), 'images/categories/cover');
        }

        $subcategory->update($data);

        return redirect()->route('admin.subcategories.index')->with('success', 'Category Updated Successfully');
    }

    public function toggleStatus(Category $subcategory)
    {
        $subcategory->status = ! $subcategory->status;
        $subcategory->save();

        return redirect()->route('admin.subcategories.index')->with('success', 'Category Status Updated Successfully');
    }
}
