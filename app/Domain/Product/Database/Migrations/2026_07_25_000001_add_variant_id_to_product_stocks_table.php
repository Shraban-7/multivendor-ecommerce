<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds product_variant_id to product_stocks so the table can serve
 * as SoT for both product-level and variant-level stock entries.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            if (! Schema::hasColumn('product_stocks', 'product_variant_id')) {
                $table->unsignedBigInteger('product_variant_id')->nullable()->after('product_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            if (Schema::hasColumn('product_stocks', 'product_variant_id')) {
                $table->dropColumn('product_variant_id');
            }
        });
    }
};
