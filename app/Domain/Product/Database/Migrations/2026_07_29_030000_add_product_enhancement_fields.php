<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['product_id', 'tag_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('specifications')->nullable()->after('description');
            $table->string('country_of_origin', 100)->nullable()->after('length');
            $table->string('manufacturer_name', 255)->nullable()->after('country_of_origin');
            $table->text('manufacturer_details')->nullable()->after('manufacturer_name');
            $table->boolean('is_visible')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'specifications',
                'country_of_origin',
                'manufacturer_name',
                'manufacturer_details',
                'is_visible',
            ]);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('tags');
    }
};
