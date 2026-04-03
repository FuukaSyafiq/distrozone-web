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
        Schema::create('kaos_varian', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('kaos_id');
            $table->unsignedInteger('warna_id');
            $table->unsignedInteger('ukuran_id');
            $table->decimal('harga_jual');
            $table->decimal('harga_pokok');
            $table->unsignedInteger('stok_kaos');
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('warna_id')->references('id')->on('warna')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kaos_id')->references('id_kaos')->on('kaos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('ukuran_id')->references('id')->on('ukuran_kaos')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaos_varian');
    }
};
