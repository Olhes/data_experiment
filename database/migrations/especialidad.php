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
        Schema::create('Especialidad', function (Blueprint $table) {
            $table->increments('id_especialidad'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->string('nombre_especialidad', 100)->unique(); // VARCHAR(100) NOT NULL UNIQUE
            $table->text('descripcion')->nullable(); // TEXT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Especialidad');
    }
};