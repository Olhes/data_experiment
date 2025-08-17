<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CitasController extends Controller
{
    /**
     * Listar citas (filtradas según el rol del usuario)
     */
    public function index(Request $request)
    {
        try {
            $usuario = $request->attributes->get('current_user');
            $query = Cita::with(['paciente', 'medico.especialidad']);

            // Filtrar según el rol del usuario
            switch ($usuario->rol) {
                case 'medico':
                    if ($usuario->medico) {
                        $query->where('Id_medico', $usuario->medico->Id_medico);
                    }
                    break;
                case 'paciente':
                    if ($usuario->paciente) {
                        $query->where('Id_paciente', $usuario->paciente->Id_paciente);
                    }
                    break;
                case 'admin':
                    // Los admin pueden ver todas las citas
                    break;
            }

            // Filtros adicionales
            if ($request->has('fecha')) {
                $query->whereDate('fecha_cita', $request->fecha);
            }

            if ($request->has('estado')) {
                $query->where('estado_cita', $request->estado);
            }

            if ($request->has('medico_id')) {
                $query->where('Id_medico', $request->medico_id);
            }

            // Ordenar por fecha y hora
            $citas = $query->orderBy('fecha_cita')
                          ->orderBy('hora_cita')
                          ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $citas->map(function ($cita) {
                    return $this->formatCita($cita);
                }),
                'pagination' => [
                    'current_page' => $citas->currentPage(),
                    'total_pages' => $citas->lastPage(),
                    'total_items' => $citas->total(),
                    'per_page' => $citas->perPage()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener citas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nueva cita
     */
    public function store(Request $request)
    {
        try {
            $usuario = $request->attributes->get('current_user');

            $validationRules = [
                'Id_medico' => 'required|exists:medico,Id_medico',
                'fecha_cita' => 'required|date|after_or_equal:today',
                'hora_cita' => 'required|date_format:H:i',
                'duracion_minutos' => 'nullable|integer|min:15|max:240',
                'motivo_consulta' => 'required|string|max:1000'
            ];

            // Si es admin puede asignar a cualquier paciente, si no, usar el paciente del usuario actual
            if ($usuario->rol === 'admin') {
                $validationRules['Id_paciente'] = 'required|exists:paciente,Id_paciente';
            }

            $request->validate($validationRules);

            // Determinar ID del paciente
            $pacienteId = $usuario->rol === 'admin' 
                ? $request->Id_paciente 
                : $usuario->paciente->Id_paciente;

            // Verificar disponibilidad del médico
            $existeCita = Cita::where('Id_medico', $request->Id_medico)
                             ->where('fecha_cita', $request->fecha_cita)
                             ->where('hora_cita', $request->hora_cita)
                             ->where('estado_cita', '!=', 'Cancelada')
                             ->exists();

            if ($existeCita) {
                return response()->json([
                    'success' => false,
                    'message' => 'El médico ya tiene una cita programada para esa fecha y hora'
                ], 400);
            }

            $cita = Cita::create([
                'Id_paciente' => $pacienteId,
                'Id_medico' => $request->Id_medico,
                'fecha_cita' => $request->fecha_cita,
                'hora_cita' => $request->hora_cita,
                'duracion_minutos' => $request->duracion_minutos ?? 30,
                'motivo_consulta' => $request->motivo_consulta,
                'estado_cita' => Cita::ESTADO_PROGRAMADA
            ]);

            $cita->load(['paciente', 'medico.especialidad']);

            return response()->json([
                'success' => true,
                'message' => 'Cita creada exitosamente',
                'data' => $this->formatCita($cita)
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar cita específica
     */
    public function show(Request $request, $id)
    {
        try {
            $usuario = $request->attributes->get('current_user');
            $cita = Cita::with(['paciente', 'medico.especialidad'])->find($id);

            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            // Verificar permisos
            if (!$this->usuarioPuedeVerCita($usuario, $cita)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para ver esta cita'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatCita($cita)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener cita'
            ], 500);
        }
    }

    /**
     * Actualizar cita
     */
    public function update(Request $request, $id)
    {
        try {
            $usuario = $request->attributes->get('current_user');
            $cita = Cita::find($id);

            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            // Verificar permisos
            if (!$this->usuarioPuedeEditarCita($usuario, $cita)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para editar esta cita'
                ], 403);
            }

            $validationRules = [
                'fecha_cita' => 'sometimes|date|after_or_equal:today',
                'hora_cita' => 'sometimes|date_format:H:i',
                'duracion_minutos' => 'sometimes|integer|min:15|max:240',
                'motivo_consulta' => 'sometimes|string|max:1000',
                'notas_medicas' => 'sometimes|string|max:2000',
                'estado_cita' => 'sometimes|in:Programada,Confirmada,Completada,Cancelada,No asistió'
            ];

            $request->validate($validationRules);

            // Solo médicos pueden editar notas médicas
            if ($request->has('notas_medicas') && $usuario->rol !== 'medico') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo los médicos pueden editar las notas médicas'
                ], 403);
            }

            $cita->update($request->only([
                'fecha_cita', 'hora_cita', 'duracion_minutos', 
                'motivo_consulta', 'notas_medicas', 'estado_cita'
            ]));

            $cita->load(['paciente', 'medico.especialidad']);

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => $this->formatCita($cita)
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cita'
            ], 500);
        }
    }

    /**
     * Cancelar cita
     */
    public function cancel(Request $request, $id)
    {
        try {
            $usuario = $request->attributes->get('current_user');
            $cita = Cita::find($id);

            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            // Verificar permisos
            if (!$this->usuarioPuedeEditarCita($usuario, $cita)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para cancelar esta cita'
                ], 403);
            }

            $cita->update(['estado_cita' => Cita::ESTADO_CANCELADA]);

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar cita'
            ], 500);
        }
    }

    /**
     * Obtener citas del día actual
     */
    public function citasHoy(Request $request)
    {
        try {
            $usuario = $request->attributes->get('current_user');
            $query = Cita::with(['paciente', 'medico.especialidad'])
                         ->whereDate('fecha_cita', Carbon::today());

            // Filtrar según el rol
            switch ($usuario->rol) {
                case 'medico':
                    if ($usuario->medico) {
                        $query->where('Id_medico', $usuario->medico->Id_medico);
                    }
                    break;
                case 'paciente':
                    if ($usuario->paciente) {
                        $query->where('Id_paciente', $usuario->paciente->Id_paciente);
                    }
                    break;
            }

            $citas = $query->orderBy('hora_cita')->get();

            return response()->json([
                'success' => true,
                'data' => $citas->map(function ($cita) {
                    return $this->formatCita($cita);
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener citas de hoy'
            ], 500);
        }
    }

    /**
     * Formatear datos de la cita para respuesta
     */
    private function formatCita($cita)
    {
        return [
            'id' => $cita->Id_cita,
            'fecha_cita' => $cita->fecha_cita->format('Y-m-d'),
            'hora_cita' => $cita->hora_cita->format('H:i'),
            'duracion_minutos' => $cita->duracion_minutos,
            'motivo_consulta' => $cita->motivo_consulta,
            'notas_medicas' => $cita->notas_medicas,
            'estado_cita' => $cita->estado_cita,
            'fecha_hora_completa' => $cita->fecha_hora_completa->format('Y-m-d H:i'),
            'paciente' => [
                'id' => $cita->paciente->Id_paciente,
                'nombre_completo' => $cita->paciente->nombre_completo,
                'dni' => $cita->paciente->dni,
                'telefono' => $cita->paciente->telefono
            ],
            'medico' => [
                'id' => $cita->medico->Id_medico,
                'nombre_completo' => $cita->medico->nombre_completo,
                'licencia_medica' => $cita->medico->licencia_medica,
                'email_profesional' => $cita->medico->email_profesional,
                'especialidad' => $cita->medico->especialidad ? [
                    'id' => $cita->medico->especialidad->id_especialidad,
                    'nombre' => $cita->medico->especialidad->nombre_especialidad
                ] : null
            ]
        ];
    }

    /**
     * Verificar si el usuario puede ver la cita
     */
    private function usuarioPuedeVerCita($usuario, $cita)
    {
        switch ($usuario->rol) {
            case 'admin':
                return true;
            case 'medico':
                return $usuario->medico && $usuario->medico->Id_medico === $cita->Id_medico;
            case 'paciente':
                return $usuario->paciente && $usuario->paciente->Id_paciente === $cita->Id_paciente;
            default:
                return false;
        }
    }

    /**
     * Verificar si el usuario puede editar la cita
     */
    private function usuarioPuedeEditarCita($usuario, $cita)
    {
        // Los pacientes no pueden editar citas pasadas
        if ($usuario->rol === 'paciente' && $cita->isPasada()) {
            return false;
        }

        return $this->usuarioPuedeVerCita($usuario, $cita);
    }
}
