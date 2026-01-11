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
        Schema::create('customer_service', function (Blueprint $table) {
            $table->id('id_chat');

            $table->integer('id_customer');
            $table->integer('id_admin')->nullable();

            $table->text('pesan');

            $table->enum('pengirim', ['CUSTOMER', 'ADMIN']);


            $table->timestamps();

            $table->foreign('id_customer')
                  ->references('id_user')
                  ->on('users')
                  ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreign('id_admin')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_service');
    }
};
