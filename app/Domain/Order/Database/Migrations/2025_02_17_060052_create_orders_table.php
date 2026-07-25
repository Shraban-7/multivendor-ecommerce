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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount', 6, 2)->nullable();
            $table->decimal('tax', 6, 2)->nullable();
            $table->decimal('shipping_fee', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('payable', 10, 2)->nullable();
            $table->decimal('due', 10, 2)->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('commission_type')->nullable();
            $table->decimal('commission_amount', 6, 1)->nullable();
            $table->decimal('total_commission', 8, 2)->nullable();
            $table->decimal('seller_earnings', 10, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delivery_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
