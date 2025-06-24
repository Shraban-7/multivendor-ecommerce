<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

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
            'app_name'   => 'required|string',
            'logo'       => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_white' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $setting = SystemSetting::first();

        if (! $setting) {
            $setting = new SystemSetting();
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

            $favicon  = $request->file('favicon');
            $filename = uniqid() . '.' . $favicon->getClientOriginalExtension();
            $path     = 'favicon';

            $favicon->move(public_path($path), $filename);

            $data['favicon'] = $path . '/' . $filename;
        }

        $setting->fill($data);
        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

}
