<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_performance_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->string('period', 32); // last_7_days|last_30_days|last_90_days|all_time
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();

            // Source counts
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->unsignedInteger('shipped_orders')->default(0);
            $table->unsignedInteger('late_shipped_orders')->default(0);
            $table->unsignedInteger('delivered_orders')->default(0);
            $table->unsignedInteger('refunded_orders')->default(0);
            $table->unsignedInteger('returned_orders')->default(0);
            $table->unsignedInteger('disputed_returns')->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('chat_count')->default(0);
            $table->unsignedInteger('chat_responded_count')->default(0);

            // Aggregates (raw)
            $table->decimal('avg_review_rating', 3, 2)->default(0);
            $table->decimal('avg_shipping_hours', 10, 2)->nullable();
            $table->decimal('avg_response_hours', 10, 2)->nullable();

            // Rates (0..1)
            $table->decimal('cancellation_rate', 6, 4)->default(0);
            $table->decimal('late_shipping_rate', 6, 4)->default(0);
            $table->decimal('response_rate', 6, 4)->default(0);
            $table->decimal('dispute_rate', 6, 4)->default(0);

            // Sub-scores (0..100) — components for the overall score
            $table->decimal('cancellation_score', 6, 2)->default(0);
            $table->decimal('late_shipping_score', 6, 2)->default(0);
            $table->decimal('rating_score', 6, 2)->default(0);
            $table->decimal('response_score', 6, 2)->default(0);
            $table->decimal('dispute_score', 6, 2)->default(0);

            $table->decimal('overall_score', 6, 2)->default(0);
            $table->string('tier', 16)->default('new');
            $table->json('breakdown')->nullable();
            $table->json('weights')->nullable();
            $table->json('thresholds')->nullable();

            $table->timestamp('computed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['seller_id', 'period'], 'sps_seller_period_unique');
            $table->index(['period', 'overall_score'], 'sps_period_score_idx');
            $table->index(['tier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_performance_scores');
    }
};
