<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('seller_reply')->nullable()->after('description');
            $table->timestamp('replied_at')->nullable()->after('seller_reply');
            $table->boolean('is_approved')->default(true)->after('replied_at');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['seller_reply', 'replied_at', 'is_approved']);
        });
    }
};
