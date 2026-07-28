<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_payout_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('method_type'); // bank, mobile_banking, cash
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('mobile_provider')->nullable(); // bKash, Nagad, Rocket
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
        });

        Schema::create('seller_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('payout_method_id')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('charge', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->tinyInteger('status')->default(0); // 0=pending, 1=processing, 2=completed, 3=cancelled, 4=failed
            $table->text('admin_note')->nullable();
            $table->text('seller_note')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('payout_method_id')->references('id')->on('seller_payout_methods')->onDelete('set null');
            $table->foreign('processed_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_payouts');
        Schema::dropIfExists('seller_payout_methods');
    }
};
