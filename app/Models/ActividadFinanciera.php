<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActividadFinanciera extends Model
{
    use HasFactory;

    protected $table = 'actividad_financiera';
    protected $primaryKey = 'id_actividad_financiera';
    public $timestamps = false;

    protected $fillable = [
        'id_cita',
        'id_usuario_registro',
        'descripcion',
        'monto',
        'tipo_movimiento',
        'fecha_movimiento',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_movimiento' => 'datetime',
    ];

    // Tipos de movimiento
    const TIPO_INGRESO = 'ingreso';
    const TIPO_GASTO = 'gasto';
    const TIPO_PAGO_CONSULTA = 'pago_consulta';
    const TIPO_PAGO_PROCEDIMIENTO = 'pago_procedimiento';

    // Relación con Cita
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'Id_cita');
    }

    // Relación con Usuario que registró (puede ser paciente o admin)
    public function usuarioRegistro()
    {
        return $this->belongsTo(Paciente::class, 'id_usuario_registro', 'Id_paciente');
    }

    // Verificar si es ingreso
    public function isIngreso()
    {
        return in_array($this->tipo_movimiento, [
            self::TIPO_INGRESO,
            self::TIPO_PAGO_CONSULTA,
            self::TIPO_PAGO_PROCEDIMIENTO
        ]);
    }

    // Verificar si es gasto
    public function isGasto()
    {
        return $this->tipo_movimiento === self::TIPO_GASTO;
    }

    // Scopes
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopeIngresos($query)
    {
        return $query->whereIn('tipo_movimiento', [
            self::TIPO_INGRESO,
            self::TIPO_PAGO_CONSULTA,
            self::TIPO_PAGO_PROCEDIMIENTO
        ]);
    }

    public function scopeGastos($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_GASTO);
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin = null)
    {
        if ($fechaFin) {
            return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
        }
        return $query->whereDate('fecha_movimiento', $fechaInicio);
    }

    public function scopeDelMes($query, $mes = null, $año = null)
    {
        $mes = $mes ?: Carbon::now()->month;
        $año = $año ?: Carbon::now()->year;
        
        return $query->whereMonth('fecha_movimiento', $mes)
                    ->whereYear('fecha_movimiento', $año);
    }
}
