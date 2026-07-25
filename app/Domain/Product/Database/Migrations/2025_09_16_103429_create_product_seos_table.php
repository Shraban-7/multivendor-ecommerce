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
        Schema::create('product_seos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            $table->string('meta_title', 70)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();

            $table->string('og_title', 70)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('meta_desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_seos');
    }
};
