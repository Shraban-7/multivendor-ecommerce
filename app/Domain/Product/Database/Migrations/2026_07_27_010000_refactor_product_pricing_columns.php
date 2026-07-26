<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Renames legacy pricing columns for databases that already migrated
 * under the old buying_price / selling_price / discounted_price schema.
 * Fresh installs already create the new columns — this migration no-ops then.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->renameProductPricing('products');
        $this->renameProductPricing('product_variants');
        $this->renameOrderItemPricing();
        $this->renameStockPricing();
    }

    public function down(): void
    {
        // Irreversible data-shape change; recreate from backup if needed.
    }

    private function renameProductPricing(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        if (Schema::hasColumn($table, 'buying_price') && ! Schema::hasColumn($table, 'cost_price')) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->renameColumn('buying_price', 'cost_price');
            });
        }

        if (Schema::hasColumn($table, 'selling_price') && ! Schema::hasColumn($table, 'price')) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->renameColumn('selling_price', 'price');
            });
        }

        if (Schema::hasColumn($table, 'discounted_price') && ! Schema::hasColumn($table, 'compare_price')) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->renameColumn('discounted_price', 'compare_price');
            });
        }

        Schema::table($table, function (Blueprint $blueprint) use ($table) {
            if (Schema::hasColumn($table, 'discount_type')) {
                $blueprint->dropColumn('discount_type');
            }
            if (Schema::hasColumn($table, 'discount_value')) {
                $blueprint->dropColumn('discount_value');
            }
            if (Schema::hasColumn($table, 'discount_amount')) {
                $blueprint->dropColumn('discount_amount');
            }
            // If discounted_price still exists (rename skipped because compare_price already there)
            if (Schema::hasColumn($table, 'discounted_price')) {
                $blueprint->dropColumn('discounted_price');
            }
        });
    }

    private function renameOrderItemPricing(): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        if (Schema::hasColumn('order_items', 'buying_price') && ! Schema::hasColumn('order_items', 'cost_price')) {
            Schema::table('order_items', function (Blueprint $blueprint) {
                $blueprint->renameColumn('buying_price', 'cost_price');
            });
        }

        if (Schema::hasColumn('order_items', 'selling_price') && ! Schema::hasColumn('order_items', 'price')) {
            Schema::table('order_items', function (Blueprint $blueprint) {
                $blueprint->renameColumn('selling_price', 'price');
            });
        }
    }

    private function renameStockPricing(): void
    {
        if (! Schema::hasTable('product_stocks')) {
            return;
        }

        if (Schema::hasColumn('product_stocks', 'buying_price') && ! Schema::hasColumn('product_stocks', 'cost_price')) {
            Schema::table('product_stocks', function (Blueprint $blueprint) {
                $blueprint->renameColumn('buying_price', 'cost_price');
            });
        }
    }
};
