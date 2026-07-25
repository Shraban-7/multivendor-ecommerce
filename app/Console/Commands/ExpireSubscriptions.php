<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Mark expired subscriptions as expired';

    public function handle(SubscriptionService $subscriptionService)
    {
        $this->info('Checking for expired subscriptions...');

        $expiredCount = $subscriptionService->expireSubscriptions();

        $this->info("Expired {$expiredCount} subscription(s).");

        return Command::SUCCESS;
    }
}
