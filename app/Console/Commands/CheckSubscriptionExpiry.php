<?php

// app/Console/Commands/CheckSubscriptionExpiry.php

namespace App\Console\Commands;

use App\Domain\Vendor\Models\SellerSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
