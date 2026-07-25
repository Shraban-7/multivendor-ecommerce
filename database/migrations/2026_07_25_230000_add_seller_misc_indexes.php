<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_histories')) {
            Schema::table('stock_histories', function (Blueprint $t) {
                $t->index(['product_id', 'id'], 'stock_histories_product_id_id_index');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $t) {
                $t->index(['seller_id', 'user_id'], 'orders_seller_id_user_id_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('stock_histories', function (Blueprint $t) {
            $t->dropIndex('stock_histories_product_id_id_index');
        });

        Schema::table('orders', function (Blueprint $t) {
            $t->dropIndex('orders_seller_id_user_id_index');
        });
    }
};
