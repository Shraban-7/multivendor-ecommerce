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
        Schema::table('flash_sale_products', function (Blueprint $table) {
            $table->after('seller_id', function () use ($table) {
                $table->integer('stock_in')->default(0);
                $table->integer('stock_out')->default(0);
            });

            $table->dropColumn('sold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {});
    }
};
