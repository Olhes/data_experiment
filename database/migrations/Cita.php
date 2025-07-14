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
        Schema::create('Cita', function (Blueprint $table) {
            $table->increments('Id_cita'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->unsignedInteger('Id_paciente'); // INT NOT NULL (unsigned para FK)
            $table->unsignedInteger('Id_medico'); // INT NOT NULL (unsigned para FK)
            $table->date('fecha_cita'); // DATE NOT NULL
            $table->time('hora_cita'); // TIME NOT NULL
            $table->integer('duracion_minutos')->nullable(); // INT
            $table->text('motivo_consulta'); // TEXT NOT NULL
            $table->text('notas_medicas')->nullable(); // TEXT
            $table->string('estado_cita', 50)->default('Programada'); // VARCHAR(50) DEFAULT 'Programada'

            // Claves foráneas
            $table->foreign('Id_paciente')->references('Id_paciente')->on('Paciente')->onDelete('cascade');
            $table->foreign('Id_medico')->references('Id_medico')->on('Medico')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Cita');
    }
};