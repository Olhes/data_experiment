<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medico';
    protected $primaryKey = 'Id_medico';
    public $timestamps = false;

    protected $fillable = [
        'Id_usuario',
        'nombre',
        'apellido',
        'licencia_medica',
        'telefono_consultorio',
        'email_profesional',
        'disponibilidad',
        'Id_especialidad',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Id_usuario', 'Id_usuario');
    }

    // Relación con Especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'Id_especialidad', 'id_especialidad');
    }

    // Relación con Citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'Id_medico', 'Id_medico');
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    // Scope para médicos disponibles
    public function scopeDisponibles($query)
    {
        return $query->whereNotNull('disponibilidad');
    }

    // Scope para médicos por especialidad
    public function scopePorEspecialidad($query, $especialidadId)
    {
        return $query->where('Id_especialidad', $especialidadId);
    }
}
