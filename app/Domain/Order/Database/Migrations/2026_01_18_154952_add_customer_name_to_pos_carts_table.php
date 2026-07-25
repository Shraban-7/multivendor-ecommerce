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
        Schema::table('pos_carts', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('order_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->decimal('sub_total', 10, 2)->nullable()->after('customer_phone');
            $table->decimal('discount', 6, 2)->nullable()->after('sub_total');
            $table->decimal('additional_discount', 6, 2)->nullable()->after('discount');
            $table->decimal('total', 10, 2)->nullable()->after('additional_discount');
            $table->decimal('payable', 10, 2)->nullable()->after('total');
            $table->decimal('paid', 10, 2)->nullable()->after('payable');
            $table->decimal('due', 10, 2)->nullable()->after('paid');
            $table->decimal('cash_received', 10, 2)->nullable()->after('due');
            $table->decimal('cash_returned', 10, 2)->nullable()->after('cash_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_carts', function (Blueprint $table) {
            //
        });
    }
};
