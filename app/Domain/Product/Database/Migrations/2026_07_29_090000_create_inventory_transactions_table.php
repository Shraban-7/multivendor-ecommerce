<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->string('type', 20); // addition, removal, set, adjustment
            $table->integer('quantity');
            $table->integer('stock_before')->default(0);
            $table->integer('stock_after')->default(0);
            $table->string('reason', 100)->nullable(); // sale, purchase, return, adjustment, initial
            $table->string('reference_type', 50)->nullable(); // order, purchase_order, adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'created_at']);
            $table->index(['seller_id', 'created_at']);
            $table->index('type');
            $table->index('reason');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
