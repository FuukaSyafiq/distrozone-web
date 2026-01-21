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
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('transaksi_id');
            $table->date('tanggal');
            $table->decimal('jumlah', 12, 2);
            $table->enum('jenis', ['OFFLINE', 'ONLINE']);
            $table->timestamps();
          
            $table->foreign('transaksi_id')
                ->references('id_transaksi')
                ->on('transaksi')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapatan');
    }
};
