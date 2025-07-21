<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Seller;
use App\Services\AamarpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $aamarpay;

    public function __construct(AamarpayService $aamarpay)
    {
        $this->aamarpay = $aamarpay;
    }

    public function pay(Request $request)
    {
        $tran_id = uniqid('SM');

        $request->validate([
            'amount'    => 'required|numeric|min:10',
            'cus_name'  => 'required|string',
            'cus_email' => 'required|email',
            'cus_phone' => 'required|string',
        ]);

        Payment::create([
            'gateway'        => 'aamarpay',
            'user_id'        => Auth::id(),
            'transaction_id' => $tran_id,
            'status'         => Payment::PENDING,
            'amount'         => $request->amount,
            'currency'       => 'BDT',
            'customer_name'  => $request->cus_name,
            'customer_email' => $request->cus_email,
            'customer_phone' => $request->cus_phone,
        ]);

        try {
            $response = $this->aamarpay->initiate([
                'tran_id'      => $tran_id,
                'success_url'  => route('payment.success'),
                'fail_url'     => route('payment.cancel'),
                'cancel_url'   => route('payment.cancel'),
                'amount'       => $request->amount,
                'desc'         => 'Test Payment',
                'cus_name'     => $request->cus_name,
                'cus_email'    => $request->cus_email,
                'cus_add1'     => '',
                'cus_add2'     => '',
                'cus_city'     => '',
                'cus_state'    => '',
                'cus_postcode' => '',
                'cus_country'  => 'Bangladesh',
                'cus_phone'    => $request->cus_phone,
                'opt_a'        => base64_encode(json_encode([
                    'user_id'    => Auth::id(),
                    'return_url' => route('cart.details'),
                ])),
            ]);

            if (isset($response['payment_url'])) {
                return redirect()->away($response['payment_url']);
            }

            return back()->with('error', 'Payment URL not received.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirm(Request $request)
    {
        $transactionId = $request->input('mer_txnid');

        //call verify payment API here

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->update([
                'status'        => Payment::SUCCESSFUL,
                'gateway_trxid' => $request->input('pg_txnid'),
                'response'      => $request->all(),
            ]);

            $this->updateOrder($payment);
        }

        session()->flash('Payment Successful');
        return redirect($request->return_url);

        //return view('payment.success', ['data' => $request->all()]);
    }

    public function cancel(Request $request)
    {
        $transactionId = $request->input('mer_txnid');

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->update([
                'status'   => Payment::FAILED,
                'response' => $request->all(),
            ]);

            $this->updateOrder($payment);
        }

        session()->flash('Payment Failed');
        return redirect($request->return_url);
        //return view('payment.failed', ['data' => $request->all()]);
    }

    public function notify(Request $request)
    {
        \Log::info('AamarPay IPN Received', $request->all());

        $transactionId = $request->mer_txnid;
        $status        = $request->pay_status;

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->update([
                'status'        => $status === Payment::SUCCESSFUL ? $status : Payment::FAILED,
                'gateway_trxid' => $request->pg_txnid,
                'response'      => $request->all(),
            ]);

            $this->updateOrder($payment);
        }

        //update order status in DB
        // $order = Order::where('transaction_id', $transactionId)->first();
        // if ($order && $status === 'Successful') {
        //     $order->status = 'paid';
        //     $order->save();
        // }

        return response('IPN received', 200);
    }

    public function manual(Request $request)
    {
        if ($request->isMethod("GET")) {
            return view('payment.manual');
        }
    }

    private function updateOrder(Payment $payment)
    {
        $order = Order::where('invoice_id', $payment->transaction_id)->first();

        $due = $order->due;

        if ($due > 0 && $payment->status === Payment::SUCCESSFUL) {
            $due = $due - $payment->amount;
            $seller = Seller::find($order->seller_id);

            $balance =$seller->balance + $order->seller_earnings;

            $seller->update([
                'balance' => $balance
            ]);
        }
        $order->update([
            'payment_id' => $payment->id,
            'due'        => $due,
        ]);

    }
}
