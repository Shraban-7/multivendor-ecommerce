<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('api_endpoint')->nullable();
            $table->string('api_key')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('seller_shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('name');
            $table->string('type'); // flat, weight_based, price_based
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('free_above', 10, 2)->nullable();
            $table->decimal('extra_rate_per_kg', 10, 2)->nullable();
            $table->decimal('min_weight', 10, 2)->nullable();
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->decimal('min_order', 10, 2)->nullable();
            $table->decimal('max_order', 10, 2)->nullable();
            $table->json('districts')->nullable();
            $table->boolean('is_cod_available')->default(true);
            $table->unsignedBigInteger('carrier_id')->nullable();
            $table->integer('estimated_days_min')->nullable();
            $table->integer('estimated_days_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('carrier_id')->references('id')->on('shipping_carriers')->onDelete('set null');
        });

        Schema::table('order_trackings', function (Blueprint $table) {
            $table->string('tracking_number', 255)->nullable()->after('courier_name');
            $table->unsignedBigInteger('carrier_id')->nullable()->after('tracking_number');
            $table->foreign('carrier_id')->references('id')->on('shipping_carriers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('order_trackings', function (Blueprint $table) {
            $table->dropForeign(['carrier_id']);
            $table->dropColumn(['tracking_number', 'carrier_id']);
        });

        Schema::dropIfExists('seller_shipping_zones');
        Schema::dropIfExists('shipping_carriers');
    }
};
