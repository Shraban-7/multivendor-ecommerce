<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'coupon_id')) {
                $table->unsignedBigInteger('coupon_id')->nullable()->after('seller_id');
                $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'coupon_id')) {
                $table->dropForeign(['coupon_id']);
                $table->dropColumn('coupon_id');
            }
        });
    }
};
