<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ejecutar seeders en orden correcto (respetando dependencias)
        $this->call([
            UsuariosSeeder::class,
            CategoriasHerramientasSeeder::class,
            HerramientasSeeder::class,
            CasosSeeder::class,
            ChatbotsSeeder::class,
            CapturistaTestSeeder::class, // Datos de prueba para mgarcia
            // ModuloCapturistaSeeder::class, // Descomentar para datos de prueba del modulo capturista
        ]);
        
        $this->command->info('Todos los seeders ejecutados correctamente!');
        $this->command->info('');
        $this->command->info('Usuario mgarcia creado con 5 casos de prueba asignados');
        $this->command->info('Para datos de prueba del Modulo Capturista, ejecuta:');
        $this->command->info('php artisan db:seed --class=ModuloCapturistaSeeder');
    }
}
