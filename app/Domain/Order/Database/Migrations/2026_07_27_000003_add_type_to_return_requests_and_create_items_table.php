<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->string('type', 20)->default('full')->after('reason');
        });

        Schema::create('return_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_request_items');

        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
