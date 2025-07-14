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
        Schema::create('Usuario', function (Blueprint $table) {
            $table->increments('Id_usuario'); // INT PRIMARY KEY AUTO_INCREMENT
            $table->string('username', 100)->unique(); // VARCHAR(100) NOT NULL UNIQUE
            $table->string('password_hash', 255); // VARCHAR(255) NOT NULL
            $table->string('email', 255)->unique()->nullable(); // VARCHAR(255) UNIQUE (Laravel lo hace nullable por defecto si no es NOT NULL)
            $table->string('rol', 50)->default('usuario'); // VARCHAR(50) DEFAULT 'usuario'
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};