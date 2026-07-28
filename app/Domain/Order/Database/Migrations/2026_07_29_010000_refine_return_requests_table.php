<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->string('refunded_amount', 12)->nullable()->after('refunded_at');
            $table->string('refund_method', 30)->nullable()->after('refunded_amount');
            $table->string('refund_reference', 120)->nullable()->after('refund_method');
            $table->string('cancellation_reason', 500)->nullable()->after('rejection_reason');
            $table->index(['status', 'created_at'], 'rr_status_created_idx');
            $table->index('is_disputed', 'rr_is_disputed_idx');
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropIndex('rr_status_created_idx');
            $table->dropIndex('rr_is_disputed_idx');
            $table->dropColumn([
                'refunded_amount',
                'refund_method',
                'refund_reference',
                'cancellation_reason',
            ]);
        });
    }
};
