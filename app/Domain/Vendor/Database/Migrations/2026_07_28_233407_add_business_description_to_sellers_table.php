<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (! Schema::hasColumn('sellers', 'business_description')) {
                $table->text('business_description')->nullable()->after('business_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (Schema::hasColumn('sellers', 'business_description')) {
                $table->dropColumn('business_description');
            }
        });
    }
};
