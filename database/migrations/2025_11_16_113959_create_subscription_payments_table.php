<?php

use App\Enums\SubscriptionPaymentMethod;
use App\Enums\SubscriptionPaymentStatus;
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
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_subscription_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', array_column(SubscriptionPaymentMethod::cases(), 'value'));
            $table->enum('status', array_column(SubscriptionPaymentStatus::cases(), 'value'));
            $table->text('payment_details')->nullable(); // JSON
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
