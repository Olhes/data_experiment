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
        Schema::create('Medicacion', function (Blueprint $table) {
            $table->increments('id_medicina'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->string('nombre_comercial', 255); // VARCHAR(255) NOT NULL
            $table->string('nombre_generico', 255)->nullable(); // VARCHAR(255)
            $table->string('presentacion', 100)->nullable(); // VARCHAR(100)
            $table->string('dosis_estandar', 100)->nullable(); // VARCHAR(100)
            $table->string('fabricante', 255)->nullable(); // VARCHAR(255)
            $table->text('descripcion')->nullable(); // TEXT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Medicacion');
    }
};