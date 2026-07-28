<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('subject', 200);
            $table->text('description');

            // Owner side — exactly one of (seller_id) or (user_id) is set.
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('category', 30)->default('other');
            $table->string('priority', 20)->default('normal');
            $table->string('status', 30)->default('open');

            $table->foreignId('assigned_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            $table->timestamp('first_admin_reply_at')->nullable();
            $table->timestamp('seller_last_reply_at')->nullable();
            $table->timestamp('admin_last_reply_at')->nullable();
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedInteger('reply_count')->default(0);

            $table->timestamps();

            $table->index(['status', 'priority'], 'st_status_priority_idx');
            $table->index(['seller_id', 'status'], 'st_seller_status_idx');
            $table->index(['assigned_admin_id', 'status'], 'st_admin_status_idx');
            $table->index('sla_due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
