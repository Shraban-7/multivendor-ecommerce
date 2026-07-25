<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Compatibility shim: migration 2025_12_11_165049 drops the `sold` column from
 * flash_sale_products, but that column was never added via any prior migration.
 * This stub adds it so the subsequent drop succeeds on a fresh install / SQLite.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {
            if (! Schema::hasColumn('flash_sale_products', 'sold')) {
                $table->integer('sold')->default(0)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {
            if (Schema::hasColumn('flash_sale_products', 'sold')) {
                $table->dropColumn('sold');
            }
        });
    }
};
