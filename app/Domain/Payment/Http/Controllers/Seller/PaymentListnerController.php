<?php

namespace App\Domain\Payment\Http\Controllers\Seller;

use App\Domain\Payment\Models\PaymentListenerDevice;
use App\Domain\Payment\Models\PaymentListenerPayment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentListnerController extends Controller
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepo,
    ) {}

    public function index()
    {
        $seller_id = get_seller_id();

        $devices = $this->paymentRepo->getListenerDevicesBySeller($seller_id);

        $payments = $this->paymentRepo->getListenerPaymentsBySeller($seller_id);

        return view('seller.payment-listener.index', compact('devices', 'payments'));
    }

    public function generateCode()
    {
        $seller_id = get_seller_id();

        $device = $this->paymentRepo->getListenerDevicesBySeller($seller_id)
            ->whereNull('device_name')
            ->first();

        if (! $device) {
            $device = PaymentListenerDevice::create([
                'seller_id' => $seller_id,
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
            'fcm_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $device = $this->paymentRepo->findListenerDeviceByCode($request->device_code);
        if (! $device) {
            return errorResponse('Invalid device code!');
        }

        $device->load('seller');

        if (! is_null($device->device_name) && $device->device_name != $request->device_name) {
            return errorResponse("Use this code for {$device->device_name}");
        }

        $device->update([
            'device_name' => $request->device_name,
            'fcm_token' => $request->fcm_token,
            'status' => PaymentListenerDevice::STATUS_ACTIVE,
        ]);

        $data['allowed_senders'] = PaymentListenerPayment::allowed_senders();
        $data['user'] = [
            'id' => $device->seller->id,
            'name' => $device->seller->name,
            'last_sync_at' => $device->last_sync_at ? $device->last_sync_at->format('Y/m/d h:iA') : 'Never',
        ];

        return apiResponse($data, 'Device connected successfully');
    }

    public function triggerSms(Request $request)
    {
        $validator = validateRequest($request, [
            'device_code' => 'required|string',
            'sender' => 'required|string',
            'sender_number' => 'nullable|string',
            'full_sms' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
            'trx_id' => 'nullable|string',
            'received_at' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $device = $this->paymentRepo->findListenerDeviceByCode($request->device_code);
        if (! $device) {
            return errorResponse('Invalid device code!', 403);
        }

        $submittedAmount = (float) ($request->amount ?? 0);
        $smsAmount = $this->extractAmountFromSms($request->full_sms);

        if ($submittedAmount > 0 && $smsAmount > 0 && abs($submittedAmount - $smsAmount) > 0.01) {
            Log::warning('SMS amount mismatch', [
                'device_code' => $request->device_code,
                'submitted' => $submittedAmount,
                'extracted' => $smsAmount,
            ]);
        }

        $finalAmount = $submittedAmount > 0 ? $submittedAmount : $smsAmount;

        $this->paymentRepo->createListenerPayment([
            'seller_id' => $device->seller_id,
            'device_id' => $device->id,
            'sender' => $request->sender,
            'sender_number' => $request->sender_number,
            'amount' => $finalAmount,
            'trx_id' => $request->trx_id,
            'status' => 'pending',
            'received_at' => $request->received_at,
            'full_sms' => $request->full_sms,
        ]);

        $device->update(['last_sync_at' => now()]);

        return successResponse('Payment received successfully');
    }

    private function extractAmountFromSms(string $sms): float
    {
        if (preg_match('/(?:Tk\.?\s*|BDT\s*|TK\s*|taka\s*)?(\d{1,6}(?:,\d{3})*(?:\.\d{1,2})?)\s*(?:Tk\.?|TK|BDT|taka)?/i', $sms, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }

        return 0.0;
    }

    public function checkDevice(Request $request)
    {
        $validator = validateRequest($request, [
            'device_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return errorResponse(sendValidationError($validator->errors()));
        }

        $device = $this->paymentRepo->findListenerDeviceByCode($request->device_code);
        if (! $device) {
            return errorResponse('Invalid device code!', 403);
        }

        $device->load('seller');

        $data['allowed_senders'] = PaymentListenerPayment::allowed_senders();
        $data['user'] = [
            'id' => $device->seller->id,
            'name' => $device->seller->name,
            'last_sync_at' => $device->last_sync_at ? $device->last_sync_at->format('Y/m/d h:iA') : 'Never',
        ];

        return apiResponse($data);
    }

    public function disconnectDevice(Request $request)
    {
        $validator = validateRequest($request, [
            'device_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $device = $this->paymentRepo->findListenerDeviceByCode($request->device_code);
        if (! $device) {
            return errorResponse('Invalid device code!');
        }

        $device->status = PaymentListenerDevice::STATUS_INACTIVE;
        $device->save();

        return successResponse('Disconnected successfully');
    }

    public function checkPayments(PaymentListenerDevice $device)
    {
        (new FcmService)->notifyPaymentListener($device->fcm_token, 'Payment Listener', 'Checking new payment messages');

        return redirect()->back()->with('success', 'Checked successfully');
    }
}
