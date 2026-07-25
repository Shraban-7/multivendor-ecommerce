<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Vendor\Models\SellerSubscription;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SellerSubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request)
    {
        $query = SellerSubscription::with(['seller', 'plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_id')) {
            $query->where('subscription_plan_id', $request->plan_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('seller', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $subscriptions = $query->latest()->paginate(20);
        $plans = SubscriptionPlan::all();

        return view('admin.subscription.index', compact('subscriptions', 'plans'));
    }

    public function show(SellerSubscription $sellerSubscription)
    {
        $subscription = $sellerSubscription->load(['seller', 'plan.features', 'histories.performedBy', 'payments']);

        return view('admin.seller-subscriptions.show', compact('subscription'));
    }

    public function edit(SellerSubscription $sellerSubscription)
    {
        $subscription = $sellerSubscription->load(['seller', 'plan']);
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('admin.seller-subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, SellerSubscription $sellerSubscription)
    {
        $validated = $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'admin_notes' => 'nullable|string',
        ]);

        $newPlan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);

        // Only update if plan is different
        if ($sellerSubscription->subscription_plan_id != $newPlan->id) {
            $this->subscriptionService->changePlan(
                $sellerSubscription,
                $newPlan,
                auth()->id(),
                $validated['admin_notes'] ?? null
            );

            return redirect()->route('admin.seller-subscriptions.show', $sellerSubscription)
                ->with('success', 'Subscription updated successfully');
        }

        // Just update notes if plan is same
        $sellerSubscription->update([
            'admin_notes' => $validated['admin_notes'],
            'upgraded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.seller-subscriptions.show', $sellerSubscription)
            ->with('success', 'Notes updated successfully');
    }

    public function cancel(Request $request, SellerSubscription $sellerSubscription)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->subscriptionService->cancelSubscription(
            $sellerSubscription,
            $validated['reason'],
            auth()->id()
        );

        return redirect()->route('admin.seller-subscriptions.show', $sellerSubscription)
            ->with('success', 'Subscription cancelled successfully');
    }

    public function renew(SellerSubscription $sellerSubscription)
    {
        $this->subscriptionService->renewSubscription($sellerSubscription);

        return redirect()->route('admin.seller-subscriptions.show', $sellerSubscription)
            ->with('success', 'Subscription renewed successfully');
    }

    public function suspend(Request $request, SellerSubscription $sellerSubscription)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $sellerSubscription->update([
            'status' => SubscriptionStatus::SUSPENDED->value,
            'admin_notes' => $validated['reason'],
        ]);

        return redirect()->route('admin.seller-subscriptions.show', $sellerSubscription)
            ->with('success', 'Subscription suspended successfully');
    }

    public function activate(SellerSubscription $sellerSubscription)
    {
        $sellerSubscription->update([
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);

        return redirect()->route('admin.seller-subscriptions.show', $sellerSubscription)
            ->with('success', 'Subscription activated successfully');
    }
}
