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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id("id_review");
            $table->text('review');
            $table->integer('star');
            $table->integer('id_customer');
            $table->integer('id_kaos');
            $table->timestamps();
            
            $table->foreign('id_customer')->references('id_user')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_kaos')->references('id_kaos')->on('kaos')->cascadeOnDelete()->cascadeOnUpdate();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
