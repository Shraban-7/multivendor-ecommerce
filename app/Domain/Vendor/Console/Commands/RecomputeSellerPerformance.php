<?php

namespace App\Domain\Vendor\Console\Commands;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Services\SellerPerformanceService;
use Illuminate\Console\Command;

class RecomputeSellerPerformance extends Command
{
    protected $signature = 'seller:performance:recompute
        {--seller= : Specific seller ID to recompute}
        {--chunk=100 : Chunk size for batch processing}';

    protected $description = 'Recompute seller performance scores and tiers.';

    public function handle(SellerPerformanceService $service): int
    {
        $chunk = (int) $this->option('chunk') ?: 100;

        if ($sellerId = $this->option('seller')) {
            $seller = Seller::find($sellerId);
            if (! $seller) {
                $this->error("Seller #{$sellerId} not found.");

                return self::FAILURE;
            }

            $this->info("Recomputing scores for seller #{$sellerId} ({$seller->business_name}).");
            $results = $service->recompute($seller);

            foreach ($results as $period => $score) {
                $this->line(sprintf(
                    '  %s → %s (%.2f / 100) tier=%s',
                    $period,
                    $score->periodEnum()->label(),
                    (float) $score->overall_score,
                    $score->tierLabel(),
                ));
            }

            return self::SUCCESS;
        }

        $count = 0;
        Seller::query()->where('status', Seller::ACTIVE)
            ->chunkById($chunk, function ($sellers) use ($service, &$count) {
                foreach ($sellers as $seller) {
                    $service->recompute($seller);
                    $count++;
                }
            });

        $this->info("Recomputed performance for {$count} sellers.");

        return self::SUCCESS;
    }
}
