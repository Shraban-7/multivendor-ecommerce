<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::latest()->get();

        return view('admin.static_pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.static_pages.edit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['slug'] = Str::slug($request->input('title'));

        $i = 1;
        $originalSlug = $validated['slug'];
        while (StaticPage::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $i++;
        }

        StaticPage::create($validated);

        return redirect()->route('admin.staticPages.index')->with('success', 'Static Page created successfully.');
    }

    public function edit(StaticPage $page)
    {
        return view('admin.static_pages.edit', compact('page'));
    }

    public function update(Request $request, StaticPage $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $page->update($validated);

        return redirect()->route('admin.staticPages.index')->with('success', 'Static Page updated successfully.');
    }
}
