<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Product\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::category()->with('subcategories')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'status' => 'nullable',
        ]);

        $data['slug'] = str_slug('categories', 'slug', $data['name']);
        $data['status'] = $request->has('status') ? 1 : 0;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/categories');
        }

        $data['image'] = $imagePath;

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category Added Successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'status' => 'nullable',
            'icon' => 'nullable',
        ]);

        $data['slug'] = str_slug('categories', 'slug', $data['name']);
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($category->image != null) {
                delete_file($category->image);
            }

            $data['image'] = upload_file($request->file('image'), 'images/categories');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category Updated Successfully');
    }

    public function toggleStatus(Category $category)
    {
        $category->status = ! $category->status;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category Status Updated Successfully');
    }
}
