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
            $table->string('username')->index();;
            $table->string('password');
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->string('nik')->unique();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('foto_id')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('foto_id')->references('id')->on('images')->cascadeOnDelete()->cascadeOnUpdate();
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
