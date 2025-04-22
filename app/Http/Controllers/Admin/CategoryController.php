<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::category()->with('subcategories')->latest()->paginate(15);

        return view('admin.categories.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cover_title' => 'required|string|max:255',
            'cover_description' => 'required|string',
            'cover_bg_color' => 'required|string',
            'cover_text_color' => 'required|string',
            'cover_button_color' => 'required|string',
            'is_nav' => 'nullable|boolean',
            'is_special' => 'nullable|boolean',
            'is_slider' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['slug'] = str_slug('categories','slug',$data['name']);

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

        return redirect()->route('admin.categories.index')->with('success','Category Added Successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit',compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cover_title' => 'required|string|max:255',
            'cover_description' => 'required|string',
            'cover_bg_color' => 'required|string',
            'cover_text_color' => 'required|string',
            'cover_button_color' => 'required|string',
            'is_nav' => 'nullable|boolean',
            'is_special' => 'nullable|boolean',
            'is_slider' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $data['slug'] = str_slug('categories', 'slug', $data['name']);

        if ($request->hasFile('image')) {
            if ($category->image != null) {
                delete_file($category->image);
            }

            $data['image'] = upload_file($request->file('image'), 'images/categories/base');
        }

        if ($request->hasFile('cover_image')) {
            if ($category->cover_image != null) {
                delete_file($category->cover_image);
            }

            $data['cover_image'] = upload_file($request->file('cover_image'), 'images/categories/cover');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category Updated Successfully');
    }

    public function toggleStatus(Category $category)
    {
        $category->status = !$category->status;
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'Category Status Updated Successfully');
    }
}
