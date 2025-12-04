# Instrucciones para Ejecutar el Seeder

El seeder `CapturistaTestSeeder` ha sido creado pero Laravel está teniendo problemas para encontrarlo debido a diferencias de namespace.

## Opción 1: Ejecutar desde DatabaseSeeder

Agrega la siguiente línea al archivo `database/seeds/DatabaseSeeder.php`:

```php
public function run()
{
    $this->call(CapturistaTestSeeder::class);
}
```

Luego ejecuta:
```bash
php artisan db:seed
```

## Opción 2: Ejecutar directamente con PHP

```bash
cd /home/daniel_gil/Documentos/Capturista-Ing-Soft-II/back-osint
php -r "require 'vendor/autoload.php'; require 'database/seeds/CapturistaTestSeeder.php'; (new CapturistaTestSeeder)->run();"
```

## Opción 3: Usar tinker

```bash
php artisan tinker
```

Luego dentro de tinker:
```php
require 'database/seeds/CapturistaTestSeeder.php';
(new CapturistaTestSeeder)->run();
```

## Qué hace el Seeder

- Crea/encuentra usuario `mgarcia` con contraseña `password123`
- Crea 5 casos de prueba:
  - CASO-2025-001 (activo, alta prioridad) - Fraude digital
  - CASO-2025-002 (en progreso, media prioridad) - Phishing
  - CASO-2025-003 (activo, alta prioridad) - Redes sociales
  - CASO-2024-089 (en progreso, media prioridad) - Robo de identidad
  - CASO-2024-075 (finalizado, baja prioridad) - Malware
- Asigna todos los casos a mgarcia
- Agrega evidencias de ejemplo a los casos activos y en progreso
