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
        Schema::create('kaos', function (Blueprint $table) {
            $table->id("id_kaos");
            $table->string("merek_kaos");
            $table->string("nama_kaos")->unique();
            $table->text("description")->nullable();
            $table->enum("type_kaos", ["lengan panjang", "lengan pendek"]);
            $table->unsignedInteger("id_warna_kaos");
            $table->enum("ukuran", ["XS","S","M","L","XL","2XL","3XL","4XL","5XL"]);
            $table->decimal('harga_jual');
            $table->decimal("harga_pokok");
            $table->integer("stok_kaos");
            $table->timestamps();

            $table->foreign("id_warna_kaos")->references("id")->on("warna")->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaos');
    }
};
