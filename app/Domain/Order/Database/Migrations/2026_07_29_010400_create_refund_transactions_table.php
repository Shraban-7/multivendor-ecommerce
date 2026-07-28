<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refund_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->unsignedBigInteger('user_id'); // customer
            $table->unsignedBigInteger('seller_id');
            $table->decimal('amount', 12, 2);
            $table->string('method', 30); // gateway|wallet|manual
            $table->string('status', 20)->default('pending'); // pending|processing|success|failed
            $table->string('gateway', 30)->nullable();
            $table->string('gateway_reference', 120)->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['return_request_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_transactions');
    }
};
