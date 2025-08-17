<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CitasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar rutas API para tu aplicación. Estas rutas
| son cargadas por el RouteServiceProvider y todas serán asignadas
| al grupo de middleware "api". ¡Haz algo genial!
|
*/

// Rutas públicas (sin autenticación)
Route::prefix('v1')->group(function () {
    // Autenticación
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    // Ruta de prueba
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'API funcionando correctamente',
            'timestamp' => now(),
            'version' => '1.0.0'
        ]);
    });

    // Información pública de especialidades (para registro de médicos)
    Route::get('/especialidades', function () {
        $especialidades = \App\Models\Especialidad::all();
        return response()->json([
            'success' => true,
            'data' => $especialidades
        ]);
    });
});

// Rutas protegidas (requieren autenticación JWT)
Route::prefix('v1')->middleware(['jwt.auth'])->group(function () {
    
    // Rutas de autenticación (usuario autenticado)
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
    });

    // Rutas de citas (accesibles para todos los roles autenticados)
    Route::prefix('citas')->group(function () {
        Route::get('/', [CitasController::class, 'index']);
        Route::post('/', [CitasController::class, 'store']);
        Route::get('/hoy', [CitasController::class, 'citasHoy']);
        Route::get('/{id}', [CitasController::class, 'show']);
        Route::put('/{id}', [CitasController::class, 'update']);
        Route::patch('/{id}/cancelar', [CitasController::class, 'cancel']);
    });

    // Rutas generales (todos los usuarios autenticados)
    Route::get('/medicos', function () {
        $medicos = \App\Models\Medico::with('especialidad')->get();
        return response()->json([
            'success' => true,
            'data' => $medicos->map(function ($medico) {
                return [
                    'id' => $medico->Id_medico,
                    'nombre_completo' => $medico->nombre_completo,
                    'especialidad' => $medico->especialidad ? $medico->especialidad->nombre_especialidad : null,
                    'email_profesional' => $medico->email_profesional,
                    'telefono_consultorio' => $medico->telefono_consultorio
                ];
            })
        ]);
    });

    // Rutas solo para médicos
    Route::middleware(['jwt.auth:medico'])->prefix('medico')->group(function () {
        Route::get('/agenda', function (Request $request) {
            $usuario = $request->attributes->get('current_user');
            $fechaInicio = $request->get('fecha_inicio', \Carbon\Carbon::today());
            $fechaFin = $request->get('fecha_fin', \Carbon\Carbon::today()->addDays(7));

            $citas = \App\Models\Cita::with(['paciente'])
                ->where('Id_medico', $usuario->medico->Id_medico)
                ->whereBetween('fecha_cita', [$fechaInicio, $fechaFin])
                ->orderBy('fecha_cita')
                ->orderBy('hora_cita')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'medico' => $usuario->medico->nombre_completo,
                    'periodo' => [
                        'desde' => $fechaInicio,
                        'hasta' => $fechaFin
                    ],
                    'citas' => $citas->map(function ($cita) {
                        return [
                            'id' => $cita->Id_cita,
                            'fecha_cita' => $cita->fecha_cita->format('Y-m-d'),
                            'hora_cita' => $cita->hora_cita->format('H:i'),
                            'paciente' => $cita->paciente->nombre_completo,
                            'motivo' => $cita->motivo_consulta,
                            'estado' => $cita->estado_cita
                        ];
                    })
                ]
            ]);
        });

        Route::get('/estadisticas', function (Request $request) {
            $usuario = $request->attributes->get('current_user');
            $medico = $usuario->medico;

            $estadisticas = [
                'citas_hoy' => \App\Models\Cita::where('Id_medico', $medico->Id_medico)
                    ->whereDate('fecha_cita', \Carbon\Carbon::today())
                    ->count(),
                'citas_semana' => \App\Models\Cita::where('Id_medico', $medico->Id_medico)
                    ->whereBetween('fecha_cita', [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek()
                    ])
                    ->count(),
                'citas_mes' => \App\Models\Cita::where('Id_medico', $medico->Id_medico)
                    ->whereMonth('fecha_cita', \Carbon\Carbon::now()->month)
                    ->whereYear('fecha_cita', \Carbon\Carbon::now()->year)
                    ->count(),
                'citas_completadas_mes' => \App\Models\Cita::where('Id_medico', $medico->Id_medico)
                    ->where('estado_cita', 'Completada')
                    ->whereMonth('fecha_cita', \Carbon\Carbon::now()->month)
                    ->whereYear('fecha_cita', \Carbon\Carbon::now()->year)
                    ->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);
        });
    });

    // Rutas solo para pacientes
    Route::middleware(['jwt.auth:paciente'])->prefix('paciente')->group(function () {
        Route::get('/historial', function (Request $request) {
            $usuario = $request->attributes->get('current_user');
            
            $citas = \App\Models\Cita::with(['medico.especialidad'])
                ->where('Id_paciente', $usuario->paciente->Id_paciente)
                ->orderBy('fecha_cita', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => [
                    'paciente' => $usuario->paciente->nombre_completo,
                    'citas' => $citas->map(function ($cita) {
                        return [
                            'id' => $cita->Id_cita,
                            'fecha_cita' => $cita->fecha_cita->format('Y-m-d'),
                            'hora_cita' => $cita->hora_cita->format('H:i'),
                            'medico' => $cita->medico->nombre_completo,
                            'especialidad' => $cita->medico->especialidad ? $cita->medico->especialidad->nombre_especialidad : null,
                            'motivo' => $cita->motivo_consulta,
                            'notas_medicas' => $cita->notas_medicas,
                            'estado' => $cita->estado_cita
                        ];
                    }),
                    'pagination' => [
                        'current_page' => $citas->currentPage(),
                        'total_pages' => $citas->lastPage(),
                        'total' => $citas->total()
                    ]
                ]
            ]);
        });

        Route::get('/proximas-citas', function (Request $request) {
            $usuario = $request->attributes->get('current_user');
            
            $citas = \App\Models\Cita::with(['medico.especialidad'])
                ->where('Id_paciente', $usuario->paciente->Id_paciente)
                ->where('fecha_cita', '>=', \Carbon\Carbon::today())
                ->whereIn('estado_cita', ['Programada', 'Confirmada'])
                ->orderBy('fecha_cita')
                ->orderBy('hora_cita')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $citas->map(function ($cita) {
                    return [
                        'id' => $cita->Id_cita,
                        'fecha_cita' => $cita->fecha_cita->format('Y-m-d'),
                        'hora_cita' => $cita->hora_cita->format('H:i'),
                        'medico' => $cita->medico->nombre_completo,
                        'especialidad' => $cita->medico->especialidad ? $cita->medico->especialidad->nombre_especialidad : null,
                        'motivo' => $cita->motivo_consulta,
                        'estado' => $cita->estado_cita,
                        'dias_restantes' => \Carbon\Carbon::parse($cita->fecha_cita)->diffInDays(\Carbon\Carbon::today())
                    ];
                })
            ]);
        });
    });

    // Rutas solo para administradores
    Route::middleware(['jwt.auth:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            $estadisticas = [
                'total_usuarios' => \App\Models\Usuario::count(),
                'total_medicos' => \App\Models\Medico::count(),
                'total_pacientes' => \App\Models\Paciente::count(),
                'citas_hoy' => \App\Models\Cita::whereDate('fecha_cita', \Carbon\Carbon::today())->count(),
                'citas_pendientes' => \App\Models\Cita::whereIn('estado_cita', ['Programada', 'Confirmada'])
                    ->where('fecha_cita', '>=', \Carbon\Carbon::today())
                    ->count(),
                'citas_este_mes' => \App\Models\Cita::whereMonth('fecha_cita', \Carbon\Carbon::now()->month)
                    ->whereYear('fecha_cita', \Carbon\Carbon::now()->year)
                    ->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);
        });

        Route::get('/usuarios', function (Request $request) {
            $usuarios = \App\Models\Usuario::with(['medico', 'paciente'])
                ->when($request->get('rol'), function ($query, $rol) {
                    return $query->where('rol', $rol);
                })
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $usuarios->map(function ($usuario) {
                    return [
                        'id' => $usuario->Id_usuario,
                        'username' => $usuario->username,
                        'email' => $usuario->email,
                        'rol' => $usuario->rol,
                        'perfil' => $usuario->perfil ? [
                            'nombre_completo' => $usuario->rol === 'medico' 
                                ? $usuario->medico->nombre_completo 
                                : $usuario->paciente->nombre_completo
                        ] : null
                    ];
                }),
                'pagination' => [
                    'current_page' => $usuarios->currentPage(),
                    'total_pages' => $usuarios->lastPage(),
                    'total' => $usuarios->total()
                ]
            ]);
        });

        Route::get('/reportes/citas', function (Request $request) {
            $fechaInicio = $request->get('fecha_inicio', \Carbon\Carbon::now()->startOfMonth());
            $fechaFin = $request->get('fecha_fin', \Carbon\Carbon::now()->endOfMonth());

            $reportes = [
                'total_citas' => \App\Models\Cita::whereBetween('fecha_cita', [$fechaInicio, $fechaFin])->count(),
                'citas_por_estado' => \App\Models\Cita::whereBetween('fecha_cita', [$fechaInicio, $fechaFin])
                    ->selectRaw('estado_cita, COUNT(*) as total')
                    ->groupBy('estado_cita')
                    ->get(),
                'citas_por_especialidad' => \App\Models\Cita::with(['medico.especialidad'])
                    ->whereBetween('fecha_cita', [$fechaInicio, $fechaFin])
                    ->get()
                    ->groupBy(function ($cita) {
                        return $cita->medico->especialidad ? $cita->medico->especialidad->nombre_especialidad : 'Sin especialidad';
                    })
                    ->map(function ($group, $especialidad) {
                        return [
                            'especialidad' => $especialidad,
                            'total' => $group->count()
                        ];
                    })
                    ->values()
            ];

            return response()->json([
                'success' => true,
                'data' => $reportes,
                'periodo' => [
                    'desde' => $fechaInicio,
                    'hasta' => $fechaFin
                ]
            ]);
        });
    });

    // Rutas para medicaciones (accesible para médicos y admin)
    Route::middleware(['jwt.auth:medico,admin'])->prefix('medicaciones')->group(function () {
        Route::get('/', function (Request $request) {
            $query = \App\Models\Medicacion::query();
            
            if ($request->has('buscar')) {
                $query->buscar($request->get('buscar'));
            }

            $medicaciones = $query->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $medicaciones
            ]);
        });

        Route::post('/', function (Request $request) {
            $request->validate([
                'nombre_comercial' => 'required|string|max:255',
                'nombre_generico' => 'nullable|string|max:255',
                'presentacion' => 'nullable|string|max:100',
                'dosis_estandar' => 'nullable|string|max:100',
                'fabricante' => 'nullable|string|max:255',
                'descripcion' => 'nullable|string'
            ]);

            $medicacion = \App\Models\Medicacion::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Medicación creada exitosamente',
                'data' => $medicacion
            ], 201);
        });
    });
});

// Ruta para manejar rutas no encontradas
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada'
    ], 404);
});
