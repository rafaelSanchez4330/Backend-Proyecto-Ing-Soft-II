# Modulo Capturista - Documentacion

## Descripcion General

El Modulo Capturista permite a los usuarios con rol de capturista gestionar sus casos asignados, agregar evidencias y generar reportes en formato OSINT (Open Source Intelligence) siguiendo las plantillas de obsidian-osint-templates.

## Funcionalidades Implementadas

### 1. Acceso a Casos Asignados

Los capturistas pueden visualizar y acceder unicamente a los casos que les han sido asignados.

#### Endpoint: Obtener Casos Asignados
```
GET /api/capturista/casos
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Casos obtenidos exitosamente",
  "data": [
    {
      "id_caso": 1,
      "nombre": "Investigacion Fraude Digital",
      "tipo_caso": "Fraude",
      "descripcion": "Caso de fraude mediante redes sociales",
      "estado": "En progreso",
      "fecha_creacion": "2025-01-15T10:30:00.000000Z",
      "fecha_actualizacion": "2025-01-20T14:45:00.000000Z",
      "creador": {
        "id": 1,
        "nombre": "Admin Principal"
      },
      "total_evidencias": 5,
      "fecha_asignacion": "2025-01-15T11:00:00.000000Z"
    }
  ]
}
```

#### Endpoint: Ver Detalle de un Caso
```
GET /api/capturista/casos/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Caso obtenido exitosamente",
  "data": {
    "id_caso": 1,
    "nombre": "Investigacion Fraude Digital",
    "tipo_caso": "Fraude",
    "descripcion": "Caso de fraude mediante redes sociales",
    "estado": "En progreso",
    "fecha_creacion": "2025-01-15T10:30:00.000000Z",
    "fecha_actualizacion": "2025-01-20T14:45:00.000000Z",
    "creador": {
      "id": 1,
      "nombre": "Admin Principal",
      "mail": "admin@udint.edu.mx"
    },
    "evidencias": [
      {
        "id_evidencia": 1,
        "tipo": "Captura de Pantalla",
        "descripcion": "Evidencia de conversaciones sospechosas",
        "fecha_creacion": "2025-01-15T12:00:00.000000Z"
      }
    ],
    "actividades": []
  }
}
```

### 2. Gestion de Evidencias

Los capturistas pueden agregar, actualizar y eliminar evidencias de sus casos asignados.

#### Endpoint: Obtener Evidencias de un Caso
```
GET /api/capturista/casos/{idCaso}/evidencias
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Evidencias obtenidas exitosamente",
  "data": [
    {
      "id_evidencia": 1,
      "tipo": "Captura de Pantalla",
      "descripcion": "Conversaciones en redes sociales",
      "fecha_creacion": "2025-01-15T12:00:00.000000Z"
    }
  ]
}
```

#### Endpoint: Agregar Nueva Evidencia
```
POST /api/capturista/evidencias
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "id_caso": 1,
  "tipo": "Documento",
  "descripcion": "Contrato fraudulento encontrado en el servidor"
}
```

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Evidencia agregada exitosamente",
  "data": {
    "id_evidencia": 5,
    "tipo": "Documento",
    "descripcion": "Contrato fraudulento encontrado en el servidor",
    "fecha_creacion": "2025-01-20T15:30:00.000000Z"
  }
}
```

#### Endpoint: Actualizar Evidencia
```
PUT /api/capturista/evidencias/{id}
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "tipo": "Documento Legal",
  "descripcion": "Contrato fraudulento - Version actualizada con analisis"
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Evidencia actualizada exitosamente",
  "data": {
    "id_evidencia": 5,
    "tipo": "Documento Legal",
    "descripcion": "Contrato fraudulento - Version actualizada con analisis",
    "fecha_creacion": "2025-01-20T15:30:00.000000Z"
  }
}
```

#### Endpoint: Eliminar Evidencia
```
DELETE /api/capturista/evidencias/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Evidencia eliminada exitosamente"
}
```

### 3. Generacion de Reportes OSINT

El sistema permite generar multiples tipos de reportes basados en las plantillas de obsidian-osint-templates.

#### Tipos de Reportes Disponibles:

1. **Reporte Completo**: Incluye toda la informacion del caso con resumen ejecutivo, evidencias, linea de tiempo y conclusiones
2. **Reporte de Evidencias**: Unicamente las evidencias del caso organizadas por tipo
3. **Reportes Personalizados**: Siguiendo plantillas especificas de OSINT
   - Reporte de Persona
   - Reporte de Dominio/Website
   - Reporte de Email
   - Reporte de Telefono

#### Endpoint: Generar Reporte Completo
```
GET /api/capturista/casos/{idCaso}/reporte-completo
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Reporte generado exitosamente",
  "data": {
    "nombre_archivo": "reporte_caso_1_1737385200.md",
    "contenido": "# Reporte de Investigacion OSINT\n\n## Caso: Investigacion Fraude Digital\n\n..."
  }
}
```

#### Endpoint: Generar Reporte de Evidencias
```
GET /api/capturista/casos/{idCaso}/reporte-evidencias
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Reporte de evidencias generado exitosamente",
  "data": {
    "nombre_archivo": "reporte_evidencias_1_1737385200.md",
    "contenido": "# Reporte de Evidencias\n\n## Caso: Investigacion Fraude Digital\n\n..."
  }
}
```

#### Endpoint: Generar Reporte Personalizado
```
POST /api/capturista/casos/{idCaso}/reporte-personalizado
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body para Reporte de Persona:**
```json
{
  "tipo_reporte": "persona",
  "datos": {
    "nombre_completo": "Juan Perez Garcia",
    "aliases": "JuanP, JPerez",
    "fecha_nacimiento": "1985-03-15",
    "edad": "38",
    "genero": "Masculino",
    "nacionalidad": "Mexicana",
    "emails": "juan.perez@email.com, jperez@work.com",
    "telefonos": "+52 444 123 4567",
    "direcciones": "Av. Principal 123, San Luis Potosi",
    "redes_sociales": [
      {
        "plataforma": "Facebook",
        "usuario": "juan.perez",
        "url": "https://facebook.com/juan.perez",
        "estado": "Activo"
      },
      {
        "plataforma": "Twitter",
        "usuario": "@juanperez",
        "url": "https://twitter.com/juanperez",
        "estado": "Activo"
      }
    ],
    "investigador": "Maria Lopez"
  }
}
```

**Body para Reporte de Dominio:**
```json
{
  "tipo_reporte": "dominio",
  "datos": {
    "dominio": "ejemplo-sospechoso.com",
    "ip": "192.168.1.100",
    "registrador": "GoDaddy",
    "fecha_registro": "2023-05-10",
    "fecha_expiracion": "2025-05-10",
    "name_servers": "ns1.ejemplo.com, ns2.ejemplo.com",
    "whois": "Domain Name: ejemplo-sospechoso.com\nRegistrar: GoDaddy...",
    "dns_records": [
      {"tipo": "A", "valor": "192.168.1.100"},
      {"tipo": "MX", "valor": "mail.ejemplo-sospechoso.com"}
    ],
    "estado": "Activo",
    "servidor": "Apache/2.4.41",
    "tecnologias": "PHP 7.4, MySQL, WordPress"
  }
}
```

**Body para Reporte de Email:**
```json
{
  "tipo_reporte": "email",
  "datos": {
    "email": "sospechoso@email.com",
    "dominio": "email.com",
    "valido": "Si",
    "desechable": "No",
    "servicios": [
      {
        "nombre": "Facebook",
        "estado": "Registrado",
        "detalles": "Cuenta activa desde 2018"
      }
    ],
    "brechas": [
      {
        "sitio": "LinkedIn",
        "fecha": "2021-06-22",
        "datos_comprometidos": "Email, Nombre, Telefono"
      }
    ]
  }
}
```

**Body para Reporte de Telefono:**
```json
{
  "tipo_reporte": "telefono",
  "datos": {
    "numero": "+52 444 123 4567",
    "pais": "Mexico",
    "operador": "Telcel",
    "tipo": "Movil",
    "valido": "Si",
    "region": "San Luis Potosi",
    "ciudad": "San Luis Potosi",
    "perfiles": [
      {
        "plataforma": "WhatsApp",
        "informacion": "Cuenta activa, ultima conexion hace 2 horas"
      }
    ]
  }
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Reporte personalizado generado exitosamente",
  "data": {
    "tipo_reporte": "persona",
    "nombre_archivo": "reporte_persona_1_1737385200.md",
    "contenido": "# Person Investigation Report\n\n## Case: Investigacion Fraude Digital\n\n..."
  }
}
```

#### Endpoint: Listar Reportes de un Caso
```
GET /api/capturista/casos/{idCaso}/reportes
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Reportes obtenidos exitosamente",
  "data": [
    {
      "nombre_archivo": "reporte_caso_1_1737385200.md",
      "tamano": 5420,
      "fecha_creacion": 1737385200
    }
  ]
}
```

#### Endpoint: Descargar Reporte
```
GET /api/capturista/reportes/{nombreArchivo}/descargar
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
Descarga directa del archivo en formato Markdown.

## Formato de Reportes

Todos los reportes se generan en formato Markdown (.md) siguiendo las convenciones de obsidian-osint-templates, lo que permite:

1. Facil lectura y edicion
2. Compatibilidad con Obsidian y otras herramientas de notas
3. Estructura clara y organizada
4. Formato profesional

### Estructura de Reporte Completo

```markdown
# Reporte de Investigacion OSINT

## Caso: [Nombre del Caso]

---

## Resumen Ejecutivo
[Tabla con informacion clave del caso]

## Informacion General
[Descripcion y equipo asignado]

## Evidencias Recopiladas
[Evidencias organizadas por tipo]

## Linea de Tiempo
[Cronologia de eventos]

## Conclusiones y Observaciones
[Estadisticas y notas]
```

## Control de Acceso

El modulo implementa las siguientes medidas de seguridad:

1. **Autenticacion**: Todos los endpoints requieren token de autenticacion
2. **Autorizacion por Rol**: Solo usuarios con rol 'capturista' o 'administrador' pueden acceder
3. **Control de Acceso a Casos**: Los capturistas solo pueden acceder a casos que les fueron asignados
4. **Registro de Actividad**: Todas las acciones se registran en el log de actividad

## Mensajes de Error Comunes

### Error 401 - No Autenticado
```json
{
  "success": false,
  "message": "Usuario no autenticado"
}
```

### Error 403 - Sin Permisos
```json
{
  "success": false,
  "message": "No tiene permiso para acceder a este caso"
}
```

### Error 404 - No Encontrado
```json
{
  "success": false,
  "message": "Caso no encontrado"
}
```

### Error 422 - Error de Validacion
```json
{
  "success": false,
  "message": "Error de validacion",
  "errors": {
    "tipo": ["El tipo de evidencia es obligatorio"]
  }
}
```

## Almacenamiento de Reportes

Los reportes generados se almacenan en:
```
storage/app/reportes/
```

Nomenclatura de archivos:
- Reporte completo: `reporte_caso_{idCaso}_{timestamp}.md`
- Reporte de evidencias: `reporte_evidencias_{idCaso}_{timestamp}.md`
- Reportes personalizados: `reporte_{tipo}_{idCaso}_{timestamp}.md`

## Consideraciones Tecnicas

1. El modulo utiliza Soft Deletes para las evidencias
2. Todas las fechas se manejan con Carbon
3. Los reportes se generan de forma sincrona (para grandes volumenes considerar procesamiento asincrono)
4. El servicio de reportes es inyectable y testeable

## Ejemplo de Flujo Completo

1. Capturista inicia sesion
2. Obtiene lista de casos asignados
3. Selecciona un caso especifico
4. Agrega nuevas evidencias al caso
5. Genera reporte completo o personalizado
6. Descarga el reporte en formato Markdown

## Integracion con Frontend

Para integrar este modulo con el frontend:

1. Implementar sistema de autenticacion que guarde el token
2. Crear pantalla de listado de casos
3. Crear pantalla de detalle de caso con formulario de evidencias
4. Implementar generador de reportes con seleccion de tipo
5. Proveer opcion de descarga de reportes

## Proximas Mejoras Sugeridas

1. Exportacion de reportes a PDF
2. Plantillas personalizables
3. Adjuntar archivos multimedia a evidencias
4. Sistema de notificaciones
5. Historial de cambios en evidencias
6. Busqueda y filtrado avanzado de casos

