<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('type', 20)->default('fixed')->comment('fixed, mix_match');
            $table->string('price_type', 20)->default('auto')->comment('auto, manual');
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->string('discount_type', 20)->nullable()->comment('percentage, fixed');
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->integer('total_stock')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=active, 2=inactive, 3=draft');
            $table->boolean('is_visible')->default(false);
            $table->string('thumbnail')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
            $table->unique(['seller_id', 'sku']);
        });

        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('bundles')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->boolean('is_optional')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['bundle_id', 'product_id']);
        });

        Schema::create('bundle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('bundles')->cascadeOnDelete();
            $table->string('image');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('bundle_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('bundles')->cascadeOnDelete();
            $table->integer('min_items')->default(2);
            $table->integer('max_items')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_pricing_rules');
        Schema::dropIfExists('bundle_images');
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('bundles');
    }
};
