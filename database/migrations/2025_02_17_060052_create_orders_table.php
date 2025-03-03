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
            $table->string('tracking_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->cascadeOnDelete();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('shipping_fee', 10, 2)->nullable();
            $table->decimal('payable', 10, 2)->nullable();
            $table->decimal('due', 10, 2)->nullable();
            $table->integer('status')->default(1);
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
