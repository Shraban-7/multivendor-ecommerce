<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_performance_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->date('snapshot_date');
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->unsignedInteger('late_shipped_orders')->default(0);
            $table->unsignedInteger('delivered_orders')->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->decimal('avg_review_rating', 3, 2)->default(0);
            $table->decimal('cancellation_rate', 6, 4)->default(0);
            $table->decimal('late_shipping_rate', 6, 4)->default(0);
            $table->decimal('overall_score', 6, 2)->default(0);
            $table->string('tier', 16)->default('new');
            $table->timestamps();

            $table->unique(['seller_id', 'snapshot_date'], 'spsnap_unique');
            $table->index(['snapshot_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_performance_snapshots');
    }
};
