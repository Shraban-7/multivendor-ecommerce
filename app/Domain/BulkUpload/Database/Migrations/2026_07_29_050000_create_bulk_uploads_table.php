<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->string('status', 20)->default('pending')->comment('pending, processing, completed, failed, cancelled');
            $table->string('file_path');
            $table->string('file_type', 10)->comment('csv, xlsx');
            $table->string('original_filename');
            $table->integer('total_rows')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('fail_count')->default(0);
            $table->json('summary')->nullable();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
        });

        Schema::create('bulk_upload_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulk_upload_id')->constrained('bulk_uploads')->onDelete('cascade');
            $table->integer('row_number');
            $table->string('status', 20)->default('pending')->comment('pending, success, failed');
            $table->string('sku', 255)->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->json('errors')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index('bulk_upload_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_upload_rows');
        Schema::dropIfExists('bulk_uploads');
    }
};
