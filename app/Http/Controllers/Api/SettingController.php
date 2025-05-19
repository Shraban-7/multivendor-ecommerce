<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $validator = validateRequest($request, [
            'apk_version' => 'required|string',
        ]);

        if($validator->fails()) {
            return sendValidationError($validator->errors());
        }
        
        $settings = SystemSetting::first();

        $data['logo'] = asset($settings->logo);
        $data['apk_version'] = $settings->apk_version;
        $data['apk_link'] = $settings->apk_link;
        $data['currency'] = array(
            'name' => 'BDT',
            'symbol' => '৳',
        );

        return apiResponse($data);
    }
}
