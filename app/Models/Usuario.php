<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'Id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'rol',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accessor para password (Laravel espera 'password', pero tu DB tiene 'password_hash')
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relación con Medico
    public function medico()
    {
        return $this->hasOne(Medico::class, 'Id_usuario', 'Id_usuario');
    }

    // Relación con Paciente
    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'Id_usuario', 'Id_usuario');
    }

    // Método para obtener el perfil según el rol
    public function getPerfilAttribute()
    {
        switch ($this->rol) {
            case 'medico':
                return $this->medico;
            case 'paciente':
                return $this->paciente;
            default:
                return null;
        }
    }

    // Verificar si es médico
    public function isMedico()
    {
        return $this->rol === 'medico';
    }

    // Verificar si es paciente
    public function isPaciente()
    {
        return $this->rol === 'paciente';
    }

    // Verificar si es admin
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }
}
