<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Usuario;
use App\Caso;
use App\AsignacionCaso;
use App\Evidencia;

class ModuloCapturistaSeeder extends Seeder
{
    /**
     * Seed para el Modulo Capturista
     * Crea usuarios, casos, asignaciones y evidencias de prueba
     */
    public function run()
    {
        echo "Iniciando seed del Modulo Capturista...\n";

        // 1. Crear usuarios capturistas de prueba
        $capturista1 = Usuario::create([
            'nombre' => 'Maria Lopez Investigadora',
            'usuario' => 'capturista1',
            'mail' => 'capturista1@udint.edu.mx',
            'contrasena' => Hash::make('password123'),
            'rol' => 'capturista',
            'activo' => true,
            'fecha_creacion' => now(),
            'ultima_conexion' => now()
        ]);
        echo "Usuario capturista1 creado (ID: {$capturista1->id_usuario})\n";

        $capturista2 = Usuario::create([
            'nombre' => 'Carlos Rodriguez Analista',
            'usuario' => 'capturista2',
            'mail' => 'capturista2@udint.edu.mx',
            'contrasena' => Hash::make('password123'),
            'rol' => 'capturista',
            'activo' => true,
            'fecha_creacion' => now(),
            'ultima_conexion' => now()
        ]);
        echo "Usuario capturista2 creado (ID: {$capturista2->id_usuario})\n";

        // Crear usuario administrador si no existe
        $admin = Usuario::where('rol', 'administrador')->first();
        if (!$admin) {
            $admin = Usuario::create([
                'nombre' => 'Administrador Principal',
                'usuario' => 'admin',
                'mail' => 'admin@udint.edu.mx',
                'contrasena' => Hash::make('admin123'),
                'rol' => 'administrador',
                'activo' => true,
                'fecha_creacion' => now(),
                'ultima_conexion' => now()
            ]);
            echo "Usuario admin creado (ID: {$admin->id_usuario})\n";
        }

        // 2. Crear casos de prueba
        $caso1 = Caso::create([
            'estado' => 'En progreso',
            'nombre' => 'Investigacion Fraude Digital',
            'tipo_caso' => 'Fraude',
            'descripcion' => 'Investigacion de fraude digital mediante redes sociales. El sospechoso ha estado vendiendo productos falsificados a traves de Facebook Marketplace y paginas web fraudulentas.',
            'fecha_creacion' => now()->subDays(5),
            'fecha_actualizacion' => now()->subDays(1),
            'id_creador' => $admin->id_usuario
        ]);
        echo "Caso 1 creado: {$caso1->nombre} (ID: {$caso1->id_caso})\n";

        $caso2 = Caso::create([
            'estado' => 'Iniciado',
            'nombre' => 'Phishing Corporativo',
            'tipo_caso' => 'Ciberseguridad',
            'descripcion' => 'Campana de phishing dirigida a empleados de empresas locales. Se han identificado correos electronicos fraudulentos solicitando credenciales corporativas.',
            'fecha_creacion' => now()->subDays(3),
            'fecha_actualizacion' => now()->subDays(2),
            'id_creador' => $admin->id_usuario
        ]);
        echo "Caso 2 creado: {$caso2->nombre} (ID: {$caso2->id_caso})\n";

        $caso3 = Caso::create([
            'estado' => 'En progreso',
            'nombre' => 'Robo de Identidad Digital',
            'tipo_caso' => 'Robo de Identidad',
            'descripcion' => 'Investigacion de robo de identidad digital. Victima reporta suplantacion de identidad en multiples plataformas de redes sociales y servicios bancarios.',
            'fecha_creacion' => now()->subDays(7),
            'fecha_actualizacion' => now(),
            'id_creador' => $admin->id_usuario
        ]);
        echo "Caso 3 creado: {$caso3->nombre} (ID: {$caso3->id_caso})\n";

        // 3. Asignar casos a capturistas
        $asignacion1 = AsignacionCaso::create([
            'id_usuario' => $capturista1->id_usuario,
            'id_caso' => $caso1->id_caso,
            'fecha_asignacion' => now()->subDays(5)
        ]);
        echo "Caso 1 asignado a capturista1\n";

        $asignacion2 = AsignacionCaso::create([
            'id_usuario' => $capturista1->id_usuario,
            'id_caso' => $caso3->id_caso,
            'fecha_asignacion' => now()->subDays(7)
        ]);
        echo "Caso 3 asignado a capturista1\n";

        $asignacion3 = AsignacionCaso::create([
            'id_usuario' => $capturista2->id_usuario,
            'id_caso' => $caso2->id_caso,
            'fecha_asignacion' => now()->subDays(3)
        ]);
        echo "Caso 2 asignado a capturista2\n";

        // 4. Crear evidencias para el Caso 1
        Evidencia::create([
            'id_caso' => $caso1->id_caso,
            'tipo' => 'Captura de Pantalla',
            'descripcion' => 'Captura de pantalla de conversacion en Facebook Marketplace donde el sospechoso ofrece productos falsificados. Usuario: @vendedor_falso. Fecha de conversacion: 15 de Enero 2025.',
            'fecha_creacion' => now()->subDays(5)
        ]);

        Evidencia::create([
            'id_caso' => $caso1->id_caso,
            'tipo' => 'Analisis de Dominio',
            'descripcion' => 'Dominio: productos-originales.xyz\nIP: 203.0.113.45\nRegistrador: NameCheap\nFecha de registro: 10 de Diciembre 2024\nEstado: Activo\nTecnologias: WordPress, PHP 7.4\nObservaciones: Sitio web con diseno similar a tienda oficial pero con dominio sospechoso.',
            'fecha_creacion' => now()->subDays(4)
        ]);

        Evidencia::create([
            'id_caso' => $caso1->id_caso,
            'tipo' => 'Perfil de Redes Sociales',
            'descripcion' => 'Facebook: @vendedor_falso\nNombre de perfil: Juan Carlos Distribuidor\nFecha de creacion: Noviembre 2024\nAmigos: 237\nActividad: Publica productos diariamente\nObservaciones: Perfil creado recientemente, fotos de productos tomadas de sitios oficiales.',
            'fecha_creacion' => now()->subDays(4)
        ]);

        Evidencia::create([
            'id_caso' => $caso1->id_caso,
            'tipo' => 'Transaccion Bancaria',
            'descripcion' => 'CLABE interbancaria detectada: 012345678901234567\nBanco: Banco del Bienestar\nTitular: [REDACTADO]\nMovimientos: 15 transferencias en ultimos 30 dias\nMonto promedio: $2,500 MXN',
            'fecha_creacion' => now()->subDays(3)
        ]);

        Evidencia::create([
            'id_caso' => $caso1->id_caso,
            'tipo' => 'Correo Electronico',
            'descripcion' => 'Email identificado: vendedor.original@email.com\nProveedor: Gmail\nEstado: Activo\nServicios vinculados: Facebook, Mercado Libre, Dropbox\nBrechas de seguridad: No encontradas',
            'fecha_creacion' => now()->subDays(2)
        ]);

        echo "5 evidencias creadas para Caso 1\n";

        // 5. Crear evidencias para el Caso 2
        Evidencia::create([
            'id_caso' => $caso2->id_caso,
            'tipo' => 'Correo Phishing',
            'descripcion' => 'Remitente: soporte@bancosegur0.com (nota el 0 en lugar de o)\nAsunto: Actualizacion urgente de seguridad\nContenido: Solicita credenciales de acceso\nEnlace malicioso: http://bancoseguro-verificacion.xyz/login\nServidores: Cloudflare proxy',
            'fecha_creacion' => now()->subDays(3)
        ]);

        Evidencia::create([
            'id_caso' => $caso2->id_caso,
            'tipo' => 'Analisis de Dominio',
            'descripcion' => 'Dominio: bancoseguro-verificacion.xyz\nIP: 104.21.45.78\nRegistrador: Namecheap\nFecha de registro: 25 de Noviembre 2024\nEstado: Activo\nCertificado SSL: Lets Encrypt (valido)\nObservaciones: Dominio typosquatting del banco legitimo',
            'fecha_creacion' => now()->subDays(2)
        ]);

        Evidencia::create([
            'id_caso' => $caso2->id_caso,
            'tipo' => 'Lista de Victimas',
            'descripcion' => 'Total de correos phishing enviados: 1,247\nDestinos: Empresas en SLP, Queretaro y Guanajuato\nTasa de apertura estimada: 18%\nCredenciales comprometidas confirmadas: 12',
            'fecha_creacion' => now()->subDays(1)
        ]);

        echo "3 evidencias creadas para Caso 2\n";

        // 6. Crear evidencias para el Caso 3
        Evidencia::create([
            'id_caso' => $caso3->id_caso,
            'tipo' => 'Perfil de Redes Sociales',
            'descripcion' => 'Instagram Falso: @maria_lopez_oficial (cuenta suplantada)\nFoto de perfil: Tomada del perfil real\nSeguidores: 456\nSiguiendo: 823\nPublicaciones: 23 (todas copiadas del perfil real)\nObservaciones: Perfil creado hace 2 meses',
            'fecha_creacion' => now()->subDays(7)
        ]);

        Evidencia::create([
            'id_caso' => $caso3->id_caso,
            'tipo' => 'Correo Electronico',
            'descripcion' => 'Email suplantado: maria.lopez.oficial@gmail.com\nEmail real: maria.lopez@hotmail.com\nServicios comprometidos: Facebook, Instagram, LinkedIn\nBrechas detectadas: Cuenta de correo creada despues de filtracion de datos',
            'fecha_creacion' => now()->subDays(6)
        ]);

        Evidencia::create([
            'id_caso' => $caso3->id_caso,
            'tipo' => 'Actividad Bancaria',
            'descripcion' => 'Intento de apertura de cuenta bancaria con identidad robada\nBanco: Scotiabank\nSucursal: Centro San Luis Potosi\nFecha del intento: 28 de Noviembre 2024\nEstado: Bloqueado por verificacion de identidad\nDocumentos presentados: INE falsificada',
            'fecha_creacion' => now()->subDays(5)
        ]);

        Evidencia::create([
            'id_caso' => $caso3->id_caso,
            'tipo' => 'Registro de IP',
            'descripcion' => 'IPs detectadas accediendo a cuentas comprometidas:\n- 189.147.23.156 (ISP: Telmex, Ubicacion: CDMX)\n- 187.188.91.234 (ISP: Totalplay, Ubicacion: Guadalajara)\n- 201.141.67.89 (ISP: Megacable, Ubicacion: Monterrey)\nPatron: Accesos desde multiples ubicaciones en horarios irregulares',
            'fecha_creacion' => now()->subDays(3)
        ]);

        echo "4 evidencias creadas para Caso 3\n";

        echo "\n=== Seed del Modulo Capturista completado exitosamente ===\n\n";
        echo "USUARIOS CREADOS:\n";
        echo "- Capturista 1: usuario='capturista1', password='password123'\n";
        echo "- Capturista 2: usuario='capturista2', password='password123'\n";
        echo "- Admin: usuario='admin', password='admin123'\n\n";
        echo "CASOS CREADOS:\n";
        echo "- Caso 1: Investigacion Fraude Digital (asignado a capturista1) - 5 evidencias\n";
        echo "- Caso 2: Phishing Corporativo (asignado a capturista2) - 3 evidencias\n";
        echo "- Caso 3: Robo de Identidad Digital (asignado a capturista1) - 4 evidencias\n\n";
        echo "Puedes iniciar sesion con cualquiera de estos usuarios para probar el modulo.\n";
    }
}

