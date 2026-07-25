<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Support\Models\StaticPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::latest()->get(['id', 'title', 'slug', 'is_active', 'created_at']);

        return apiResponse($pages);
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $i = 1;
        while (StaticPage::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$i++;
        }

        $page = StaticPage::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return apiResponse([
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
        ], 'Static page created successfully.');
    }

    public function edit($slug)
    {
        $page = StaticPage::where('slug', $slug)->firstOrFail(['id', 'title', 'slug', 'content', 'is_active']);

        return apiResponse($page);
    }

    public function update(Request $request, $slug)
    {
        $page = StaticPage::where('slug', $slug)->firstOrFail();

        $validator = validateRequest($request, [
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $page->update($request->only(['title', 'content', 'is_active']));

        return successResponse('Static page updated successfully.');
    }
}
