<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'barcode')) {
                $table->string('barcode', 100)->nullable()->unique()->after('sku');
            }

            if (! Schema::hasColumn('product_variants', 'weight')) {
                $table->decimal('weight', 10, 2)->nullable()->after('compare_price');
            }

            if (! Schema::hasColumn('product_variants', 'status')) {
                $table->boolean('status')->default(true)->after('low_stock_quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'weight', 'status']);
        });
    }
};
