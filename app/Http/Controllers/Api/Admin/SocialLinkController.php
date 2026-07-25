<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Support\Models\SocialLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $links = SocialLink::latest()->get();

        return apiResponse($links->map(fn ($l) => [
            'id' => $l->id,
            'name' => $l->name,
            'icon_name' => $l->icon_name,
            'link' => $l->link,
            'status' => (bool) $l->status,
            'created_at' => $l->created_at,
        ]));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'icon_name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $link = SocialLink::create($request->only(['name', 'icon_name', 'link']));

        return apiResponse([
            'id' => $link->id,
            'name' => $link->name,
        ], 'Social link created successfully.');
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'icon_name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $request->only(['name', 'icon_name', 'link', 'status']);
        $socialLink->update($data);

        return successResponse('Social link updated successfully.');
    }
}
