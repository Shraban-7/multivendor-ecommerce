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
        Schema::table('system_settings', function (Blueprint $table) {
            $table->after('address', function () use ($table) {
                $table->string('facebook_pixel')->nullable();
                $table->string('facebook_capi')->nullable();
                $table->string('google_analytics')->nullable();
                $table->string('google_tag_manager')->nullable();
                $table->string('seo_title')->nullable();
                $table->string('seo_description')->nullable();
                $table->string('seo_keywords')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn('facebook_pixel');
            $table->dropColumn('facebook_capi');
            $table->dropColumn('google_analytics');
            $table->dropColumn('google_tag_manager');
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_description');
            $table->dropColumn('seo_keywords');
        });
    }
};
