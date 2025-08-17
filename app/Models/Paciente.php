<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'paciente';
    protected $primaryKey = 'Id_paciente';
    public $timestamps = false;

    protected $fillable = [
        'Id_usuario',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'telefono',
        'dni',
        'genero',
        'direccion',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Id_usuario', 'Id_usuario');
    }

    // Relación con Citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'Id_paciente', 'Id_paciente');
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    // Accessor para edad
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    // Scope para buscar por DNI
    public function scopePorDni($query, $dni)
    {
        return $query->where('dni', $dni);
    }

    // Scope para buscar por nombre
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', 'LIKE', "%{$nombre}%")
                    ->orWhere('apellido', 'LIKE', "%{$nombre}%");
    }
}
