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
        Schema::create('seller_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_chat_id');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_chat_messages');
    }
};
