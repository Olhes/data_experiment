<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Medico;
use App\Models\Paciente;
use App\Services\JWTService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            echo('Intento de login para: '.$request->username); // Log para depuración

            // Buscar usuario (primero sin relaciones)
            $usuario = Usuario::where('username', $request->username)->first();

            if (!$usuario) {
                echo('Usuario no encontrado: '.$request->username);
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales inválidas (usuario)'
                ], 401);
            }
            echo("Contraseña a comprobar: " .$usuario->getAuthPassword());
            echo("Contraseña ingresada: " .$request->password);
            echo("Comprobacion sale:  " .Hash::check($request->password, $usuario->getAuthPassword()));
            // Verificar contraseña con hash
            if (!Hash::check($request->password, $usuario->getAuthPassword())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales inválidas (contraseña)'
                ], 401);
            }

            echo('Login exitoso para: '.$usuario->id_usuario);

            // Ahora cargar relaciones solo si es necesario
            $usuario->load(['medico.especialidad', 'paciente']);

            // Generar token
            $token = $this->jwtService->generateToken($usuario);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $usuario->id_usuario, // Asegúrate que el caso coincide (Id_usuario vs id_usuario)
                        'username' => $usuario->username,
                        'email' => $usuario->email,
                        'rol' => $usuario->rol,
                        'perfil' => $this->formatPerfil($usuario)
                    ]
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            echo('Error en login: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Registro de nuevo usuario
     */
    public function register(Request $request)
    {
        try {
            $validationRules = [
                'username' => 'required|string|unique:usuario,username',
                'email' => 'required|email|unique:usuario,email',
                'password' => 'required|string|min:6',
                'rol' => 'required|in:medico,paciente,admin',
            ];

            // Validaciones específicas según el rol
            if ($request->rol === 'medico') {
                $validationRules = array_merge($validationRules, [
                    'nombre' => 'required|string|max:100',
                    'apellido' => 'required|string|max:100',
                    'licencia_medica' => 'required|string|unique:medico,licencia_medica',
                    'telefono_consultorio' => 'nullable|string|max:20',
                    'email_profesional' => 'nullable|email|unique:medico,email_profesional',
                    'id_especialidad' => 'nullable|exists:especialidad,id_especialidad',
                    'disponibilidad' => 'nullable|string'
                ]);
            } elseif ($request->rol === 'paciente') {
                $validationRules = array_merge($validationRules, [
                    'nombre' => 'required|string|max:100',
                    'apellido' => 'required|string|max:100',
                    'fecha_nacimiento' => 'nullable|date',
                    'telefono' => 'nullable|string|max:20',
                    'dni' => 'nullable|string|unique:paciente,dni',
                    'genero' => 'nullable|in:M,F,Otro',
                    'direccion' => 'nullable|string|max:255'
                ]);
            }

            $request->validate($validationRules);

            DB::beginTransaction();

            // Crear usuario principal
            $usuario = Usuario::create([
                'username' => $request->username,
                'password_hash' => Hash::make($request->password),
                'email' => $request->email,
                'rol' => $request->rol
            ]);

            // Crear perfil específico según el rol
            if ($request->rol === 'medico') {
                Medico::create([
                    'Id_usuario' => $usuario->Id_usuario,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'licencia_medica' => $request->licencia_medica,
                    'telefono_consultorio' => $request->telefono_consultorio,
                    'email_profesional' => $request->email_profesional,
                    'disponibilidad' => $request->disponibilidad,
                    'Id_especialidad' => $request->id_especialidad
                ]);
            } elseif ($request->rol === 'paciente') {
                Paciente::create([
                    'Id_usuario' => $usuario->Id_usuario,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'telefono' => $request->telefono,
                    'dni' => $request->dni,
                    'genero' => $request->genero,
                    'direccion' => $request->direccion
                ]);
            }

            DB::commit();

            // Recargar usuario con relaciones
            $usuario->load(['medico.especialidad', 'paciente']);

            // Generar token
            $token = $this->jwtService->generateToken($usuario);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $usuario->Id_usuario,
                        'username' => $usuario->username,
                        'email' => $usuario->email,
                        'rol' => $usuario->rol,
                        'perfil' => $this->formatPerfil($usuario)
                    ]
                ]
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function me(Request $request)
    {
        try {
            $usuario = $request->attributes->get('current_user');
            $usuario->load(['medico.especialidad', 'paciente']);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $usuario->Id_usuario,
                        'username' => $usuario->username,
                        'email' => $usuario->email,
                        'rol' => $usuario->rol,
                        'perfil' => $this->formatPerfil($usuario)
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del usuario'
            ], 500);
        }
    }

    /**
     * Refrescar token JWT
     */
    public function refresh(Request $request)
    {
        try {
            $currentToken = $request->attributes->get('jwt_token');
            $newToken = $this->jwtService->refreshToken($currentToken);

            return response()->json([
                'success' => true,
                'message' => 'Token actualizado exitosamente',
                'data' => [
                    'token' => $newToken
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al refrescar token: ' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Logout (invalidar token - opcional, JWT es stateless)
     */
    public function logout(Request $request)
    {
        // En JWT puro no hay logout real porque es stateless
        // Pero podemos responder que fue exitoso
        // En producción podrías implementar una blacklist de tokens

        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ]);
    }

    /**
     * Formatear información del perfil según el rol
     */
    private function formatPerfil($usuario)
    {
        switch ($usuario->rol) {
            case 'medico':
                if ($usuario->medico) {
                    return [
                        'tipo' => 'medico',
                        'id' => $usuario->medico->Id_medico,
                        'nombre_completo' => $usuario->medico->nombre_completo,
                        'licencia_medica' => $usuario->medico->licencia_medica,
                        'email_profesional' => $usuario->medico->email_profesional,
                        'telefono_consultorio' => $usuario->medico->telefono_consultorio,
                        'especialidad' => $usuario->medico->especialidad ? [
                            'id' => $usuario->medico->especialidad->id_especialidad,
                            'nombre' => $usuario->medico->especialidad->nombre_especialidad,
                            'descripcion' => $usuario->medico->especialidad->descripcion
                        ] : null,
                        'disponibilidad' => $usuario->medico->disponibilidad
                    ];
                }
                break;

            case 'paciente':
                if ($usuario->paciente) {
                    return [
                        'tipo' => 'paciente',
                        'id' => $usuario->paciente->Id_paciente,
                        'nombre_completo' => $usuario->paciente->nombre_completo,
                        'dni' => $usuario->paciente->dni,
                        'telefono' => $usuario->paciente->telefono,
                        'fecha_nacimiento' => $usuario->paciente->fecha_nacimiento,
                        'edad' => $usuario->paciente->edad,
                        'genero' => $usuario->paciente->genero,
                        'direccion' => $usuario->paciente->direccion
                    ];
                }
                break;

            case 'admin':
                return [
                    'tipo' => 'admin',
                    'permisos' => ['administrar_sistema', 'gestionar_usuarios', 'reportes']
                ];
        }

        return null;
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed'
            ]);

            $usuario = $request->attributes->get('current_user');

            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $usuario->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 400);
            }

            // Actualizar contraseña
            $usuario->update([
                'password_hash' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
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
                'message' => 'Error al cambiar contraseña'
            ], 500);
        }
    }
}
