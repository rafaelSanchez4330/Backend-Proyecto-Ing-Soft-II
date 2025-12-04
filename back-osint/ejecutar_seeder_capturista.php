<?php

// Script para ejecutar solo el CapturistaTestSeeder
// Uso: php ejecutar_seeder_capturista.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Cargar el seeder
require __DIR__.'/database/seeds/CapturistaTestSeeder.php';

// Ejecutar el seeder
echo "Ejecutando CapturistaTestSeeder...\n\n";

try {
    $seeder = new CapturistaTestSeeder();
    $seeder->run();
    echo "\n✓ Seeder ejecutado exitosamente!\n";
} catch (Exception $e) {
    echo "\n✗ Error al ejecutar seeder:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
