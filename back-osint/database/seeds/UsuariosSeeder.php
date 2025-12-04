<?php

use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Administrador',
                'usuario' => 'admin',
                'mail' => 'admin@osint.com',
                'contrasena' => bcrypt('admin123'),
                'rol' => 'admin',
                'activo' => true,
                'fecha_creacion' => now(),
                'ultima_conexion' => now()
            ],
            [
                'nombre' => 'Juan Pérez',
                'usuario' => 'jperez',
                'mail' => 'juan.perez@osint.com',
                'contrasena' => bcrypt('password123'),
                'rol' => 'consultor',
                'activo' => true,
                'fecha_creacion' => now(),
                'ultima_conexion' => now()->subDays(2)
            ],
            [
                'nombre' => 'María García',
                'usuario' => 'mgarcia',
                'mail' => 'maria.garcia@osint.com',
                'contrasena' => bcrypt('password123'),
                'rol' => 'capturista',
                'activo' => true,
                'fecha_creacion' => now(),
                'ultima_conexion' => now()->subHours(5)
            ],
            [
                'nombre' => 'Carlos López',
                'usuario' => 'clopez',
                'mail' => 'carlos.lopez@osint.com',
                'contrasena' => bcrypt('password123'),
                'rol' => 'consultor',
                'activo' => true,
                'fecha_creacion' => now(),
                'ultima_conexion' => now()->subDays(1)
            ],
            [
                'nombre' => 'Ana Martínez',
                'usuario' => 'amartinez',
                'mail' => 'ana.martinez@osint.com',
                'contrasena' => bcrypt('password123'),
                'rol' => 'admin',
                'activo' => false,
                'fecha_creacion' => now()->subDays(30),
                'ultima_conexion' => now()->subDays(15)
            ]
        ]);
    }
}
