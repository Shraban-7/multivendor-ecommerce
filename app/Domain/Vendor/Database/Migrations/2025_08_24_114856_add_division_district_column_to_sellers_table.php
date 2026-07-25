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
        Schema::table('sellers', function (Blueprint $table) {
            $table->unsignedBigInteger('division_id')->nullable()->after('password');
            $table->unsignedBigInteger('district_id')->nullable()->after('division_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('division_id')->nullable()->after('password');
            $table->unsignedBigInteger('district_id')->nullable()->after('division_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            //
        });
    }
};
