<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoPoster;
use Illuminate\Http\Request;

class PromoPosterController extends Controller
{
    public function index()
    {
        $posters = PromoPoster::get();

        $usedPositions = PromoPoster::whereNotNull('position')->pluck('position')->toArray();
        $allPositions = range(1, 2);
        $availablePositions = array_diff($allPositions, $usedPositions);

        return view('admin.settings.promo_posters.index', compact('posters', 'availablePositions', 'usedPositions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'position' => 'required|between:1,2|unique:promo_posters,position',
            'title' => 'nullable|string|max:255',
            // 'link' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/home_mid_banners');
        }

        $data['image'] = $imagePath;

        PromoPoster::create($data);

        return redirect()->route('admin.settings.posters.index')->with('success', 'Promo Poster create successfully');
    }

    public function update(Request $request, PromoPoster $poster)
    {
        $data = $request->validate([
            'position' => 'required|between:1,2|unique:promo_posters,position,' . $poster->id,
            'title' => 'nullable|string|max:255',
            // 'link' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($poster->image)) {
                delete_file($poster->image);
            }
            $filePath = 'images/home_mid_banners';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $poster->image;
        }

        $poster->update($data);

        return redirect()->route('admin.settings.posters.index')->with('success', 'Promo Poster updated successfully');
    }
}
