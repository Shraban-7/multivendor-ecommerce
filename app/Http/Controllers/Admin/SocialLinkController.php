<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Support\Models\SocialLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::get();

        return view('admin.settings.social_links.index', compact('socialLinks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon_name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        SocialLink::create($data);

        return redirect()->route('admin.settings.socialLinks.index')->with('success', 'Promo Poster create successfully');
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon_name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        $data['status'] = $request->status;

        $socialLink->update($data);

        return redirect()->route('admin.settings.socialLinks.index')->with('success', 'Promo Poster updated successfully');
    }
}
