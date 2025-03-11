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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_bg_color')->nullable();
            $table->string('cover_title')->nullable();
            $table->string('cover_description')->nullable();
            $table->string('cover_text_color')->nullable();
            $table->string('cover_button_color')->nullable();
            $table->boolean('is_nav')->default(0);
            $table->boolean('is_special')->default(0);
            $table->boolean('is_slider')->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
