<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Support\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $setting = SystemSetting::first();

        return view('admin.settings.setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'required|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_white' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
            'footer_text' => 'required|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',

            'facebook_pixel' => 'nullable|string|max:255',
            'facebook_capi' => 'nullable|string|max:255',
            'google_analytics' => 'nullable|string|max:255',
            'google_tag_manager' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $setting = SystemSetting::first();

        if (! $setting) {
            $setting = new SystemSetting;
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                delete_file($setting->logo);
            }
            $data['logo'] = upload_file($request->file('logo'), 'logo');
        }

        if ($request->hasFile('logo_white')) {
            if ($setting->logo_white) {
                delete_file($setting->logo_white);
            }
            $data['logo_white'] = upload_file($request->file('logo_white'), 'logo');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon && file_exists(public_path($setting->favicon))) {
                delete_file(public_path($setting->favicon));
            }

            $favicon = $request->file('favicon');
            $filename = uniqid().'.'.$favicon->getClientOriginalExtension();
            $path = 'favicon';

            $favicon->move(public_path($path), $filename);

            $data['favicon'] = $path.'/'.$filename;
        }

        $setting->fill($data);
        $setting->save();

        Cache::forget('system_settings');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
