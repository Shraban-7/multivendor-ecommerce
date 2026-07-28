<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->nullOnDelete();
            $table->string('reason', 100)->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('seller_id');
            $table->dropColumn('reason');
        });
    }
};
