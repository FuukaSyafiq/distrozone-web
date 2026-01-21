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

            $table->unsignedBigInteger('id_kasir')->nullable();
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->unsignedBigInteger('id_ongkir')->nullable();


            $table->enum('jenis_transaksi', ['OFFLINE', 'ONLINE']);

            $table->enum('metode_pembayaran', [
                'CASH',
                'JAGO',
                'BCA'
            ]);

            $table->decimal('total_harga', 12, 2);
            $table->decimal('ongkir', 12, 2)->nullable();
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
                ->onDelete('set null')->cascadeOnUpdate();
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
