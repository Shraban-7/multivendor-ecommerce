<?php

use App\Models\OtpLog;
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
        Schema::create('otp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('code', 10);
            $table->enum('type', [OtpLog::TYPE_SIGNUP, OtpLog::TYPE_LOGIN, OtpLog::TYPE_PASSWORD_RESET])->default(OtpLog::TYPE_SIGNUP);
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_logs');
    }
};
