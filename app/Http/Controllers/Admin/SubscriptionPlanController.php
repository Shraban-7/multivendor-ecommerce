<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Vendor\Models\SellerSubscription;
use App\Domain\Vendor\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();

        return view('admin.subscription.plans', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:monthly,yearly',
            'product_limit' => 'required|integer|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'pos_access' => 'boolean',
            'analytics_access' => 'boolean',
            'priority_support' => 'boolean',
            'custom_domain' => 'boolean',
            'staff_account_limit' => 'required|integer|min:0',
        ]);

        SubscriptionPlan::create($data);

        return successResponse('Plan added successfully');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:monthly,yearly',
            'product_limit' => 'required|integer|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'pos_access' => 'boolean',
            'analytics_access' => 'boolean',
            'priority_support' => 'boolean',
            'custom_domain' => 'boolean',
            'staff_account_limit' => 'required|integer|min:0',
        ]);

        $plan->update($data);

        return successResponse('Plan updated successfully');
    }

    public function delete(SubscriptionPlan $plan)
    {
        if (SellerSubscription::where('subscription_plan_id', $plan->id)->exists()) {
            return errorResponse('This subscription plan is currently assigned to a seller and cannot be deleted.');
        }

        $plan->delete();

        return successResponse('Subscription plan deleted successfully.');
    }
}
