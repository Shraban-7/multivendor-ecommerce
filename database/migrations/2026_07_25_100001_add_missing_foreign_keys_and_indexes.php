<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('orders', ['seller_id', 'status'], 'orders_seller_id_status_index');
        $this->addIndexIfMissing('orders', ['user_id', 'created_at'], 'orders_user_id_created_at_index');
        $this->addIndexIfMissing('products', ['seller_id', 'status'], 'products_seller_id_status_index');
        $this->addIndexIfMissing('products', ['category_id'], 'products_category_id_index');
        $this->addIndexIfMissing('order_items', ['order_id'], 'order_items_order_id_index');
        $this->addIndexIfMissing('order_items', ['product_id'], 'order_items_product_id_index');
        $this->addIndexIfMissing('payments', ['transaction_id'], 'payments_transaction_id_index');
        $this->addIndexIfMissing('cart_items', ['cart_id'], 'cart_items_cart_id_index');
    }

    public function down(): void
    {
        $this->dropIndexIfExists('orders', 'orders_seller_id_status_index');
        $this->dropIndexIfExists('orders', 'orders_user_id_created_at_index');
        $this->dropIndexIfExists('products', 'products_seller_id_status_index');
        $this->dropIndexIfExists('products', 'products_category_id_index');
        $this->dropIndexIfExists('order_items', 'order_items_order_id_index');
        $this->dropIndexIfExists('order_items', 'order_items_product_id_index');
        $this->dropIndexIfExists('payments', 'payments_transaction_id_index');
        $this->dropIndexIfExists('cart_items', 'cart_items_cart_id_index');
    }

    /**
     * @param  array<int, string>  $columns
     */
    private function addIndexIfMissing(string $table, array $columns, string $index): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        if (Schema::hasIndex($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $index) {
            $blueprint->index($columns, $index);
        });
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasIndex($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($index) {
            $blueprint->dropIndex($index);
        });
    }
};
