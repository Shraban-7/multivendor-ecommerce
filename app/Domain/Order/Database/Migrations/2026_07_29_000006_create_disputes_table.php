<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_request_id');
            $table->unsignedBigInteger('raised_by'); // user_id
            $table->string('reason', 2000);
            $table->text('description')->nullable();
            $table->string('status')->default('open'); // open, under_review, resolved, closed
            $table->text('admin_note')->nullable();
            $table->string('resolution')->nullable(); // approved, rejected, partial_refund
            $table->decimal('resolution_amount', 10, 2)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
