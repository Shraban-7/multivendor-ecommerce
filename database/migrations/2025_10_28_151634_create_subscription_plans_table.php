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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('duration_type', ['monthly', 'yearly'])->default('monthly');
            $table->integer('product_limit')->default(10);
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->boolean('pos_access')->default(false);
            $table->boolean('analytics_access')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('custom_domain')->default(false);
            $table->integer('staff_account_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
