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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');

            $table->string('kode_transaksi', 30)->unique();

            $table->integer('id_kasir');
            $table->integer('id_customer');

            $table->enum('jenis_transaksi', ['OFFLINE', 'ONLINE']);

            $table->enum('metode_pembayaran', [
                'CASH',
                'QRIS',
                'TRANSFER'
            ]);

            $table->decimal('total_harga', 12, 2);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->integer('id_ongkir');
            $table->enum('status', [
                'PENDING',
                'ACC_KASIR',
                'SUKSES',
                'GAGAL',
                'DIKIRIM'
            ])->default("PENDING");

            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_kasir')
                ->references('id_user')
                ->on('users')
                ->onDelete('set null')->cascadeOnUpdate();

            $table->foreign('id_ongkir')
                ->references('id')
                ->on('ongkir')
                ->onDelete('set null')->cascadeOnUpdate();

            $table->foreign('id_customer')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('set null')->cascadeOnUpdate();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('transaksi');
    }
};
