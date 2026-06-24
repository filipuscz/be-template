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
        Schema::create('me_user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('me_users')->cascadeOnDelete();
            $table->string('transaction_pin')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('identity_number')->nullable();
            $table->text('address')->nullable();
            $table->ipAddress('last_transaction_ip')->nullable();
            $table->timestamp('last_transaction_at')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->json('security_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('me_user_details');
    }
};
