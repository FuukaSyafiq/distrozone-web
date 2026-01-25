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
        Schema::create('payment_method', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank'); // Contoh: BCA, Mandiri, BRI
            $table->string('nomor_rekening'); // Nomor VA atau Nomor Rekening
            $table->string('nama_penerima'); // Nama pemilik rekening/perusahaan
            $table->string('logo')->nullable(); // Path untuk logo bank (opsional)
            $table->text('instruksi')->nullable(); // Cara bayar (misal: Masukkan kartu, pilih transfer...)
            $table->boolean('is_active')->default(true); // Untuk mematikan/menghidupkan metode ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
