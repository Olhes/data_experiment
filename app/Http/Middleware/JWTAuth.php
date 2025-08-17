<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JWTService;
use Exception;

class JWTAuth
{
    protected $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            // Obtener token del header Authorization
            $token = $this->getTokenFromRequest($request);
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token no proporcionado'
                ], 401);
            }

            // Validar token y obtener usuario
            $usuario = $this->jwtService->getUserFromToken($token);
            
            // Verificar si el usuario tiene el rol requerido
            if (!empty($roles) && !in_array($usuario->rol, $roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para acceder a este recurso'
                ], 403);
            }

            // Agregar usuario a la request para uso posterior
            $request->attributes->set('current_user', $usuario);
            $request->attributes->set('jwt_token', $token);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }

    /**
     * Extraer token del header Authorization
     */
    private function getTokenFromRequest(Request $request)
    {
        $header = $request->header('Authorization');
        
        if (!$header) {
            return null;
        }

        // Formato esperado: "Bearer {token}"
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
