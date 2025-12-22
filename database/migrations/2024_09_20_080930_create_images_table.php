<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->unique();
            $table->string('mime_type')->nullable();
            $table->string('path');
            $table->integer("id_kaos")->nullable();
            $table->unsignedInteger('size')->nullable();

            $table->foreign("id_kaos")->references("id_kaos")->on("kaos")->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
