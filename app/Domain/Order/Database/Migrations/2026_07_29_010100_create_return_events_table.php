<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('actor_type', 30); // customer|seller|admin|system
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('from_state', 30)->nullable();
            $table->string('to_state', 30)->nullable();
            $table->text('note')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['return_request_id', 'created_at'], 're_lookup_idx');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_events');
    }
};
