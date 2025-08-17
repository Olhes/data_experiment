<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidad;
use App\Models\Cita;
use App\Models\Medicacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClinicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Especialidades
        $especialidades = [
            ['nombre_especialidad' => 'Medicina General', 'descripcion' => 'Atención médica general y preventiva'],
            ['nombre_especialidad' => 'Cardiología', 'descripcion' => 'Especialidad en enfermedades del corazón'],
            ['nombre_especialidad' => 'Dermatología', 'descripcion' => 'Especialidad en enfermedades de la piel'],
            ['nombre_especialidad' => 'Pediatría', 'descripcion' => 'Especialidad en medicina infantil'],
            ['nombre_especialidad' => 'Ginecología', 'descripcion' => 'Especialidad en salud femenina'],
        ];

        foreach ($especialidades as $especialidad) {
            Especialidad::create($especialidad);
        }

        // 2. Crear Usuario Admin
        $adminUser = Usuario::create([
            'username' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'email' => 'admin@clinica.com',
            'rol' => 'admin'
        ]);

        // 3. Crear Médicos
        $medicos = [
            [
                'usuario' => [
                    'username' => 'dr_martinez',
                    'password_hash' => Hash::make('medico123'),
                    'email' => 'martinez@clinica.com',
                    'rol' => 'medico'
                ],
                'perfil' => [
                    'nombre' => 'Carlos',
                    'apellido' => 'Martínez',
                    'licencia_medica' => 'LM001234',
                    'telefono_consultorio' => '555-0101',
                    'email_profesional' => 'carlos.martinez@clinica.com',
                    'Id_especialidad' => 1, // Medicina General
                    'disponibilidad' => 'Lunes a Viernes: 8:00 - 17:00'
                ]
            ],
            [
                'usuario' => [
                    'username' => 'dra_lopez',
                    'password_hash' => Hash::make('medico123'),
                    'email' => 'lopez@clinica.com',
                    'rol' => 'medico'
                ],
                'perfil' => [
                    'nombre' => 'María',
                    'apellido' => 'López',
                    'licencia_medica' => 'LM001235',
                    'telefono_consultorio' => '555-0102',
                    'email_profesional' => 'maria.lopez@clinica.com',
                    'Id_especialidad' => 2, // Cardiología
                    'disponibilidad' => 'Lunes, Miércoles, Viernes: 9:00 - 16:00'
                ]
            ],
            [
                'usuario' => [
                    'username' => 'dr_garcia',
                    'password_hash' => Hash::make('medico123'),
                    'email' => 'garcia@clinica.com',
                    'rol' => 'medico'
                ],
                'perfil' => [
                    'nombre' => 'José',
                    'apellido' => 'García',
                    'licencia_medica' => 'LM001236',
                    'telefono_consultorio' => '555-0103',
                    'email_profesional' => 'jose.garcia@clinica.com',
                    'Id_especialidad' => 4, // Pediatría
                    'disponibilidad' => 'Martes a Sábado: 8:30 - 15:30'
                ]
            ]
        ];

        foreach ($medicos as $medicoData) {
            $usuario = Usuario::create($medicoData['usuario']);
            Medico::create(array_merge($medicoData['perfil'], ['Id_usuario' => $usuario->Id_usuario]));
        }

        // 4. Crear Pacientes
        $pacientes = [
            [
                'usuario' => [
                    'username' => 'juan_perez',
                    'password_hash' => Hash::make('paciente123'),
                    'email' => 'juan.perez@email.com',
                    'rol' => 'paciente'
                ],
                'perfil' => [
                    'nombre' => 'Juan',
                    'apellido' => 'Pérez',
                    'fecha_nacimiento' => '1985-03-15',
                    'telefono' => '555-1001',
                    'dni' => '12345678',
                    'genero' => 'M',
                    'direccion' => 'Calle Principal 123, Lima'
                ]
            ],
            [
                'usuario' => [
                    'username' => 'ana_rodriguez',
                    'password_hash' => Hash::make('paciente123'),
                    'email' => 'ana.rodriguez@email.com',
                    'rol' => 'paciente'
                ],
                'perfil' => [
                    'nombre' => 'Ana',
                    'apellido' => 'Rodríguez',
                    'fecha_nacimiento' => '1990-07-22',
                    'telefono' => '555-1002',
                    'dni' => '87654321',
                    'genero' => 'F',
                    'direccion' => 'Avenida Lima 456, Lima'
                ]
            ],
            [
                'usuario' => [
                    'username' => 'luis_torres',
                    'password_hash' => Hash::make('paciente123'),
                    'email' => 'luis.torres@email.com',
                    'rol' => 'paciente'
                ],
                'perfil' => [
                    'nombre' => 'Luis',
                    'apellido' => 'Torres',
                    'fecha_nacimiento' => '1978-12-08',
                    'telefono' => '555-1003',
                    'dni' => '11223344',
                    'genero' => 'M',
                    'direccion' => 'Jirón Unión 789, Lima'
                ]
            ],
            [
                'usuario' => [
                    'username' => 'maria_silva',
                    'password_hash' => Hash::make('paciente123'),
                    'email' => 'maria.silva@email.com',
                    'rol' => 'paciente'
                ],
                'perfil' => [
                    'nombre' => 'María',
                    'apellido' => 'Silva',
                    'fecha_nacimiento' => '1995-05-30',
                    'telefono' => '555-1004',
                    'dni' => '55667788',
                    'genero' => 'F',
                    'direccion' => 'Calle San Martín 321, Lima'
                ]
            ]
        ];

        foreach ($pacientes as $pacienteData) {
            $usuario = Usuario::create($pacienteData['usuario']);
            Paciente::create(array_merge($pacienteData['perfil'], ['Id_usuario' => $usuario->Id_usuario]));
        }

        // 5. Crear Citas de Ejemplo
        $citas = [
            [
                'Id_paciente' => 1,
                'Id_medico' => 1,
                'fecha_cita' => Carbon::today()->addDays(1),
                'hora_cita' => '09:00:00',
                'duracion_minutos' => 30,
                'motivo_consulta' => 'Consulta general de rutina',
                'estado_cita' => 'Programada'
            ],
            [
                'Id_paciente' => 2,
                'Id_medico' => 2,
                'fecha_cita' => Carbon::today()->addDays(2),
                'hora_cita' => '10:30:00',
                'duracion_minutos' => 45,
                'motivo_consulta' => 'Revisión cardiológica',
                'estado_cita' => 'Confirmada'
            ],
            [
                'Id_paciente' => 1,
                'Id_medico' => 1,
                'fecha_cita' => Carbon::today()->subDays(5),
                'hora_cita' => '14:00:00',
                'duracion_minutos' => 30,
                'motivo_consulta' => 'Dolor de cabeza frecuente',
                'notas_medicas' => 'Paciente presenta cefalea tensional. Se recomienda descanso y analgésicos.',
                'estado_cita' => 'Completada'
            ],
            [
                'Id_paciente' => 3,
                'Id_medico' => 3,
                'fecha_cita' => Carbon::today()->addDays(3),
                'hora_cita' => '11:00:00',
                'duracion_minutos' => 30,
                'motivo_consulta' => 'Control pediátrico del niño',
                'estado_cita' => 'Programada'
            ],
            [
                'Id_paciente' => 4,
                'Id_medico' => 1,
                'fecha_cita' => Carbon::today(),
                'hora_cita' => '15:30:00',
                'duracion_minutos' => 30,
                'motivo_consulta' => 'Consulta por síntomas gripales',
                'estado_cita' => 'Confirmada'
            ]
        ];

        foreach ($citas as $cita) {
            Cita::create($cita);
        }

        // 6. Crear Medicaciones de Ejemplo
        $medicaciones = [
            [
                'nombre_comercial' => 'Paracetamol 500mg',
                'nombre_generico' => 'Paracetamol',
                'presentacion' => 'Tabletas',
                'dosis_estandar' => '500mg cada 8 horas',
                'fabricante' => 'Laboratorios ABC',
                'descripcion' => 'Analgésico y antipirético'
            ],
            [
                'nombre_comercial' => 'Ibuprofeno 400mg',
                'nombre_generico' => 'Ibuprofeno',
                'presentacion' => 'Cápsulas',
                'dosis_estandar' => '400mg cada 12 horas',
                'fabricante' => 'Pharma XYZ',
                'descripcion' => 'Antiinflamatorio no esteroideo'
            ],
            [
                'nombre_comercial' => 'Amoxicilina 500mg',
                'nombre_generico' => 'Amoxicilina',
                'presentacion' => 'Cápsulas',
                'dosis_estandar' => '500mg cada 8 horas',
                'fabricante' => 'Antibióticos SA',
                'descripcion' => 'Antibiótico de amplio espectro'
            ],
            [
                'nombre_comercial' => 'Losartán 50mg',
                'nombre_generico' => 'Losartán Potásico',
                'presentacion' => 'Tabletas',
                'dosis_estandar' => '50mg una vez al día',
                'fabricante' => 'CardioMed',
                'descripcion' => 'Antihipertensivo inhibidor de la enzima convertidora de angiotensina'
            ]
        ];

        foreach ($medicaciones as $medicacion) {
            Medicacion::create($medicacion);
        }

        $this->command->info('✅ Datos de prueba creados exitosamente:');
        $this->command->info('👤 Admin: admin / admin123');
        $this->command->info('👨‍⚕️ Médicos: dr_martinez, dra_lopez, dr_garcia / medico123');
        $this->command->info('👥 Pacientes: juan_perez, ana_rodriguez, luis_torres, maria_silva / paciente123');
    }
}
