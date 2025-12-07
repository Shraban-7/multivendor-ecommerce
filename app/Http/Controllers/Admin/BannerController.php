<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|string|max:50',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpg,png,webp,svg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|string|max:50',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpg,png,webp,svg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
