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
            $table->tinyInteger('payment_type')->default(PaymentType::FULL_PAYMENT->value);

            $table->dropColumn('tax');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('payment_type')->default(PaymentType::FULL_PAYMENT->value);

            $table->dropColumn('tax');
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
