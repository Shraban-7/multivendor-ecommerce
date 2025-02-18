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
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('thumbnail', 255)->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('buying_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->string('discount_type')->nullable();
            $table->double('discount_amount')->nullable();
            $table->integer('quantity')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->string('sku', 100)->nullable();
            $table->string('barcode', 255)->nullable();
            $table->tinyInteger('is_trending')->default(0);
            $table->tinyInteger('best_selling')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->string('video')->nullable();
            $table->boolean('status')->default(1);
            $table->string('stock_status')->nullable();
            $table->integer('stock_in')->default(0);
            $table->integer('stock_out')->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0.00);
            $table->decimal('tax', 5, 2)->default(0.00);
            $table->bigInteger('views')->default(0);
            $table->string('meta_title', 255)->nullable();
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
