<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (! Schema::hasIndex('orders', 'orders_seller_id_status_created_at_index')) {
            Schema::table('orders', function (Blueprint $t) {
                $t->index(['seller_id', 'status', 'created_at'], 'orders_seller_id_status_created_at_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropIndex('orders_seller_id_status_created_at_index');
        });
    }
};
