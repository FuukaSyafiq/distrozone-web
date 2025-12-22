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
        Schema::create('permission_name', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum("action", ["CREATE", "READ", "UPDATE", "DELETE" , "VIEWPAGE", "ACCESS"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_name');
    }
};
