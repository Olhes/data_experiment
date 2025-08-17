# 🏥 Sistema de Clínica con Autenticación JWT

## 📋 Descripción
Sistema completo de gestión de citas médicas con autenticación JWT implementado en Laravel 12.

## 🚀 Instalación Rápida

### 1. Instalar Dependencias JWT
```bash
cd C:\Users\Lenovo\PhpstormProjects\data_experiment
composer require firebase/php-jwt
```

### 2. Ejecutar Migraciones y Seeders
```bash
php artisan migrate
php artisan db:seed --class=ClinicaSeeder
```

### 3. Iniciar Servidor
```bash
php artisan serve
```

## 🔐 Autenticación JWT - Cómo Funciona

### 📝 **Flujo de Login**
```
1. Usuario envía credenciales → POST /api/v1/auth/login
2. Sistema valida credenciales
3. Si son correctas: genera JWT token
4. Cliente guarda el token
5. Cliente envía token en cada petición: Authorization: Bearer {token}
```

### 🎯 **Estructura del Token JWT**
```json
{
  "user_id": 1,
  "username": "dr_martinez",
  "email": "martinez@clinica.com",
  "rol": "medico",
  "perfil_id": 1,
  "perfil_data": {
    "id": 1,
    "nombre_completo": "Carlos Martínez",
    "especialidad": "Medicina General"
  },
  "exp": 1692284400
}
```

## 📚 API Endpoints

### 🔓 **Endpoints Públicos**
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/v1/auth/login` | Login de usuario |
| POST | `/api/v1/auth/register` | Registro de nuevo usuario |
| GET | `/api/v1/test` | Test de la API |
| GET | `/api/v1/especialidades` | Lista de especialidades |

### 🔒 **Endpoints Protegidos (requieren JWT)**

#### Autenticación
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/auth/me` | Información del usuario actual |
| POST | `/api/v1/auth/refresh` | Refrescar token |
| POST | `/api/v1/auth/logout` | Cerrar sesión |
| PUT | `/api/v1/auth/change-password` | Cambiar contraseña |

#### Citas (Todos los roles autenticados)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/citas` | Listar citas |
| POST | `/api/v1/citas` | Crear nueva cita |
| GET | `/api/v1/citas/hoy` | Citas de hoy |
| GET | `/api/v1/citas/{id}` | Ver cita específica |
| PUT | `/api/v1/citas/{id}` | Actualizar cita |
| PATCH | `/api/v1/citas/{id}/cancelar` | Cancelar cita |

#### Solo Médicos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/medico/agenda` | Agenda del médico |
| GET | `/api/v1/medico/estadisticas` | Estadísticas del médico |

#### Solo Pacientes
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/paciente/historial` | Historial médico |
| GET | `/api/v1/paciente/proximas-citas` | Próximas citas |

#### Solo Administradores
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/admin/dashboard` | Dashboard administrativo |
| GET | `/api/v1/admin/usuarios` | Lista de usuarios |
| GET | `/api/v1/admin/reportes/citas` | Reportes de citas |

## 👥 Usuarios de Prueba

### 👤 **Administrador**
- **Usuario:** `admin`
- **Contraseña:** `admin123`
- **Email:** `admin@clinica.com`

### 👨‍⚕️ **Médicos**
1. **Dr. Carlos Martínez** (Medicina General)
   - **Usuario:** `dr_martinez`
   - **Contraseña:** `medico123`
   - **Email:** `martinez@clinica.com`

2. **Dra. María López** (Cardiología)
   - **Usuario:** `dra_lopez`
   - **Contraseña:** `medico123`
   - **Email:** `lopez@clinica.com`

3. **Dr. José García** (Pediatría)
   - **Usuario:** `dr_garcia`
   - **Contraseña:** `medico123`
   - **Email:** `garcia@clinica.com`

### 👥 **Pacientes**
1. **Juan Pérez**
   - **Usuario:** `juan_perez`
   - **Contraseña:** `paciente123`
   - **Email:** `juan.perez@email.com`

2. **Ana Rodríguez**
   - **Usuario:** `ana_rodriguez`
   - **Contraseña:** `paciente123`
   - **Email:** `ana.rodriguez@email.com`

## 🧪 Ejemplos de Uso

### 1. **Login**
```bash
POST http://localhost:8000/api/v1/auth/login
Content-Type: application/json

{
  "username": "dr_martinez",
  "password": "medico123"
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
      "id": 2,
      "username": "dr_martinez",
      "email": "martinez@clinica.com",
      "rol": "medico",
      "perfil": {
        "tipo": "medico",
        "nombre_completo": "Carlos Martínez",
        "licencia_medica": "LM001234",
        "especialidad": {
          "id": 1,
          "nombre": "Medicina General"
        }
      }
    }
  }
}
```

### 2. **Crear Cita (como paciente)**
```bash
POST http://localhost:8000/api/v1/citas
Authorization: Bearer {tu_token_jwt}
Content-Type: application/json

{
  "Id_medico": 1,
  "fecha_cita": "2025-08-20",
  "hora_cita": "10:30",
  "duracion_minutos": 30,
  "motivo_consulta": "Consulta de rutina"
}
```

### 3. **Ver Agenda (como médico)**
```bash
GET http://localhost:8000/api/v1/medico/agenda?fecha_inicio=2025-08-17&fecha_fin=2025-08-24
Authorization: Bearer {tu_token_jwt}
```

### 4. **Historial del Paciente**
```bash
GET http://localhost:8000/api/v1/paciente/historial
Authorization: Bearer {tu_token_jwt}
```

## 🔧 Estructura de Archivos Creados

```
app/
├── Models/
│   ├── Usuario.php          # Modelo principal de usuarios
│   ├── Medico.php          # Modelo de médicos
│   ├── Paciente.php        # Modelo de pacientes
│   ├── Cita.php            # Modelo de citas
│   ├── Especialidad.php    # Modelo de especialidades
│   ├── Medicacion.php      # Modelo de medicaciones
│   └── ActividadFinanciera.php # Modelo de actividad financiera
├── Services/
│   └── JWTService.php      # Servicio para manejo de JWT
├── Http/
│   ├── Controllers/API/
│   │   ├── AuthController.php  # Controlador de autenticación
│   │   └── CitasController.php # Controlador de citas
│   └── Middleware/
│       └── JWTAuth.php     # Middleware de autenticación JWT
routes/
└── api.php                 # Rutas de la API
database/seeders/
└── ClinicaSeeder.php      # Datos de prueba
```

## 🎯 Roles y Permisos

### 👤 **Admin**
- ✅ Ver todas las citas
- ✅ Gestionar usuarios
- ✅ Ver reportes
- ✅ Dashboard completo

### 👨‍⚕️ **Médico**
- ✅ Ver sus propias citas
- ✅ Crear/editar notas médicas
- ✅ Ver agenda personal
- ✅ Gestionar medicaciones
- ✅ Ver estadísticas personales

### 👥 **Paciente**
- ✅ Ver sus propias citas
- ✅ Crear nuevas citas
- ✅ Cancelar citas futuras
- ✅ Ver historial médico
- ❌ No puede editar notas médicas

## 🚨 Características de Seguridad

1. **Tokens JWT seguros** con expiración de 24 horas
2. **Middlewares por rol** - cada endpoint protege según permisos
3. **Validación de datos** en todas las peticiones
4. **Contraseñas hasheadas** con bcrypt
5. **Verificación de permisos** a nivel de datos (usuarios solo ven sus datos)

## 🔄 Refresh de Tokens

Los tokens JWT duran 24 horas. Para renovarlos:

```bash
POST http://localhost:8000/api/v1/auth/refresh
Authorization: Bearer {tu_token_actual}
```

## 📱 Uso para Frontend/Mobile

Este sistema está listo para ser consumido por:
- ✅ **React/Vue.js** - Single Page Applications
- ✅ **Apps móviles** - iOS/Android
- ✅ **Aplicaciones de escritorio** - Electron, etc.

Solo necesitas:
1. Guardar el token JWT en localStorage/AsyncStorage
2. Enviarlo en el header `Authorization: Bearer {token}`
3. Refrescarlo antes de que expire

## 🐛 Troubleshooting

### Error: "Token no proporcionado"
- Verifica que el header `Authorization: Bearer {token}` esté presente

### Error: "Token expirado" 
- Usa el endpoint `/auth/refresh` para obtener un nuevo token

### Error: "No tienes permisos"
- Verifica que tu rol tenga acceso a ese endpoint

### Error: "Usuario no encontrado"
- El token puede estar corrupto, haz login nuevamente

## 🚀 Próximos Pasos

1. **Frontend**: Crear interfaz web con React/Vue
2. **Notificaciones**: Sistema de recordatorios de citas
3. **Reportes**: Más reportes médicos y financieros
4. **WhatsApp**: Integración para recordatorios
5. **Pagos**: Sistema de pagos en línea
