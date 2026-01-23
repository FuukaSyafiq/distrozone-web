<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id("id_user");
            $table->string('email');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('nama')->nullable();
            $table->enum("status", ["ACTIVE", "SUSPENDED", "BANNED"])->default("ACTIVE");
            $table->string('alamat_lengkap')->nullable();
            $table->unsignedInteger('kota_id')->nullable();
            $table->string('no_telepon')->nullable()->unique();
            $table->enum('nik_verified', ['EMPTY', 'PENDING','APPROVED','REJECTED'])->default('empty')->nullable();
            $table->string('nik')->nullable()->unique();
            $table->unsignedInteger('role_id');
            $table->string('foto_user')->nullable();
            $table->string('otp_code')->nullable();        // hash OTP
            $table->timestamp('otp_expires_at')->nullable();
        $table->boolean('otp_verified')->default(false);
            $table->rememberToken()->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kota_id')->references('id')->on('kota')->cascadeOnDelete()->cascadeOnUpdate();
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
