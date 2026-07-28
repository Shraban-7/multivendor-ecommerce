<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disputes', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_admin_id')->nullable()->after('raised_by');
            $table->text('seller_response')->nullable()->after('admin_note');
            $table->timestamp('seller_responded_at')->nullable()->after('seller_response');
            $table->index('status', 'disputes_status_idx');
            $table->index('assigned_admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('disputes', function (Blueprint $table) {
            $table->dropIndex('disputes_status_idx');
            $table->dropColumn(['assigned_admin_id', 'seller_response', 'seller_responded_at']);
        });
    }
};
