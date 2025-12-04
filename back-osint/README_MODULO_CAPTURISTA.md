# Modulo Capturista - Guia de Configuracion y Uso

## Descripcion

El Modulo Capturista es un componente integral de la plataforma UDINT que permite a los investigadores gestionar casos de OSINT, agregar evidencias y generar reportes profesionales siguiendo las plantillas de obsidian-osint-templates.

## Caracteristicas Principales

1. Gestion de Casos Asignados
2. Captura y Actualizacion de Evidencias
3. Generacion de Reportes OSINT en formato Markdown
4. Control de acceso basado en roles
5. Registro de actividad y auditoria
6. Multiples formatos de reporte (Completo, Evidencias, Persona, Dominio, Email, Telefono)

## Requisitos

- PHP >= 7.2
- Laravel 7.x
- MySQL/MariaDB
- Composer
- Extensiones PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, JSON

## Instalacion

### 1. Clonar el Repositorio

```bash
cd Backend-Proyecto-Ing-Soft-II/back-osint
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar Base de Datos

Edita el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=udint_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 4. Ejecutar Migraciones

```bash
php artisan migrate
```

### 5. Ejecutar Seeder del Modulo Capturista

Para crear datos de prueba (usuarios, casos y evidencias):

```bash
php artisan db:seed --class=ModuloCapturistaSeeder
```

Este seeder creara:
- 2 usuarios capturistas (capturista1, capturista2)
- 1 usuario administrador (admin)
- 3 casos de prueba con diferentes tipos
- 12 evidencias distribuidas en los casos

### 6. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

El servidor estara disponible en `http://localhost:8000`

## Estructura de Archivos

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── CapturistaController.php    # Controlador principal
│   ├── Middleware/
│   │   └── VerificarRolCapturista.php      # Middleware de autorizacion
│   └── Kernel.php                          # Registro de middleware
├── Services/
│   └── ReporteOsintService.php             # Servicio de generacion de reportes
└── [Modelos]
    ├── Caso.php
    ├── Evidencia.php
    ├── AsignacionCaso.php
    └── Usuario.php

routes/
└── api.php                                 # Rutas del API

database/
└── seeds/
    └── ModuloCapturistaSeeder.php          # Seeder de datos de prueba

storage/
└── app/
    └── reportes/                           # Almacenamiento de reportes generados
```

## Usuarios de Prueba

Despues de ejecutar el seeder, puedes iniciar sesion con:

### Capturista 1
- **Usuario:** `capturista1`
- **Password:** `password123`
- **Casos asignados:** 2 (Fraude Digital, Robo de Identidad)

### Capturista 2
- **Usuario:** `capturista2`
- **Password:** `password123`
- **Casos asignados:** 1 (Phishing Corporativo)

### Administrador
- **Usuario:** `admin`
- **Password:** `admin123`
- **Permisos:** Acceso completo

## Prueba Rapida

### 1. Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "usuario": "capturista1",
    "contrasena": "password123"
  }'
```

Guarda el token de la respuesta.

### 2. Ver Casos Asignados

```bash
curl -X GET http://localhost:8000/api/capturista/casos \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### 3. Agregar Evidencia

```bash
curl -X POST http://localhost:8000/api/capturista/evidencias \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "id_caso": 1,
    "tipo": "Captura de Pantalla",
    "descripcion": "Nueva evidencia de prueba"
  }'
```

### 4. Generar Reporte

```bash
curl -X GET http://localhost:8000/api/capturista/casos/1/reporte-completo \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

## Documentacion Completa

- **MODULO_CAPTURISTA.md**: Documentacion tecnica completa del API
- **EJEMPLOS_MODULO_CAPTURISTA.md**: Ejemplos practicos de uso con cURL

## Endpoints Disponibles

### Gestion de Casos
- `GET /api/capturista/casos` - Listar casos asignados
- `GET /api/capturista/casos/{id}` - Ver detalle de caso

### Gestion de Evidencias
- `GET /api/capturista/casos/{idCaso}/evidencias` - Listar evidencias
- `POST /api/capturista/evidencias` - Agregar evidencia
- `PUT /api/capturista/evidencias/{id}` - Actualizar evidencia
- `DELETE /api/capturista/evidencias/{id}` - Eliminar evidencia

### Generacion de Reportes
- `GET /api/capturista/casos/{idCaso}/reporte-completo` - Reporte completo
- `GET /api/capturista/casos/{idCaso}/reporte-evidencias` - Reporte de evidencias
- `POST /api/capturista/casos/{idCaso}/reporte-personalizado` - Reporte personalizado

### Gestion de Reportes
- `GET /api/capturista/casos/{idCaso}/reportes` - Listar reportes
- `GET /api/capturista/reportes/{nombreArchivo}/descargar` - Descargar reporte

## Tipos de Reportes

El sistema genera reportes en formato Markdown compatibles con Obsidian:

1. **Reporte Completo**: Vista integral del caso con todas las secciones
2. **Reporte de Evidencias**: Solo las evidencias del caso
3. **Reporte de Persona**: Template para investigacion de personas
4. **Reporte de Dominio**: Template para analisis de dominios/sitios web
5. **Reporte de Email**: Template para investigacion de correos electronicos
6. **Reporte de Telefono**: Template para investigacion de numeros telefonicos

## Seguridad

El modulo implementa:

1. Autenticacion mediante tokens
2. Autorizacion basada en roles
3. Control de acceso a casos (solo casos asignados)
4. Validacion de entrada de datos
5. Soft deletes para evidencias
6. Registro de auditoria de todas las acciones

## Almacenamiento de Reportes

Los reportes se almacenan en:
```
storage/app/reportes/
```

Formato de nombres:
- `reporte_caso_{idCaso}_{timestamp}.md`
- `reporte_evidencias_{idCaso}_{timestamp}.md`
- `reporte_{tipo}_{idCaso}_{timestamp}.md`

## Permisos de Directorio

Asegurate de que Laravel tenga permisos de escritura en:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Troubleshooting

### Error: "Class 'App\Services\ReporteOsintService' not found"

Ejecuta:
```bash
composer dump-autoload
```

### Error: "SQLSTATE[HY000]: General error: 1364"

Ejecuta las migraciones nuevamente:
```bash
php artisan migrate:fresh
php artisan db:seed --class=ModuloCapturistaSeeder
```

### Error: "Storage directory not found"

Crea el directorio de reportes:
```bash
mkdir -p storage/app/reportes
chmod 775 storage/app/reportes
```

### Error: "Token invalido"

Genera un nuevo token haciendo login nuevamente. Los tokens pueden expirar o ser invalidos.

## Testing

### Testing Manual con Postman

1. Importa la coleccion de Postman desde `EJEMPLOS_MODULO_CAPTURISTA.md`
2. Configura la variable de entorno `base_url` a `http://localhost:8000/api`
3. Ejecuta el endpoint de Login
4. Copia el token a la variable de entorno `token`
5. Prueba los demas endpoints

### Testing con cURL

Consulta el archivo `EJEMPLOS_MODULO_CAPTURISTA.md` para ejemplos completos de comandos cURL.

## Proximas Mejoras

1. Exportacion de reportes a PDF
2. Adjuntar archivos multimedia a evidencias
3. Sistema de notificaciones en tiempo real
4. Dashboard con estadisticas
5. Busqueda y filtrado avanzado
6. Integracion con herramientas OSINT externas
7. API para chatbots (Telegram, WhatsApp, Alexa)

## Soporte

Para reportar problemas o sugerencias:
- Revisa la documentacion completa en `MODULO_CAPTURISTA.md`
- Consulta ejemplos practicos en `EJEMPLOS_MODULO_CAPTURISTA.md`

## Creditos

- Basado en plantillas de [obsidian-osint-templates](https://github.com/WebBreacher/obsidian-osint-templates)
- Desarrollado para la Unidad de Delitos Informaticos (UDINT)
- Universidad Politecnica de San Luis Potosi

## Licencia

Este proyecto es parte de la plataforma UDINT para fines educativos y de investigacion.

