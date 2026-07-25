<?php

namespace App\Services;

use App\Models\AffiliateCommission;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class AffiliateService
{
    const AFFILIATE_COMMISSION_PERCENTAGE = 0.05;

    public function processCommissions($orderItems, $user, $invoiceId)
    {
        $cookieValue = Cookie::get('affiliate_refs');
        $affiliateRefs = json_decode($cookieValue, true) ?: [];
        $order = Order::where('invoice_id', $invoiceId)->first();

        foreach ($orderItems as $item) {

            if (! isset($item->product) || ! isset($item->product->slug)) {
                continue;
            }

            $productSlug = $item->product->slug;

            if (isset($affiliateRefs[$productSlug])) {

                foreach ($affiliateRefs[$productSlug] as $refCode) {

                    $affiliateUser = User::where('referral_code', $refCode)->first();

                    if (! $affiliateUser || $affiliateUser->id === $user->id) {
                        continue;
                    }

                    $commissionAmount =
                        $item->unit_price * $item->quantity * self::AFFILIATE_COMMISSION_PERCENTAGE;

                    AffiliateCommission::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'affiliate_id' => $affiliateUser->id,
                        'commission_amount' => $commissionAmount,
                        'commission_date' => now(),
                    ]);
                }
            }
        }

        Cookie::queue(Cookie::forget('affiliate_refs'));
    }

    public function updateOrderAffiliateId(Order $order): void
    {
        $affiliate = AffiliateCommission::where('order_id', $order->id)->first();

        if ($affiliate) {
            $order->affiliate_id = $affiliate->affiliate_id;
            $order->save();
        }
    }

    public function approveCommission(Order $order)
    {
        $affiliate = AffiliateCommission::where('order_id', $order->id)->first();

        if ($affiliate) {
            $affiliate->status = AffiliateCommission::APPROVED;

            $affiliate->save();
        }
    }
}
