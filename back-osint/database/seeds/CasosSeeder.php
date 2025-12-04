<?php

use Illuminate\Database\Seeder;

class CasosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $casos = [
            [
                'estado' => 'activo',
                'nombre' => 'Investigación Fraude Financiero',
                'tipo_caso' => 'fraude',
                'descripcion' => 'Investigación de posible fraude en transacciones financieras online. Se requiere análisis de cuentas bancarias y movimientos sospechosos.',
                'id_creador' => 1,
                'fecha_creacion' => now()->subDays(10)
            ],
            [
                'estado' => 'en_progreso',
                'nombre' => 'Búsqueda Persona Desaparecida',
                'tipo_caso' => 'persona_desaparecida',
                'descripcion' => 'Localización de persona reportada como desaparecida. Última actividad en redes sociales hace 5 días.',
                'id_creador' => 2,
                'fecha_creacion' => now()->subDays(5)
            ],
            [
                'estado' => 'cerrado',
                'nombre' => 'Análisis Amenazas Cibernéticas',
                'tipo_caso' => 'ciberseguridad',
                'descripcion' => 'Investigación de amenazas dirigidas contra infraestructura crítica. Caso resuelto exitosamente.',
                'id_creador' => 3,
                'fecha_creacion' => now()->subDays(30),
                'fecha_actualizacion' => now()->subDays(2)
            ],
            [
                'estado' => 'activo',
                'nombre' => 'Verificación Identidad Digital',
                'tipo_caso' => 'verificacion',
                'descripcion' => 'Verificación de identidad de usuario sospechoso en plataformas digitales. Posible suplantación de identidad.',
                'id_creador' => 4,
                'fecha_creacion' => now()->subDays(3)
            ],
            [
                'estado' => 'pausado',
                'nombre' => 'Monitoreo Dark Web',
                'tipo_caso' => 'monitoreo',
                'descripcion' => 'Monitoreo continuo de actividades sospechosas en la dark web relacionadas con venta de datos personales.',
                'id_creador' => 1,
                'fecha_creacion' => now()->subDays(20)
            ]
        ];

        foreach ($casos as $caso) {
            DB::table('casos')->insert($caso);
        }

        // Crear asignaciones de casos
        $asignaciones = [
            ['id_caso' => 1, 'id_usuario' => 2, 'fecha_asignacion' => now()->subDays(9)],
            ['id_caso' => 1, 'id_usuario' => 3, 'fecha_asignacion' => now()->subDays(8)],
            ['id_caso' => 2, 'id_usuario' => 4, 'fecha_asignacion' => now()->subDays(4)],
            ['id_caso' => 3, 'id_usuario' => 2, 'fecha_asignacion' => now()->subDays(29)],
            ['id_caso' => 4, 'id_usuario' => 3, 'fecha_asignacion' => now()->subDays(2)],
            ['id_caso' => 5, 'id_usuario' => 4, 'fecha_asignacion' => now()->subDays(19)]
        ];

        foreach ($asignaciones as $asignacion) {
            DB::table('asignaciones_casos')->insert($asignacion);
        }

        // Crear evidencias
        $evidencias = [
            [
                'id_caso' => 1,
                'tipo' => 'documento',
                'descripcion' => 'Estados de cuenta bancarios sospechosos - /evidencias/caso1/estados_cuenta.pdf',
                'fecha_creacion' => now()->subDays(8)
            ],
            [
                'id_caso' => 1,
                'tipo' => 'imagen',
                'descripcion' => 'Captura de pantalla de transacción fraudulenta - /evidencias/caso1/transaccion_fraude.png',
                'fecha_creacion' => now()->subDays(7)
            ],
            [
                'id_caso' => 2,
                'tipo' => 'enlace',
                'descripcion' => 'Perfil de redes sociales de la persona desaparecida - https://facebook.com/profile/12345',
                'fecha_creacion' => now()->subDays(4)
            ],
            [
                'id_caso' => 4,
                'tipo' => 'documento',
                'descripcion' => 'Análisis de metadatos de imágenes del perfil - /evidencias/caso4/analisis_metadatos.txt',
                'fecha_creacion' => now()->subDays(1)
            ]
        ];

        foreach ($evidencias as $evidencia) {
            DB::table('evidencias')->insert($evidencia);
        }

        // Crear actividades de casos
        $actividades = [
            [
                'id_caso' => 1,
                'actividad' => 'Inicio de investigación - Recopilación de información inicial',
                'fecha' => now()->subDays(9)
            ],
            [
                'id_caso' => 1,
                'actividad' => 'Análisis de transacciones bancarias completado',
                'fecha' => now()->subDays(6)
            ],
            [
                'id_caso' => 2,
                'actividad' => 'Búsqueda en redes sociales iniciada',
                'fecha' => now()->subDays(4)
            ],
            [
                'id_caso' => 2,
                'actividad' => 'Contacto con familiares establecido',
                'fecha' => now()->subDays(3)
            ],
            [
                'id_caso' => 4,
                'actividad' => 'Verificación de identidad en proceso',
                'fecha' => now()->subDays(2)
            ]
        ];

        foreach ($actividades as $actividad) {
            DB::table('actividades_caso')->insert($actividad);
        }

        // Crear logs de actividad
        $logs = [
            [
                'id_usuario' => 1,
                'tipo_accion' => 'crear_caso',
                'descripcion' => 'Caso de fraude financiero creado',
                'caso_id_relacionado' => 1,
                'fecha_hora' => now()->subDays(10)
            ],
            [
                'id_usuario' => 2,
                'tipo_accion' => 'asignar_caso',
                'descripcion' => 'Usuario asignado al caso de fraude',
                'caso_id_relacionado' => 1,
                'fecha_hora' => now()->subDays(9)
            ],
            [
                'id_usuario' => 3,
                'tipo_accion' => 'agregar_evidencia',
                'descripcion' => 'Evidencia agregada al caso',
                'caso_id_relacionado' => 1,
                'fecha_hora' => now()->subDays(8)
            ],
            [
                'id_usuario' => 2,
                'tipo_accion' => 'crear_caso',
                'descripcion' => 'Caso de persona desaparecida creado',
                'caso_id_relacionado' => 2,
                'fecha_hora' => now()->subDays(5)
            ],
            [
                'id_usuario' => 4,
                'tipo_accion' => 'actualizar_caso',
                'descripcion' => 'Estado del caso actualizado',
                'caso_id_relacionado' => 4,
                'fecha_hora' => now()->subDays(1)
            ]
        ];

        foreach ($logs as $log) {
            DB::table('log_actividad')->insert($log);
        }
    }
}
