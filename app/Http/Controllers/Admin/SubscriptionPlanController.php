<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();

        return view('admin.subscription-plans.index', compact('plans'));
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
            'staff_accounts' => 'required|integer|min:0',
        ]);

        SubscriptionPlan::create($data);

        return response()->json(['success' => true, 'message' => 'Plan added successfully']);
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
            'staff_accounts' => 'required|integer|min:0',
        ]);

        $plan->update($data);

        return response()->json(['success' => true, 'message' => 'Plan updated successfully']);
    }
}
