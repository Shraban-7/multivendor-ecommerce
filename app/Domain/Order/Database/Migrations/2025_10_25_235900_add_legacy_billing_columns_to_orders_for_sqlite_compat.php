<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Compatibility shim: a later migration (2025_10_26) drops billing_address_id
 * and billing_information from orders, but those columns were never added via
 * a migration (they existed only in the production MySQL DB). This stub adds
 * them in SQLite / fresh installs so the subsequent drop succeeds.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'billing_address_id')) {
                $table->unsignedBigInteger('billing_address_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('orders', 'billing_information')) {
                $table->text('billing_information')->nullable()->after('billing_address_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                $table->dropColumn('billing_address_id');
            }

            if (Schema::hasColumn('orders', 'billing_information')) {
                $table->dropColumn('billing_information');
            }
        });
    }
};
