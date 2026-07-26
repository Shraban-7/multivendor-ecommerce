<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pricing columns are now defined on create_order_items for fresh installs.
     * This migration remains for historical DBs that still need the columns.
     */
    public function up(): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        // Legacy path: table still has buying_price (pre-refactor).
        if (Schema::hasColumn('order_items', 'buying_price') && ! Schema::hasColumn('order_items', 'selling_price') && ! Schema::hasColumn('order_items', 'price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('selling_price', 10, 2)->nullable()->after('buying_price');
            });
        }

        if (! Schema::hasColumn('order_items', 'total')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('total', 10, 2)->nullable()->after('sub_total');
            });
        }
    }

    public function down(): void
    {
        //
    }
};
