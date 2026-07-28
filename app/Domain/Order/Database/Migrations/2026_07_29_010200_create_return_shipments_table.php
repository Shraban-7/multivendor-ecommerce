<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->cascadeOnDelete();
            $table->string('direction', 20)->default('to_seller'); // to_seller|to_customer
            $table->string('carrier', 80)->nullable();
            $table->string('tracking_number', 120)->nullable();
            $table->string('status', 30)->default('pending'); // pending|in_transit|delivered|failed
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['return_request_id', 'direction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_shipments');
    }
};
