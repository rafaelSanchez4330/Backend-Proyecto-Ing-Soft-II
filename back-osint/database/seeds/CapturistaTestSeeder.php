<?php

use Illuminate\Database\Seeder;
use App\Usuario;
use App\Caso;
use App\AsignacionCaso;
use App\Evidencia;
use Illuminate\Support\Facades\Hash;

class CapturistaTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar o crear usuario mgarcia
        $usuario = Usuario::firstOrCreate(
            ['usuario' => 'mgarcia'],
            [
                'nombre' => 'María García',
                'mail' => 'mgarcia@udint.edu.mx',
                'contrasena' => Hash::make('password123'),
                'rol' => 'capturista'
            ]
        );

        echo "Usuario mgarcia creado/encontrado: ID {$usuario->id_usuario}\n";

        // Crear casos de prueba
        $casos = [
            [
                'nombre' => 'Fraude Digital E-commerce',
                'tipo_caso' => 'fraude',
                'descripcion' => 'Investigación de fraude digital en plataforma de comercio electrónico. Se detectaron múltiples transacciones sospechosas desde cuentas comprometidas.',
                'estado' => 'activo',
                'fecha_creacion' => now()->subDays(5),
            ],
            [
                'nombre' => 'Campaña de Phishing Corporativo',
                'tipo_caso' => 'phishing',
                'descripcion' => 'Análisis de phishing dirigido a empleados de empresa tecnológica. Campaña de correos electrónicos maliciosos con enlaces a sitios fraudulentos.',
                'estado' => 'en_progreso',
                'fecha_creacion' => now()->subDays(10),
            ],
            [
                'nombre' => 'Perfiles Falsos en Redes Sociales',
                'tipo_caso' => 'fraude',
                'descripcion' => 'Rastreo de actividad sospechosa en redes sociales. Perfiles falsos utilizados para difundir información falsa y realizar estafas.',
                'estado' => 'activo',
                'fecha_creacion' => now()->subDays(3),
            ],
            [
                'nombre' => 'Robo de Identidad Digital',
                'tipo_caso' => 'robo_identidad',
                'descripcion' => 'Investigación de robo de identidad digital. Víctima reporta uso no autorizado de sus datos personales en múltiples plataformas.',
                'estado' => 'en_progreso',
                'fecha_creacion' => now()->subDays(15),
            ],
            [
                'nombre' => 'Distribución de Malware',
                'tipo_caso' => 'malware',
                'descripcion' => 'Análisis de dominio sospechoso utilizado para distribución de malware. El sitio web imita servicios legítimos para engañar a usuarios.',
                'estado' => 'finalizado',
                'fecha_creacion' => now()->subDays(30),
            ],
        ];

        foreach ($casos as $casoData) {
            // Agregar id_creador al caso
            $casoData['id_creador'] = $usuario->id_usuario;
            
            $caso = Caso::create($casoData);
            
            // Asignar caso al usuario mgarcia
            AsignacionCaso::create([
                'id_caso' => $caso->id_caso,
                'id_usuario' => $usuario->id_usuario,
                'fecha_asignacion' => $casoData['fecha_creacion'],
            ]);

            echo "Caso creado y asignado: {$caso->nombre} (ID: {$caso->id_caso})\n";

            // Agregar algunas evidencias de ejemplo para los casos activos y en progreso
            if ($caso->estado !== 'finalizado') {
                $evidencias = [];
                
                if ($caso->nombre === 'Fraude Digital E-commerce') {
                    $evidencias = [
                        [
                            'tipo' => 'Captura de Pantalla',
                            'descripcion' => 'Captura de pantalla de transacciones fraudulentas realizadas el 28 de noviembre de 2024.',
                        ],
                        [
                            'tipo' => 'Registro DNS',
                            'descripcion' => 'Registros DNS del dominio sospechoso fraude-shop.com',
                        ],
                    ];
                } elseif ($caso->nombre === 'Campaña de Phishing Corporativo') {
                    $evidencias = [
                        [
                            'tipo' => 'Documento',
                            'descripcion' => 'Correo electrónico de phishing enviado a 150 empleados.',
                        ],
                    ];
                }

                foreach ($evidencias as $evidenciaData) {
                    Evidencia::create([
                        'id_caso' => $caso->id_caso,
                        'tipo' => $evidenciaData['tipo'],
                        'descripcion' => $evidenciaData['descripcion'],
                        'fecha_registro' => now()->subDays(rand(1, 5)),
                    ]);
                }

                echo "  - Agregadas " . count($evidencias) . " evidencias\n";
            }
        }

        echo "\n✓ Seeder completado exitosamente!\n";
    }
}
