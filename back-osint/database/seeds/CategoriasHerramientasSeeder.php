<?php

use Illuminate\Database\Seeder;
use App\CategoriaHerramienta;

class CategoriasHerramientasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
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
        ];

        foreach ($categorias as $cat) {
            CategoriaHerramienta::firstOrCreate(['nombre' => $cat['nombre']]);
        }
    }
}