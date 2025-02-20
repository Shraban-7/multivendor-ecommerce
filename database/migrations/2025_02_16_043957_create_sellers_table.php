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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('username')->unique();
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');

            $table->string('business_name')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('business_email')->nullable()->unique();
            $table->text('business_address')->nullable();

            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->string('zip')->nullable();

            $table->string('total_follower')->nullable();
            $table->string('total_sold')->nullable();
            $table->string('total_item')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
