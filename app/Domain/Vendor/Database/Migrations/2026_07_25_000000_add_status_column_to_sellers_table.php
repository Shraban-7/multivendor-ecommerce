<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (! Schema::hasColumn('sellers', 'status')) {
                // 0=pending, 1=active, 2=blocked, 4=deleted
                $table->unsignedTinyInteger('status')->default(0)->after('is_active');
            }
        });

        // Sync existing is_active values into status
        if (Schema::hasColumn('sellers', 'is_active')) {
            DB::statement('UPDATE sellers SET status = CASE WHEN is_active = 1 THEN 1 ELSE 0 END WHERE status = 0');
        }
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (Schema::hasColumn('sellers', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
