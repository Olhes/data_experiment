<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'cita';
    protected $primaryKey = 'Id_cita';
    public $timestamps = false;

    protected $fillable = [
        'Id_paciente',
        'Id_medico',
        'fecha_cita',
        'hora_cita',
        'duracion_minutos',
        'motivo_consulta',
        'notas_medicas',
        'estado_cita',
    ];

    protected $casts = [
        'fecha_cita' => 'date',
        'hora_cita' => 'datetime:H:i',
        'duracion_minutos' => 'integer',
    ];

    // Estados válidos para las citas
    const ESTADO_PROGRAMADA = 'Programada';
    const ESTADO_CONFIRMADA = 'Confirmada';
    const ESTADO_COMPLETADA = 'Completada';
    const ESTADO_CANCELADA = 'Cancelada';
    const ESTADO_NO_ASISTIO = 'No asistió';

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'Id_paciente', 'Id_paciente');
    }

    // Relación con Médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'Id_medico', 'Id_medico');
    }

    // Relación con Actividad Financiera
    public function actividadFinanciera()
    {
        return $this->hasMany(ActividadFinanciera::class, 'id_cita', 'Id_cita');
    }

    // Accessor para fecha y hora completa
    public function getFechaHoraCompletaAttribute()
    {
        return Carbon::parse($this->fecha_cita->format('Y-m-d') . ' ' . $this->hora_cita->format('H:i:s'));
    }

    // Verificar si la cita está activa
    public function isActiva()
    {
        return in_array($this->estado_cita, [
            self::ESTADO_PROGRAMADA,
            self::ESTADO_CONFIRMADA
        ]);
    }

    // Verificar si la cita ya pasó
    public function isPasada()
    {
        return $this->fecha_hora_completa->isPast();
    }

    // Scopes
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_cita', $estado);
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha_cita', $fecha);
    }

    public function scopePorMedico($query, $medicoId)
    {
        return $query->where('Id_medico', $medicoId);
    }

    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('Id_paciente', $pacienteId);
    }

    public function scopeHoy($query)
    {
        return $query->where('fecha_cita', Carbon::today());
    }

    public function scopeProximas($query)
    {
        return $query->where('fecha_cita', '>=', Carbon::today())
                    ->orderBy('fecha_cita')
                    ->orderBy('hora_cita');
    }
}
