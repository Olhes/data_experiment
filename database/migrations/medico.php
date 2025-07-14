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
        Schema::create('Medico', function (Blueprint $table) {
            $table->increments('Id_medico'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->unsignedInteger('Id_usuario'); // INT NOT NULL (unsigned para FK)
            $table->string('nombre', 100); // VARCHAR(100) NOT NULL
            $table->string('apellido', 100); // VARCHAR(100) NOT NULL
            $table->string('licencia_medica', 50)->unique(); // VARCHAR(50) NOT NULL UNIQUE
            $table->string('telefono_consultorio', 20)->nullable(); // VARCHAR(20)
            $table->string('email_profesional', 255)->unique()->nullable(); // VARCHAR(255) UNIQUE
            $table->text('disponibilidad')->nullable(); // TEXT
            $table->unsignedInteger('Id_especialidad')->nullable(); // INT (unsigned para FK)

            // Claves foráneas
            $table->foreign('Id_usuario')->references('Id_usuario')->on('Usuario')->onDelete('cascade');
            $table->foreign('Id_especialidad')->references('id_especialidad')->on('Especialidad')->onDelete('set null'); // set null si la especialidad se borra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Medico');
    }
};