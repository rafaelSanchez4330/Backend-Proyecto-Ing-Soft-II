<?php

use Illuminate\Database\Seeder;

class CategoriasHerramientasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias_herramientas')->insert([
            ['nombre' => 'Búsqueda de Personas'],
            ['nombre' => 'Análisis de Redes Sociales'],
            ['nombre' => 'Geolocalización'],
            ['nombre' => 'Análisis de Imágenes'],
            ['nombre' => 'Búsqueda de Dominios'],
            ['nombre' => 'Análisis de Metadatos'],
            ['nombre' => 'Monitoreo de Dark Web'],
            ['nombre' => 'Análisis de Comunicaciones'],
            ['nombre' => 'Verificación de Identidad'],
            ['nombre' => 'Análisis Forense Digital']
        ]);
    }
}
