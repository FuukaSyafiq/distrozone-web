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
        Schema::create('jam_operasional', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis',['OFFLINE','ONLINE']);
            $table->enum("hari",['SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU','MINGGU']);
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->enum("status",['BUKA', 'TUTUP'])->default("BUKA");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_operasional');
    }
};
