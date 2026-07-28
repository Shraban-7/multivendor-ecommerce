<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->string('rma_number')->nullable()->unique()->after('id');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('admin_note');
            $table->timestamp('return_window_end')->nullable()->after('rejected_at');
            $table->boolean('is_disputed')->default(false)->after('return_window_end');
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn(['rma_number', 'rejected_at', 'rejection_reason', 'return_window_end', 'is_disputed']);
        });
    }
};
