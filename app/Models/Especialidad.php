<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidad';
    protected $primaryKey = 'id_especialidad';
    public $timestamps = false;

    protected $fillable = [
        'nombre_especialidad',
        'descripcion',
    ];

    // Relación con Médicos
    public function medicos()
    {
        return $this->hasMany(Medico::class, 'Id_especialidad', 'id_especialidad');
    }

    // Scope para buscar por nombre
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre_especialidad', 'LIKE', "%{$nombre}%");
    }
}
