<?php

use App\Enums\SubscriptionAction;
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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('old_plan_id')->nullable()->constrained('subscription_plans');
            $table->foreignId('new_plan_id')->constrained('subscription_plans');
            $table->enum('action', array_column(SubscriptionAction::cases(), 'value'));
            $table->foreignId('performed_by')->nullable()->constrained('admins');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_histories');
    }
};
