<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_class_id')->constrained('tax_classes')->onDelete('cascade');
            $table->string('name');
            $table->decimal('rate', 5, 2)->comment('Percentage e.g. 15.00 for 15%');
            $table->string('country', 100)->nullable()->comment('ISO country code or null for all');
            $table->string('state', 100)->nullable()->comment('State/region or null for all');
            $table->boolean('is_compound')->default(false);
            $table->integer('priority')->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_tax_class', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('tax_class_id')->constrained('tax_classes')->onDelete('cascade');
            $table->primary(['product_id', 'tax_class_id']);
        });

        Schema::create('seller_tax_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->foreignId('tax_class_id')->constrained('tax_classes');
            $table->string('tax_number', 100)->nullable()->comment('VAT/GST registration number');
            $table->boolean('is_tax_exempt')->default(false);
            $table->enum('tax_behavior', ['inclusive', 'exclusive'])->default('exclusive');
            $table->timestamps();
            $table->unique('seller_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('tax_amount', 10, 2)->default(0.00)->after('discount');
            $table->json('tax_breakdown')->nullable()->after('tax_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tax_amount', 'tax_breakdown']);
        });

        Schema::dropIfExists('seller_tax_configs');
        Schema::dropIfExists('product_tax_class');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('tax_classes');
    }
};
