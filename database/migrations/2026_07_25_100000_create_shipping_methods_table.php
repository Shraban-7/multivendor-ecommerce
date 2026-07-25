<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->unsignedBigInteger('district_id')->nullable()->index();
            $table->decimal('charge', 10, 2)->default(0);
            $table->decimal('free_above', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
