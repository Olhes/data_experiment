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
        Schema::create('Paciente', function (Blueprint $table) {
            $table->increments('Id_paciente'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->unsignedInteger('Id_usuario'); // INT NOT NULL (unsigned para FK)
            $table->string('nombre', 100); // VARCHAR(100) NOT NULL
            $table->string('apellido', 100); // VARCHAR(100) NOT NULL
            $table->date('fecha_nacimiento')->nullable(); // DATE
            $table->string('telefono', 20)->nullable(); // VARCHAR(20)
            $table->string('dni', 20)->unique()->nullable(); // VARCHAR(20) UNIQUE
            $table->string('genero', 10)->nullable(); // VARCHAR(10)
            $table->string('direccion', 255)->nullable(); // VARCHAR(255)

            // Clave foránea
            $table->foreign('Id_usuario')->references('Id_usuario')->on('Usuario')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Paciente');
    }
};