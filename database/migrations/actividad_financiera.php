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
        Schema::create('Actividad_Financiera', function (Blueprint $table) {
            $table->increments('id_actividad_financiera'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->unsignedInteger('id_cita')->nullable(); // INT (unsigned para FK)
            $table->unsignedInteger('id_usuario_registro'); // INT NOT NULL (unsigned para FK)
            $table->text('descripcion')->nullable(); // TEXT
            $table->decimal('monto', 10, 2); // DECIMAL(10, 2) NOT NULL
            $table->string('tipo_movimiento', 50); // VARCHAR(50) NOT NULL
            $table->dateTime('fecha_movimiento')->default(DB::raw('CURRENT_TIMESTAMP')); // DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP

            // Claves foráneas
            $table->foreign('id_cita')->references('Id_cita')->on('Cita')->onDelete('set null'); // set null si la cita se borra
            // OJO: id_usuario_registro referencia a Paciente(Id_paciente) en tu SQL, no a Usuario(Id_usuario)
            // Si id_usuario_registro es un Paciente, la FK es a Paciente.
            // Si es un Usuario que registra, la FK es a Usuario. He asumido Paciente según tu SQL.
            $table->foreign('id_usuario_registro')->references('Id_paciente')->on('Paciente')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Actividad_Financiera');
    }
};