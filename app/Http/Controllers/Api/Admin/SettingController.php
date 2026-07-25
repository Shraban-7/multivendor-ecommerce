<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Support\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $setting = SystemSetting::first();

        return apiResponse($setting ? [
            'app_name' => $setting->app_name,
            'logo' => $setting->logo,
            'logo_white' => $setting->logo_white,
            'favicon' => $setting->favicon,
            'footer_text' => $setting->footer_text,
            'email' => $setting->email,
            'phone' => $setting->phone,
            'address' => $setting->address,
            'facebook_pixel' => $setting->facebook_pixel,
            'google_analytics' => $setting->google_analytics,
            'seo_title' => $setting->seo_title,
            'seo_description' => $setting->seo_description,
            'seo_keywords' => $setting->seo_keywords,
        ] : []);
    }

    public function update(Request $request)
    {
        $validator = validateRequest($request, [
            'app_name' => 'required|string|max:255',
            'footer_text' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'facebook_pixel' => 'nullable|string|max:255',
            'google_analytics' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $setting = SystemSetting::first() ?? new SystemSetting;
        $data = $request->only(['app_name', 'footer_text', 'email', 'phone', 'address', 'facebook_pixel', 'google_analytics', 'seo_title', 'seo_description', 'seo_keywords']);

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                delete_file($setting->logo);
            }
            $data['logo'] = upload_file($request->file('logo'), 'logo');
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $filename = uniqid().'.'.$favicon->getClientOriginalExtension();
            $favicon->move(public_path('favicon'), $filename);
            $data['favicon'] = 'favicon/'.$filename;
        }

        $setting->fill($data);
        $setting->save();

        Cache::forget('system_settings');

        return successResponse('Settings updated successfully.');
    }
}
