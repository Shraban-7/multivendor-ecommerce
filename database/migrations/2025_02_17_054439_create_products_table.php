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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video')->nullable();

            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);

            $table->string('discount_type')->nullable();
            $table->decimal('discount_value',6,1)->nullable();
            $table->decimal('discount_amount',8,2)->nullable();
            $table->decimal('discounted_price',10,2)->nullable();

            $table->unsignedBigInteger('unit_id')->nullable();
            $table->integer('unit_value')->nullable();

            $table->integer('stock_in')->default(0);
            $table->integer('stock_out')->default(0);
            $table->integer('low_stock_quantity')->default(0);

            $table->tinyInteger('is_trending')->default(0);
            $table->tinyInteger('best_selling')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_interest')->default(0);
            $table->tinyInteger('is_community')->default(0);
            $table->tinyInteger('is_lightdeal')->default(0);
            $table->dateTime('lightdeal_expired_at')->nullable();

            $table->decimal('tax', 5, 2)->default(0.00);

            $table->boolean('is_active')->default(true);
            $table->bigInteger('views')->default(0);
            $table->decimal('avg_rating', 3, 1)->default(0.0);
            $table->integer('rating_count')->default(0);

            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_desc')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
