<?php

use Illuminate\Database\Seeder;
use App\Herramienta;
use App\CategoriaHerramienta;

class HerramientasSeeder extends Seeder
{
    public function run()
    {
        $herramientas = [
            [
                'nombre' => 'Shodan',
                'link' => 'https://www.shodan.io',
                'categoria' => 'Búsqueda de Dominios'
            ],
            [
                'nombre' => 'Maltego',
                'link' => 'https://www.maltego.com',
                'categoria' => 'Análisis de Redes Sociales'
            ],
            [
                'nombre' => 'Autopsy',
                'link' => 'https://www.sleuthkit.org',
                'categoria' => 'Análisis Forense Digital'
            ],
            [
                'nombre' => 'Wireshark',
                'link' => 'https://www.wireshark.org',
                'categoria' => 'Análisis de Comunicaciones'
            ],
            [
                'nombre' => 'ExifTool',
                'link' => 'https://exiftool.org',
                'categoria' => 'Análisis de Metadatos'
            ],
            [
                'nombre' => 'Google Maps',
                'link' => 'https://maps.google.com',
                'categoria' => 'Geolocalización'
            ],
            [
                'nombre' => 'OSINT Framework',
                'link' => 'https://osintframework.com/',
                'categoria' => 'Búsqueda de Personas'
            ],
            [
                'nombre' => 'Spiderfoot',
                'link' => 'https://www.spiderfoot.net',
                'categoria' => 'Monitoreo de Dark Web'
            ],
            [
                'nombre' => 'TinEye',
                'link' => 'https://tineye.com',
                'categoria' => 'Análisis de Imágenes'
            ],
            [
                'nombre' => 'Have I Been Pwned',
                'link' => 'https://haveibeenpwned.com',
                'categoria' => 'Verificación de Identidad'
            ],
        ];

        foreach ($herramientas as $h) {

            $cat = CategoriaHerramienta::where('nombre', $h['categoria'])->first();

            if ($cat) {
                Herramienta::firstOrCreate(
                    ['nombre' => $h['nombre']],
                    [
                        'nombre' => $h['nombre'],
                        'link' => $h['link'],
                        'id_categoria' => $cat->id_categoria
                    ]
                );
            }
        }
    }
}