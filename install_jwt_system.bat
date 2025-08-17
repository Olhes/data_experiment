@echo off
echo ========================================
echo  INSTALADOR AUTOMATICO JWT CLINICA
echo ========================================
echo.

cd /d "C:\Users\Lenovo\PhpstormProjects\data_experiment"

echo [1/5] Instalando Firebase JWT...
composer require firebase/php-jwt
if %errorlevel% neq 0 (
    echo ERROR: No se pudo instalar Firebase JWT
    pause
    exit /b 1
)

echo [2/5] Ejecutando migraciones...
php artisan migrate
if %errorlevel% neq 0 (
    echo ERROR: Fallo en las migraciones
    pause
    exit /b 1
)

echo [3/5] Ejecutando seeders (datos de prueba)...
php artisan db:seed --class=ClinicaSeeder
if %errorlevel% neq 0 (
    echo ERROR: Fallo en los seeders
    pause
    exit /b 1
)

echo [4/5] Limpiando cache...
php artisan config:clear
php artisan route:clear
php artisan cache:clear

echo [5/5] Verificando instalacion...
php artisan route:list | findstr "api/v1"

echo.
echo ========================================
echo      INSTALACION COMPLETADA
echo ========================================
echo.
echo ✅ JWT System instalado correctamente
echo ✅ Base de datos configurada
echo ✅ Datos de prueba creados
echo.
echo 👤 USUARIOS DE PRUEBA:
echo    Admin: admin / admin123
echo    Medico: dr_martinez / medico123
echo    Paciente: juan_perez / paciente123
echo.
echo 🚀 Para iniciar el servidor:
echo    php artisan serve
echo.
echo 📚 Ver documentacion completa:
echo    JWT_SYSTEM_DOCS.md
echo.
echo 🔗 API Base URL: http://localhost:8000/api/v1
echo.
pause
