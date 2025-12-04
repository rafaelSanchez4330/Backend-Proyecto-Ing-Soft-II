<?php

use Illuminate\Database\Seeder;

class HerramientasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $herramientas = [
            ['nombre' => 'Sherlock', 'enlace' => 'https://github.com/sherlock-project/sherlock'],
            ['nombre' => 'Maltego', 'enlace' => 'https://www.maltego.com/'],
            ['nombre' => 'Shodan', 'enlace' => 'https://www.shodan.io/'],
            ['nombre' => 'Google Dorking', 'enlace' => 'https://www.google.com/'],
            ['nombre' => 'TinEye', 'enlace' => 'https://tineye.com/'],
            ['nombre' => 'Pipl', 'enlace' => 'https://pipl.com/'],
            ['nombre' => 'Whois Lookup', 'enlace' => 'https://whois.net/'],
            ['nombre' => 'Social Searcher', 'enlace' => 'https://www.social-searcher.com/'],
            ['nombre' => 'Have I Been Pwned', 'enlace' => 'https://haveibeenpwned.com/'],
            ['nombre' => 'Wayback Machine', 'enlace' => 'https://web.archive.org/'],
            ['nombre' => 'ExifTool', 'enlace' => 'https://exiftool.org/'],
            ['nombre' => 'Recon-ng', 'enlace' => 'https://github.com/lanmaster53/recon-ng'],
            ['nombre' => 'theHarvester', 'enlace' => 'https://github.com/laramies/theHarvester'],
            ['nombre' => 'SpiderFoot', 'enlace' => 'https://www.spiderfoot.net/'],
            ['nombre' => 'FOCA', 'enlace' => 'https://github.com/ElevenPaths/FOCA'],
            ['nombre' => 'Creepy', 'enlace' => 'https://github.com/ilektrojohn/creepy'],
            ['nombre' => 'Metagoofil', 'enlace' => 'https://github.com/laramies/metagoofil'],
            ['nombre' => 'Tor Browser', 'enlace' => 'https://www.torproject.org/'],
            ['nombre' => 'Wireshark', 'enlace' => 'https://www.wireshark.org/'],
            ['nombre' => 'Nmap', 'enlace' => 'https://nmap.org/']
        ];

        foreach ($herramientas as $herramienta) {
            DB::table('herramientas')->insert($herramienta);
        }

        // Crear relaciones entre herramientas y categorías
        $relaciones = [
            // Sherlock - Búsqueda de Personas, Análisis de Redes Sociales
            ['id_herramienta' => 1, 'id_categoria' => 1],
            ['id_herramienta' => 1, 'id_categoria' => 2],
            
            // Maltego - Análisis de Redes Sociales, Verificación de Identidad
            ['id_herramienta' => 2, 'id_categoria' => 2],
            ['id_herramienta' => 2, 'id_categoria' => 9],
            
            // Shodan - Búsqueda de Dominios, Análisis Forense Digital
            ['id_herramienta' => 3, 'id_categoria' => 5],
            ['id_herramienta' => 3, 'id_categoria' => 10],
            
            // TinEye - Análisis de Imágenes
            ['id_herramienta' => 5, 'id_categoria' => 4],
            
            // Pipl - Búsqueda de Personas
            ['id_herramienta' => 6, 'id_categoria' => 1],
            
            // ExifTool - Análisis de Metadatos, Análisis de Imágenes
            ['id_herramienta' => 11, 'id_categoria' => 6],
            ['id_herramienta' => 11, 'id_categoria' => 4],
            
            // Tor Browser - Monitoreo de Dark Web
            ['id_herramienta' => 18, 'id_categoria' => 7],
            
            // Wireshark - Análisis de Comunicaciones, Análisis Forense Digital
            ['id_herramienta' => 19, 'id_categoria' => 8],
            ['id_herramienta' => 19, 'id_categoria' => 10]
        ];

        foreach ($relaciones as $relacion) {
            DB::table('rel_herramientas_categorias')->insert($relacion);
        }
    }
}
