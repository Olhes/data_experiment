<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use App\Models\Usuario;
use Exception;

class JWTService
{
    private $secretKey;
    private $algorithm;
    private $expirationTime;

    public function __construct()
    {
        $this->secretKey = config('app.key'); // Usa la clave de tu app
        $this->algorithm = 'HS256';
        $this->expirationTime = 60 * 60 * 24; // 24 horas
    }

    /**
     * Generar token JWT para un usuario
     */
    public function generateToken(Usuario $usuario)
    {
        $payload = [
            'iss' => config('app.url'), // Emisor
            'sub' => $usuario->Id_usuario, // Sujeto (ID del usuario)
            'iat' => time(), // Tiempo de emisión
            'exp' => time() + $this->expirationTime, // Tiempo de expiración
            'data' => [
                'user_id' => $usuario->Id_usuario,
                'username' => $usuario->username,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
                'perfil_id' => $this->getPerfilId($usuario),
                'perfil_data' => $this->getPerfilData($usuario)
            ]
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validar y decodificar token JWT
     */
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            throw new Exception('Token expirado');
        } catch (SignatureInvalidException $e) {
            throw new Exception('Firma del token inválida');
        } catch (Exception $e) {
            throw new Exception('Token inválido: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos del usuario desde el token
     */
    public function getUserFromToken($token)
    {
        try {
            $decoded = $this->validateToken($token);
            $userData = (array) $decoded['data'];
            
            // Buscar el usuario en la base de datos
            $usuario = Usuario::find($userData['user_id']);
            
            if (!$usuario) {
                throw new Exception('Usuario no encontrado');
            }

            return $usuario;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Refrescar token (generar uno nuevo)
     */
    public function refreshToken($token)
    {
        try {
            $usuario = $this->getUserFromToken($token);
            return $this->generateToken($usuario);
        } catch (Exception $e) {
            throw new Exception('No se pudo refrescar el token: ' . $e->getMessage());
        }
    }

    /**
     * Obtener ID del perfil según el rol
     */
    private function getPerfilId(Usuario $usuario)
    {
        switch ($usuario->rol) {
            case 'medico':
                return $usuario->medico ? $usuario->medico->Id_medico : null;
            case 'paciente':
                return $usuario->paciente ? $usuario->paciente->Id_paciente : null;
            default:
                return null;
        }
    }

    /**
     * Obtener datos del perfil según el rol
     */
    private function getPerfilData(Usuario $usuario)
    {
        switch ($usuario->rol) {
            case 'medico':
                if ($usuario->medico) {
                    return [
                        'id' => $usuario->medico->Id_medico,
                        'nombre_completo' => $usuario->medico->nombre_completo,
                        'licencia_medica' => $usuario->medico->licencia_medica,
                        'especialidad' => $usuario->medico->especialidad ? $usuario->medico->especialidad->nombre_especialidad : null,
                        'email_profesional' => $usuario->medico->email_profesional,
                        'telefono_consultorio' => $usuario->medico->telefono_consultorio
                    ];
                }
                break;
            case 'paciente':
                if ($usuario->paciente) {
                    return [
                        'id' => $usuario->paciente->Id_paciente,
                        'nombre_completo' => $usuario->paciente->nombre_completo,
                        'dni' => $usuario->paciente->dni,
                        'telefono' => $usuario->paciente->telefono,
                        'fecha_nacimiento' => $usuario->paciente->fecha_nacimiento,
                        'edad' => $usuario->paciente->edad
                    ];
                }
                break;
        }
        return null;
    }

    /**
     * Verificar si el token está cerca de expirar (útil para refresh automático)
     */
    public function isTokenNearExpiration($token, $threshold = 300) // 5 minutos por defecto
    {
        try {
            $decoded = $this->validateToken($token);
            $exp = $decoded['exp'];
            $timeLeft = $exp - time();
            
            return $timeLeft <= $threshold;
        } catch (Exception $e) {
            return true; // Si hay error, consideramos que necesita renovación
        }
    }

    /**
     * Extraer datos del token sin validar (útil para debugging)
     */
    public function decodeTokenWithoutValidation($token)
    {
        try {
            list($header, $payload, $signature) = explode('.', $token);
            $decodedPayload = json_decode(base64_decode($payload), true);
            return $decodedPayload;
        } catch (Exception $e) {
            throw new Exception('No se pudo decodificar el token');
        }
    }
}
