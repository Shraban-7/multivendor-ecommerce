<?php

namespace App\Console\Commands;

use App\Domain\Vendor\Models\SellerSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiryReminders extends Command
{
    protected $signature = 'send-subscription-expiry-reminders';

    protected $description = 'Send  subscription expiration reminders via mail';

    public function handle()
    {
        $expiringSubscriptions = SellerSubscription::where('status', 'active')
            ->whereBetween('end_date', [
                now()->addDays(3)->toDateString(),
                now()->addDays(3)->endOfDay()->toDateString(),
            ])
            ->with('seller')
            ->get();

        // foreach ($expiringSubscriptions as $subscription) {

        //     Mail::to($subscription->seller->email)
        //         ->send(new SubscriptionExpiringMail($subscription));
        // }
    }
}
