<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\AffiliateClick;
use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AffiliatorController extends Controller
{
    public function dashboard()
    {
        $affiliate_id = Auth::id();
        $clicks = AffiliateClick::where('affiliate_id', $affiliate_id)->count();
        $earnings = AffiliateCommission::where('referer_id', $affiliate_id)->sum('commission_amount');
        $total_orders = Order::where('affiliate_id', $affiliate_id)->count();
        $pending_earnings = AffiliateCommission::where('referer_id', $affiliate_id)->where('status',AffiliateCommission::APPROVED)->sum('commission_amount');
        return view('frontend.affiliator.dashboard', compact('clicks', 'earnings', 'total_orders', 'pending_earnings'));
    }

    public function withdraw(Request $request)
    {
        $affiliate = Auth::user();

        if ($request->isMethod('GET')) {
            $payment_methods = AffiliatePayout::payment_methods();
            $available_balance = $affiliate->balance;
            $withdraw_histories = AffiliatePayout::where('affiliate_id', $affiliate->id)->get();

            return view('frontend.affiliator.withdraw', compact('payment_methods', 'withdraw_histories', 'available_balance'));
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $affiliate->balance,
            'method' => 'required',
        ]);

        if ($affiliate->balance < $data['amount']) {
            return back()->withErrors(['amount' => 'Insufficient balance']);
        }

        $data['affiliate_id'] = $affiliate->id;

        $withdraw = AffiliatePayout::create($data);

        $affiliate->balance -= $withdraw->amount;
        $affiliate->save();

        return redirect()->back()->with('success', 'Withdraw Request Sent Successfully');
    }
}
