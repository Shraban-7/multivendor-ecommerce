<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerBannerImage;
use App\Domain\Vendor\Models\SellerExpense;
use App\Domain\Vendor\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $seller = Auth::user()->load(['country', 'division', 'district', 'banner_images']);

        return apiResponse([
            'seller' => [
                'id' => $seller->id,
                'name' => $seller->name,
                'email' => $seller->email,
                'phone' => $seller->phone,
                'business_name' => $seller->business_name,
                'business_email' => $seller->business_email,
                'business_address' => $seller->business_address,
                'business_logo' => $seller->business_logo,
                'cover_image' => $seller->cover_image,
                'shipping_cost' => (float) ($seller->shipping_cost ?? 0),
                'division_id' => $seller->division_id,
                'district_id' => $seller->district_id,
                'division' => $seller->division?->name,
                'district' => $seller->district?->name,
                'banner_images' => $seller->banner_images->pluck('image'),
            ],
            'countries' => \App\Domain\Shipping\Models\Country::get(['id', 'name']),
            'divisions' => \App\Domain\Shipping\Models\Division::get(['id', 'name']),
        ]);
    }

    public function update(Request $request)
    {
        $seller = Auth::user();

        $validator = validateRequest($request, [
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_images' => 'nullable|array',
            'banner_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $request->only(['business_name', 'business_email', 'business_address', 'shipping_cost', 'division_id', 'district_id']);

        if ($request->hasFile('business_logo')) {
            if ($seller->business_logo) {
                delete_file($seller->business_logo);
            }
            $data['business_logo'] = upload_file($request->file('business_logo'), 'images/seller/logos');
        }

        if ($request->hasFile('cover_image')) {
            if ($seller->cover_image) {
                delete_file($seller->cover_image);
            }
            $data['cover_image'] = upload_file($request->file('cover_image'), 'images/seller/covers');
        }

        $seller->update($data);

        if ($request->hasFile('banner_images')) {
            foreach ($request->file('banner_images') as $image) {
                $path = upload_file($image, 'images/seller/banners');
                $seller->banner_images()->create(['image' => $path]);
            }
        }

        return successResponse('Settings updated successfully.');
    }

    public function deleteBannerImage(SellerBannerImage $image)
    {
        if ($image->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        delete_file($image->image);
        $image->delete();

        return successResponse('Banner image deleted.');
    }

    public function plans()
    {
        $seller = Auth::user();
        $plans = SubscriptionPlan::orderBy('price')->get();

        return apiResponse([
            'current_plan' => $seller->plan?->load('subscriptions'),
            'plans' => $plans,
        ]);
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $seller = Auth::user();

        $seller->subscriptions()->create([
            'subscription_plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
        ]);

        $seller->update(['plan_id' => $plan->id]);

        return successResponse("Subscribed to {$plan->name} plan successfully.");
    }

    public function notifications()
    {
        $notifications = \App\Domain\Support\Models\Notification::where('seller_id', Auth::id())
            ->latest()
            ->paginate(25);

        return apiResourceResponse($notifications);
    }

    public function customers(Request $request)
    {
        $customers = \App\Domain\Auth\Models\User::whereHas('orders', fn ($q) => $q->where('seller_id', Auth::id()))
            ->withCount(['orders' => fn ($q) => $q->where('seller_id', Auth::id())])
            ->paginate($request->input('limit', 25));

        return apiResourceResponse($customers->through(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'phone' => $c->phone,
            'email' => $c->email,
            'orders_count' => (int) ($c->orders_count ?? 0),
        ]));
    }
}
