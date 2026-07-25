<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (! Schema::hasColumn('sellers', 'code')) {
                $table->string('code', 10)->after('username');
            }

            if (! Schema::hasColumn('sellers', 'sku_counter')) {
                // Do not use after('status') — status is added in a later migration.
                $table->integer('sku_counter')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (Schema::hasColumn('sellers', 'sku_counter')) {
                $table->dropColumn('sku_counter');
            }

            if (Schema::hasColumn('sellers', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
