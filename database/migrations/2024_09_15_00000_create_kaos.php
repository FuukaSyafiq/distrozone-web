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
            $table->id('id_kaos');
            $table->unsignedInteger('merek_id');
            $table->string('nama_kaos')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('type_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('merek_id')->references('id')->on('merek_kaos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('type_id')->references('id')->on('type_kaos')->cascadeOnUpdate()->cascadeOnDelete();
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
