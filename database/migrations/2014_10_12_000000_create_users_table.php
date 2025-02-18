<?php

use App\Enums\UserType;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('username')->unique();
            $table->string('displayname')->nullable();
            $table->string('image')->nullable();

            $table->string('email')->unique();
            $table->string('secondary_email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('password');

            $table->integer('role')->default(UserType::CUSTOMER);

            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->string('zip')->nullable();

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
        Schema::dropIfExists('users');
    }
};
