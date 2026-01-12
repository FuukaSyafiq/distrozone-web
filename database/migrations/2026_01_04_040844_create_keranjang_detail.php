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
        Schema::create('keranjang_detail', function (Blueprint $table) {
            $table->id('id_keranjang_detail');
            $table->unsignedInteger('id_kaos_varian');
            $table->unsignedInteger('id_keranjang');
            $table->integer('qty');
            $table->decimal('harga_satuan');
            $table->decimal('subtotal');
            $table->timestamps();

            $table->foreign('id_kaos_varian')
                ->references('id')
                ->on('kaos_varian')
                ->cascadeOnDelete()->cascadeOnUpdate();
                
            $table->foreign('id_keranjang')
                ->references('id_keranjang')
                ->on('keranjang')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang_detail');
    }
};
