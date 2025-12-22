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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id("id_pembayaran");
            $table->integer("id_transaksi");
            $table->enum('status', [
                'MENUNGGU',
                'DITERIMA',
                'DITOLAK'
            ])->default("MENUNGGU");

            $table->string("no_invoice");   
            $table->unsignedInteger("bukti_transfer")->nullable();
            $table->timestamps();

            $table->foreign("bukti_transfer")->references("id")->on("images")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("id_transaksi")->references("id_transaksi")->on("transaksi")->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pembayaran');
    }
};
