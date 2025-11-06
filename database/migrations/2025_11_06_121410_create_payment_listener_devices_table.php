<?php

use App\Models\PaymentListenerDevice;
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
        Schema::create('payment_listener_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('device_name')->nullable();
            $table->string('device_code')->nullable();
            $table->json('device_details')->nullable();
            $table->tinyInteger('status')->default(PaymentListenerDevice::STATUS_PENDING);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_listener_devices');
    }
};
