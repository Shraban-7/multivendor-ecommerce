<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->string('sender_type', 20); // seller|customer|admin|system
            $table->unsignedBigInteger('sender_id')->nullable();

            $table->text('body');
            $table->boolean('is_internal_note')->default(false); // admin-only internal notes
            $table->boolean('is_status_change')->default(false); // system message announcing status change
            $table->json('meta')->nullable(); // status transition details, attachments refs, etc.

            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['support_ticket_id', 'created_at'], 'stm_lookup_idx');
            $table->index('sender_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_messages');
    }
};
