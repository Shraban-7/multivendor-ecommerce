<?php

namespace App\Domain\Payment\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobipayController extends Controller
{
    public function webhook(Request $request)
    {
        $file = 'mobipay_connections.json';

        $logs = Storage::exists($file) ? json_decode(Storage::get($file), true) : [];

        $logs[] = [
            'time' => now()->format('d-m-Y h:i A'),
            'data' => $request->all(),
        ];

        Storage::put($file, json_encode($logs, JSON_PRETTY_PRINT));

        $data = [
            [
                'sender' => 'sender',
                'url' => url('/api/mobipay/store'),
                'sim_slot' => 1,
                'template' => 'template',
                'headers' => 'headers',
                'retries_number' => 3,
                'ignore_ssl' => false,
                'chunked_mode' => false,
                'is_sms_enabled' => true,
            ],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Connected',
            'data' => $data,
        ]);
    }

    public function storeSms(Request $request)
    {
        $file = 'mobipay_sms.json';

        $logs = Storage::exists($file) ? json_decode(Storage::get($file), true) : [];

        $logs[] = [
            'time' => now()->format('d-m-Y h:i A'),
            'data' => $request->all(),
        ];

        Storage::put($file, json_encode($logs, JSON_PRETTY_PRINT));

        return response()->json([
            'status' => true,
            'message' => 'SMS received',
        ]);
    }
}
