<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Vendor\Models\SellerSubscription;
use App\Domain\Vendor\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();

        $current_subscription = SellerSubscription::active()
            ->where('seller_id', get_seller_id())
            ->with('plan')
            ->first();

        return view('seller.subscription-plans', compact('plans', 'current_subscription'));
    }

    public function subscribe(SubscriptionPlan $plan, Request $request)
    {
        $seller = auth('seller')->user();

        $freePlan = SubscriptionPlan::where('price', 0)->first();
        if (! $freePlan) {
            return redirect()->back()->with('error', 'Free plan not found. Please create one first.');
        }

        $current_plan = SellerSubscription::where('seller_id', $seller->id)->first();
        if ($request->plan_id) {
            $plan = SubscriptionPlan::findOrFail($request->plan_id);
            $start_date = now();
            $end_date = $plan->duration_type === 'monthly'
                ? now()->addMonth()
                : now()->addYear();

            $current_plan->update([
                'seller_id' => $seller->id,
                'subscription_plan_id' => $plan->id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => SellerSubscription::ACTIVE,
            ]);

            return redirect()->back()->with('success', "Subscribed to {$plan->name} successfully!");
        } else {
            $current_plan->update([
                'seller_id' => $seller->id,
                'subscription_plan_id' => $freePlan->id,
                'start_date' => now(),
                'end_date' => null,
                'status' => SellerSubscription::ACTIVE,
            ]);

            return redirect()->back()->with('success', 'Subscription cancelled. Reverted to Free Plan.');
        }
    }
}
