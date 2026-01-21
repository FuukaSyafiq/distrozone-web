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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id('id_detail_transaksi');
            $table->integer('id_transaksi');
            $table->integer('id_kaos_varian');

            $table->integer('qty');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('harga_pokok', 12, 2);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            $table->foreign('id_transaksi')
                ->references('id_transaksi')
                ->on('transaksi')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreign('id_kaos_varian')
                ->references('id')
                ->on('kaos_varian')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
