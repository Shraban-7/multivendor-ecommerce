<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode', 32)->nullable()->unique()->after('sku');
                $table->index('barcode');
            }
        });

        // Backfill existing products that have no barcode.
        $existing = DB::table('products')
            ->whereNull('barcode')
            ->orWhere('barcode', '')
            ->select('id', 'sku')
            ->get();

        foreach ($existing as $row) {
            DB::table('products')
                ->where('id', $row->id)
                ->update(['barcode' => \App\Domain\Product\Models\Product::generateBarcode()]);
        }

        // Backfill existing variants that have no barcode.
        if (Schema::hasColumn('product_variants', 'barcode')) {
            $existingVariants = DB::table('product_variants')
                ->whereNull('barcode')
                ->orWhere('barcode', '')
                ->select('id', 'sku')
                ->get();

            foreach ($existingVariants as $row) {
                DB::table('product_variants')
                    ->where('id', $row->id)
                    ->update(['barcode' => \App\Domain\Product\Models\ProductVariant::generateBarcode()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'barcode')) {
                $table->dropIndex(['barcode']);
                $table->dropColumn('barcode');
            }
        });
    }
};
