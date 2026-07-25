<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->change();
            $table->string('payment_status')->nullable()->after('payment_id');
            $table->string('payment_method_name')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_method_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'payment_status', 'payment_method_name', 'paid_at']);
        });
    }
};
