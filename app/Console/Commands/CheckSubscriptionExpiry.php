<?php

// app/Console/Commands/CheckSubscriptionExpiry.php
namespace App\Console\Commands;

use App\Models\SellerSubscription;
use Illuminate\Console\Command;
use App\Models\VendorSubscription;
use Carbon\Carbon;

class CheckSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:check-expiry';

    protected $description = 'Expire seller subscriptions past end_date';

    public function handle()
    {
        SellerSubscription::active()
            ->whereDate('end_date', '<', Carbon::today())
            ->update(['status' => SellerSubscription::EXPIRED]);

        $this->info('Expired subscriptions updated successfully.');
    }
}
