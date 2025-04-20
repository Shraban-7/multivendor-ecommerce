<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeMidBanner;
use Illuminate\Http\Request;

class HomeMidController extends Controller
{
    public function index()
    {
        $banners = HomeMidBanner::get();

        $usedPositions = HomeMidBanner::whereNotNull('position')->pluck('position')->toArray();
        $allPositions = range(1, 5);
        $availablePositions = array_diff($allPositions, $usedPositions);

        return view('admin.settings.home_mid_banners.index', compact('banners', 'availablePositions', 'usedPositions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'position' => 'required|between:1,5|unique:home_mid_banners,position',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/home_mid_banners');
        }

        $data['image'] = $imagePath;

        HomeMidBanner::create($data);

        return redirect()->route('admin.settings.banner.index')->with('success', 'Hero Mid banner create successfully');
    }

    public function update(Request $request, HomeMidBanner $heroBanner)
    {
        $data = $request->validate([
            'position' => 'required|between:1,5|unique:hero_banners,position,' . $heroBanner->id,
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($heroBanner->image)) {
                delete_file($heroBanner->image);
            }
            $filePath = 'images/home_mid_banners';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $heroBanner->image;
        }

        $heroBanner->update($data);

        return redirect()->route('admin.settings.banner.index')->with('success', 'Home Mid banner updated successfully');
    }
}
