<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PaymentListenerDevice;
use App\Models\PaymentListenerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentListnerController extends Controller
{
    public function devices()
    {
        $seller_id = get_seller_id();

        $devices = PaymentListenerDevice::where('seller_id', $seller_id)->get();

        $payments = PaymentListenerPayment::where('seller_id', $seller_id)->latest('id')->limit(50)->get();

        return view('seller.payment-listener.devices', compact('devices', 'payments'));
    }

    public function generateCode()
    {
        $seller_id = get_seller_id();

        $device = PaymentListenerDevice::where('seller_id', $seller_id)->whereNull('device_name')->first();
        if (!$device) {
            $device = PaymentListenerDevice::create([
                'seller_id' => get_seller_id(),
                'device_code' => strtoupper(Str::random(8)),
                'status' => PaymentListenerDevice::STATUS_PENDING,
            ]);
        }

        return response()->json([
            'code' => $device->device_code,
            'expires_at' => now()->addMinutes(15),
        ]);
    }

    public function deleteDevice(PaymentListenerDevice $device)
    {
        $device->delete();

        return redirect()->back()->with('success', 'Device removed successfully.');
    }

    public function payments()
    {
        $payments = PaymentListenerPayment::where('seller_id', get_seller_id())->latest('id')->paginate(30);

        return view('seller.payment-listener.payments', compact('payments'));
    }

    public function connectDevice(Request $request)
    {
        $validator = validateRequest($request, [
            'device_code' => 'required|string',
            'device_name' => 'required|string',
            //'device_details' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $device = PaymentListenerDevice::where('device_code', $request->device_code)->first();
        if (!$device) {
            return errorResponse("Invalid device code!");
        }

        if (!is_null($device->device_name) && $device->status == PaymentListenerDevice::STATUS_ACTIVE) {
            return errorResponse("This device code is already connected!");
        }

        $device->update([
            'device_name' => $request->device_name,
            //'device_details' => json_encode($request->device_details),
            'status' => PaymentListenerDevice::STATUS_ACTIVE,
            'last_sync_at' => now(),
        ]);

        $data['allowed_senders'] = ['NAGAD', 'bKash', 'ROCKET', 'Upay'];

        $data['user'] = [
            'id' => $device->seller->id,
            'name' => $device->seller->name,
        ];

        return apiResponse($data, "Device connected successfully");
    }

    public function triggerSms(Request $request)
    {
        $validator = validateRequest($request, [
            'device_code' => 'required|string',
            'sender' => 'required|string',
            'sender_number' => 'nullable|string',
            // 'amount' => 'nullable|numeric',
            // 'trx_id' => 'nullable|string',
            'full_sms' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $device = PaymentListenerDevice::where('device_code', $request->device_code)->first();
        if (!$device) {
            return errorResponse("Invalid device code!", 403);
        }

        PaymentListenerPayment::create([
            'seller_id' => $device->seller_id,
            'device_id' => $device->id,
            'sender' => $request->sender,
            'sender_number' => $request->sender_number,
            'amount' => $request->amount,
            'trx_id' => $request->trx_id,
            'status' => 'pending',
            'received_at' => now(),
            'full_sms' => $request->full_sms,
        ]);

        $device->update(['last_sync_at' => now()]);

        return successResponse('Payment received successfully');
    }

    public function disconnectDevice(Request $request)
    {
        $validator = validateRequest($request, [
            'device_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $device = PaymentListenerDevice::where('device_code', $request->device_code)->first();
        if (!$device) {
            return errorResponse("Invalid device code!");
        }

        $device->status = PaymentListenerDevice::STATUS_INACTIVE;
        $device->save();

        return successResponse('Disconnected successfully');
    }
}
