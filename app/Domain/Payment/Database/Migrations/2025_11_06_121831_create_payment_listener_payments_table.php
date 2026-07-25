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
        Schema::create('payment_listener_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->foreignId('device_id')->constrained('payment_listener_devices')->cascadeOnDelete();
            $table->string('sender');
            $table->string('sender_number')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('trx_id')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('full_sms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_listener_payments');
    }
};
