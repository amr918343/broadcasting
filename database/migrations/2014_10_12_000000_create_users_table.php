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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->text('image')->nullable();
            $table->string('full_name');
            $table->string('identity_number')->nullable();
            $table->string('hijri_date')->nullable();
            $table->string('reset_code')->nullable();
            $table->String('verification_code')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->text('ban_reason')->nullable();
            $table->string('wallet')->default(0);
            $table->string('locale')->default('ar');
            $table->rememberToken();
            $table->timestamps();
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
