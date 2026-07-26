<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasColor = Schema::hasColumn('product_variants', 'color_id');
        $hasSize = Schema::hasColumn('product_variants', 'size_id');

        if (! $hasColor || ! $hasSize) {
            Schema::table('product_variants', function (Blueprint $table) use ($hasColor, $hasSize) {
                if (! $hasColor) {
                    $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete()->after('product_id');
                }

                if (! $hasSize) {
                    $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete()->after('color_id');
                }
            });
        }

        // SKU uniqueness is enforced per-product in app logic; drop global unique if present.
        if (Schema::hasIndex('product_variants', 'product_variants_sku_unique')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropUnique(['sku']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);
            $table->dropColumn(['color_id', 'size_id']);
            $table->string('sku')->unique()->change();
        });
    }
};