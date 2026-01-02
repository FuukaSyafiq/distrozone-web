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
            $table->string('alamat')->nullable();
            $table->string('no_telepon')->nullable()->unique();
            $table->boolean('verified')->default(false)->nullable();
            $table->string('nik')->nullable()->unique();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('foto_id')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('foto_id')
                ->references('id')->on('images')
                ->nullOnDelete();
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
