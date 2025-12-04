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
                'codigo_caso' => 'CASO-2025-001',
                'descripcion' => 'Investigación de fraude digital en plataforma de comercio electrónico. Se detectaron múltiples transacciones sospechosas desde cuentas comprometidas.',
                'estado' => 'activo',
                'prioridad' => 'alta',
                'fecha_creacion' => now()->subDays(5),
            ],
            [
                'nombre' => 'Campaña de Phishing Corporativo',
                'tipo_caso' => 'phishing',
                'codigo_caso' => 'CASO-2025-002',
                'descripcion' => 'Análisis de phishing dirigido a empleados de empresa tecnológica. Campaña de correos electrónicos maliciosos con enlaces a sitios fraudulentos.',
                'estado' => 'en_progreso',
                'prioridad' => 'media',
                'fecha_creacion' => now()->subDays(10),
            ],
            [
                'nombre' => 'Perfiles Falsos en Redes Sociales',
                'tipo_caso' => 'fraude',
                'codigo_caso' => 'CASO-2025-003',
                'descripcion' => 'Rastreo de actividad sospechosa en redes sociales. Perfiles falsos utilizados para difundir información falsa y realizar estafas.',
                'estado' => 'activo',
                'prioridad' => 'alta',
                'fecha_creacion' => now()->subDays(3),
            ],
            [
                'nombre' => 'Robo de Identidad Digital',
                'tipo_caso' => 'robo_identidad',
                'codigo_caso' => 'CASO-2024-089',
                'descripcion' => 'Investigación de robo de identidad digital. Víctima reporta uso no autorizado de sus datos personales en múltiples plataformas.',
                'estado' => 'en_progreso',
                'prioridad' => 'media',
                'fecha_creacion' => now()->subDays(15),
            ],
            [
                'nombre' => 'Distribución de Malware',
                'tipo_caso' => 'malware',
                'codigo_caso' => 'CASO-2024-075',
                'descripcion' => 'Análisis de dominio sospechoso utilizado para distribución de malware. El sitio web imita servicios legítimos para engañar a usuarios.',
                'estado' => 'finalizado',
                'prioridad' => 'baja',
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

            echo "Caso creado y asignado: {$caso->codigo_caso} (ID: {$caso->id})\n";

            // Agregar algunas evidencias de ejemplo para los casos activos y en progreso
            if ($caso->estado !== 'finalizado') {
                $evidencias = [];
                
                if ($caso->codigo_caso === 'CASO-2025-001') {
                    $evidencias = [
                        [
                            'tipo' => 'Captura de Pantalla',
                            'descripcion' => 'Captura de pantalla de transacciones fraudulentas realizadas el 28 de noviembre de 2024. Se observan 5 compras consecutivas por montos superiores a $500 USD cada una.',
                        ],
                        [
                            'tipo' => 'Registro DNS',
                            'descripcion' => 'Registros DNS del dominio sospechoso fraude-shop.com:\n\nA: 185.220.101.45\nMX: mail.fraude-shop.com\nNS: ns1.suspicious-host.net\n\nEl dominio fue registrado hace 2 semanas con información de contacto oculta.',
                        ],
                    ];
                } elseif ($caso->codigo_caso === 'CASO-2025-002') {
                    $evidencias = [
                        [
                            'tipo' => 'Documento',
                            'descripcion' => 'Correo electrónico de phishing enviado a 150 empleados. Asunto: "Actualización urgente de credenciales". El correo contiene un enlace a un sitio falso que imita el portal corporativo.',
                        ],
                        [
                            'tipo' => 'Análisis de Dominio',
                            'descripcion' => 'Análisis del dominio phishing: corporativo-login.net\n\nIP: 192.168.100.50\nRegistrador: Namecheap\nFecha de registro: 2024-11-20\nEstado: Activo\n\nEl dominio utiliza certificado SSL gratuito de Let\'s Encrypt para parecer legítimo.',
                        ],
                    ];
                } elseif ($caso->codigo_caso === 'CASO-2025-003') {
                    $evidencias = [
                        [
                            'tipo' => 'Perfil de Redes Sociales',
                            'descripcion' => 'Perfil falso de Facebook identificado: @inversiones_seguras_2024\n\nCreado: 15 de noviembre de 2024\nSeguidores: 3,500 (mayoría bots)\nPublicaciones: Promesas de inversión con retornos del 300%\n\nEl perfil utiliza fotos robadas de personas reales.',
                        ],
                    ];
                } elseif ($caso->codigo_caso === 'CASO-2024-089') {
                    $evidencias = [
                        [
                            'tipo' => 'Documento',
                            'descripcion' => 'Reporte de la víctima con capturas de pantalla de cuentas creadas sin su autorización en:\n- Amazon\n- PayPal\n- Mercado Libre\n\nTodas las cuentas fueron creadas el mismo día usando el mismo correo electrónico de la víctima.',
                        ],
                        [
                            'tipo' => 'Captura de Pantalla',
                            'descripcion' => 'Evidencia de compras realizadas con tarjeta de crédito clonada. Total de transacciones fraudulentas: $2,350 USD en un período de 48 horas.',
                        ],
                    ];
                }

                foreach ($evidencias as $evidenciaData) {
                    Evidencia::create([
                        'id_caso' => $caso->id,
                        'tipo' => $evidenciaData['tipo'],
                        'descripcion' => $evidenciaData['descripcion'],
                        'fecha_registro' => now()->subDays(rand(1, 5)),
                    ]);
                }

                echo "  - Agregadas " . count($evidencias) . " evidencias\n";
            }
        }

        echo "\n✓ Seeder completado exitosamente!\n";
        echo "✓ Usuario: mgarcia (password: password123)\n";
        echo "✓ Casos creados: " . count($casos) . "\n";
        echo "✓ Todos los casos han sido asignados a mgarcia\n";
    }
}
