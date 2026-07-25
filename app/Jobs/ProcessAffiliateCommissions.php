<?php

namespace App\Jobs;

use App\Domain\Affiliate\Models\AffiliateCommission;
use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class ProcessAffiliateCommissions implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        private readonly string $invoiceId,
        private readonly int $userId,
        private readonly array $orderData,
    ) {}

    public function handle(): void
    {
        $order = Order::with('items.product')->where('invoice_id', $this->invoiceId)->first();

        if (! $order) {
            Log::warning('AffiliateCommissionJob: Order not found', ['invoice' => $this->invoiceId]);

            return;
        }

        $cookieValue = Cookie::get('affiliate_refs');
        $affiliateRefs = json_decode($cookieValue, true) ?: [];

        foreach ($order->items as $item) {
            $productSlug = $item->product->slug ?? null;
            if (! $productSlug || ! isset($affiliateRefs[$productSlug])) {
                continue;
            }

            foreach ($affiliateRefs[$productSlug] as $refCode) {
                $affiliateUser = User::where('affiliate_code', $refCode)->first();
                if (! $affiliateUser) {
                    continue;
                }

                $commissionAmount = ($item->total ?? $item->sub_total) * 0.05;

                AffiliateCommission::create([
                    'affiliate_id' => $affiliateUser->id,
                    'order_id' => $item->order_id,
                    'product_id' => $item->product_id,
                    'order_item_id' => $item->id,
                    'user_id' => $this->userId,
                    'commission_amount' => $commissionAmount,
                    'status' => AffiliateCommission::PENDING,
                ]);
            }
        }
    }
}
