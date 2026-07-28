<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->string('type', 40); // status_change|priority_change|assignment|resolved|closed|reopened|sla_warning
            $table->string('actor_type', 20); // seller|admin|system
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('from_value')->nullable();
            $table->string('to_value')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['support_ticket_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_events');
    }
};
