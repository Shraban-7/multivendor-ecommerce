<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\Request;

class HeroBannerController extends Controller
{
    public function index()
    {
        $hero_banners = HeroBanner::get();

        $usedPositions = HeroBanner::whereNotNull('position')->pluck('position')->toArray();
        $allPositions = range(1, 5);
        $availablePositions = array_diff($allPositions, $usedPositions);

        return view('admin.settings.hero_banners.index', compact('hero_banners', 'availablePositions', 'usedPositions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'position' => 'required|between:1,5|unique:hero_banners,position',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            // 'button_link' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:6000',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/hero_banners');
        }

        $data['image'] = $imagePath;

        HeroBanner::create($data);

        return redirect()->route('admin.settings.hero.index')->with('success','Hero banner create successfully');
    }

    public function update(Request $request, HeroBanner $heroBanner)
    {
        $data = $request->validate([
            'position' => 'required|between:1,5|unique:hero_banners,position,' . $heroBanner->id,
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6000',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($heroBanner->image)) {
                delete_file($heroBanner->image);
            }
            $filePath = 'images/hero_banners';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $heroBanner->image;
        }

        $heroBanner->update($data);

        return redirect()->route('admin.settings.hero.index')->with('success', 'Hero banner updated successfully');
    }
}
