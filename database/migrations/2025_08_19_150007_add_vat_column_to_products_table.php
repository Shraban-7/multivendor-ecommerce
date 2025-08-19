<?php

use App\Enums\PaymentType;
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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('vat_percent', 5, 2)->default(0);
            $table->tinyInteger('payment_type')->default(PaymentType::FULL_PAYMENT->value);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('payment_type')->default(PaymentType::FULL_PAYMENT->value);
            $table->decimal('vat_amount', 10, 2)->default(0);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('vat_percent', 5, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
