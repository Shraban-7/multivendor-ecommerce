<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('products', ['status', 'category_id'], 'products_status_category_id_index');
        $this->addIndexIfMissing('products', ['status', 'seller_id'], 'products_status_seller_id_index');
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $t->dropIndex('products_status_category_id_index');
            $t->dropIndex('products_status_seller_id_index');
        });
    }

    private function addIndexIfMissing(string $table, array $columns, string $index): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        if (! Schema::hasIndex($table, $index)) {
            Schema::table($table, function (Blueprint $t) use ($columns, $index) {
                $t->index($columns, $index);
            });
        }
    }
};
