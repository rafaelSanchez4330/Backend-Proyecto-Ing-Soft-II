# Ejemplos de Uso - Modulo Capturista

Este documento contiene ejemplos practicos para probar el Modulo Capturista usando cURL.

## Configuracion Inicial

Primero, define las variables de entorno:

```bash
# URL base de la API
export API_URL="http://localhost:8000/api"

# Token de autenticacion (obtenido despues del login)
export TOKEN="tu_token_aqui"
```

## 1. Autenticacion

### Login
```bash
curl -X POST "${API_URL}/login" \
  -H "Content-Type: application/json" \
  -d '{
    "usuario": "capturista1",
    "contrasena": "password123"
  }'
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "token": "dXNlcl9pZHx0aW1lc3RhbXB8dW5pcWlk",
    "usuario": {
      "id": 2,
      "nombre": "Juan Capturista",
      "usuario": "capturista1",
      "mail": "capturista1@udint.edu.mx",
      "rol": "capturista"
    }
  }
}
```

Guarda el token:
```bash
export TOKEN="dXNlcl9pZHx0aW1lc3RhbXB8dW5pcWlk"
```

## 2. Gestion de Casos

### Obtener Casos Asignados
```bash
curl -X GET "${API_URL}/capturista/casos" \
  -H "Authorization: Bearer ${TOKEN}"
```

### Ver Detalle de un Caso
```bash
curl -X GET "${API_URL}/capturista/casos/1" \
  -H "Authorization: Bearer ${TOKEN}"
```

## 3. Gestion de Evidencias

### Obtener Evidencias de un Caso
```bash
curl -X GET "${API_URL}/capturista/casos/1/evidencias" \
  -H "Authorization: Bearer ${TOKEN}"
```

### Agregar Nueva Evidencia
```bash
curl -X POST "${API_URL}/capturista/evidencias" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "id_caso": 1,
    "tipo": "Captura de Pantalla",
    "descripcion": "Evidencia de conversacion en Facebook mostrando transaccion fraudulenta del 15 de enero de 2025. Usuario sospechoso: @fraudster123"
  }'
```

### Agregar Evidencia de Documento
```bash
curl -X POST "${API_URL}/capturista/evidencias" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "id_caso": 1,
    "tipo": "Documento",
    "descripcion": "Contrato fraudulento encontrado en servidor comprometido. Hash SHA256: a1b2c3d4e5f6..."
  }'
```

### Agregar Evidencia de Registro DNS
```bash
curl -X POST "${API_URL}/capturista/evidencias" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "id_caso": 1,
    "tipo": "Registro DNS",
    "descripcion": "Registros DNS del dominio sospechoso ejemplo-fraude.com:\n\nA: 192.168.1.100\nMX: mail.ejemplo-fraude.com\nNS: ns1.ejemplo.com, ns2.ejemplo.com\n\nRegistrador: GoDaddy\nFecha registro: 2023-05-10"
  }'
```

### Actualizar Evidencia
```bash
curl -X PUT "${API_URL}/capturista/evidencias/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo": "Captura de Pantalla - Validada",
    "descripcion": "Evidencia de conversacion en Facebook mostrando transaccion fraudulenta del 15 de enero de 2025. Usuario sospechoso: @fraudster123. VALIDADA por equipo forense."
  }'
```

### Eliminar Evidencia
```bash
curl -X DELETE "${API_URL}/capturista/evidencias/1" \
  -H "Authorization: Bearer ${TOKEN}"
```

## 4. Generacion de Reportes

### Generar Reporte Completo
```bash
curl -X GET "${API_URL}/capturista/casos/1/reporte-completo" \
  -H "Authorization: Bearer ${TOKEN}"
```

### Generar Reporte de Evidencias
```bash
curl -X GET "${API_URL}/capturista/casos/1/reporte-evidencias" \
  -H "Authorization: Bearer ${TOKEN}"
```

### Generar Reporte de Investigacion de Persona
```bash
curl -X POST "${API_URL}/capturista/casos/1/reporte-personalizado" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_reporte": "persona",
    "datos": {
      "nombre_completo": "Juan Carlos Perez Garcia",
      "aliases": "JuanP, JPerez, El Ingeniero",
      "fecha_nacimiento": "1985-03-15",
      "edad": "38",
      "genero": "Masculino",
      "nacionalidad": "Mexicana",
      "emails": "juan.perez@email.com, jperez@work.com, jcperez85@gmail.com",
      "telefonos": "+52 444 123 4567, +52 444 987 6543",
      "direcciones": "Av. Principal 123, Col. Centro, San Luis Potosi, SLP",
      "redes_sociales": [
        {
          "plataforma": "Facebook",
          "usuario": "juan.perez",
          "url": "https://facebook.com/juan.perez",
          "estado": "Activo"
        },
        {
          "plataforma": "Twitter",
          "usuario": "@juanperez85",
          "url": "https://twitter.com/juanperez85",
          "estado": "Activo"
        },
        {
          "plataforma": "LinkedIn",
          "usuario": "juancarlosperez",
          "url": "https://linkedin.com/in/juancarlosperez",
          "estado": "Activo"
        },
        {
          "plataforma": "Instagram",
          "usuario": "@juan_perez",
          "url": "https://instagram.com/juan_perez",
          "estado": "Privado"
        }
      ],
      "investigador": "Maria Lopez Investigadora"
    }
  }'
```

### Generar Reporte de Investigacion de Dominio
```bash
curl -X POST "${API_URL}/capturista/casos/1/reporte-personalizado" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_reporte": "dominio",
    "datos": {
      "dominio": "ejemplo-sospechoso.com",
      "ip": "192.168.1.100",
      "registrador": "GoDaddy LLC",
      "fecha_registro": "2023-05-10",
      "fecha_expiracion": "2025-05-10",
      "name_servers": "ns1.ejemplo.com, ns2.ejemplo.com",
      "whois": "Domain Name: ejemplo-sospechoso.com\\nRegistry Domain ID: 2780123456_DOMAIN_COM-VRSN\\nRegistrar: GoDaddy.com LLC\\nCreation Date: 2023-05-10T10:30:00Z\\nExpiry Date: 2025-05-10T10:30:00Z\\nRegistrant Organization: REDACTED FOR PRIVACY\\nRegistrant State/Province: San Luis Potosi\\nRegistrant Country: MX",
      "dns_records": [
        {"tipo": "A", "valor": "192.168.1.100"},
        {"tipo": "AAAA", "valor": "2001:0db8:85a3:0000:0000:8a2e:0370:7334"},
        {"tipo": "MX", "valor": "10 mail.ejemplo-sospechoso.com"},
        {"tipo": "TXT", "valor": "v=spf1 include:_spf.google.com ~all"},
        {"tipo": "NS", "valor": "ns1.ejemplo.com"},
        {"tipo": "NS", "valor": "ns2.ejemplo.com"}
      ],
      "estado": "Activo - Sitio operacional",
      "servidor": "Apache/2.4.41 (Ubuntu)",
      "tecnologias": "PHP 7.4, MySQL 5.7, WordPress 5.8, WooCommerce, SSL/TLS"
    }
  }'
```

### Generar Reporte de Investigacion de Email
```bash
curl -X POST "${API_URL}/capturista/casos/1/reporte-personalizado" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
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
          "detalles": "Cuenta activa desde 2018, ultima actividad hace 2 dias"
        },
        {
          "nombre": "Twitter",
          "estado": "Registrado",
          "detalles": "Cuenta activa desde 2019"
        },
        {
          "nombre": "LinkedIn",
          "estado": "Registrado",
          "detalles": "Perfil profesional activo"
        },
        {
          "nombre": "Amazon",
          "estado": "Registrado",
          "detalles": "Cuenta de compras activa"
        }
      ],
      "brechas": [
        {
          "sitio": "LinkedIn",
          "fecha": "2021-06-22",
          "datos_comprometidos": "Email, Nombre completo, Telefono, Direccion profesional, Hash de password"
        },
        {
          "sitio": "Adobe",
          "fecha": "2019-10-15",
          "datos_comprometidos": "Email, Username, Password hint"
        }
      ]
    }
  }'
```

### Generar Reporte de Investigacion de Telefono
```bash
curl -X POST "${API_URL}/capturista/casos/1/reporte-personalizado" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_reporte": "telefono",
    "datos": {
      "numero": "+52 444 123 4567",
      "pais": "Mexico",
      "operador": "Telcel",
      "tipo": "Movil",
      "valido": "Si",
      "region": "San Luis Potosi",
      "ciudad": "San Luis Potosi Capital",
      "perfiles": [
        {
          "plataforma": "WhatsApp",
          "informacion": "Cuenta activa, ultima conexion hace 2 horas, foto de perfil visible"
        },
        {
          "plataforma": "Telegram",
          "informacion": "Cuenta registrada, username: @usuario123"
        },
        {
          "plataforma": "Truecaller",
          "informacion": "Registrado como Juan Perez - Ingeniero"
        }
      ]
    }
  }'
```

## 5. Gestion de Reportes

### Listar Reportes de un Caso
```bash
curl -X GET "${API_URL}/capturista/casos/1/reportes" \
  -H "Authorization: Bearer ${TOKEN}"
```

### Descargar Reporte
```bash
# Primero, lista los reportes para obtener el nombre del archivo
curl -X GET "${API_URL}/capturista/casos/1/reportes" \
  -H "Authorization: Bearer ${TOKEN}"

# Luego descarga el reporte especifico
curl -X GET "${API_URL}/capturista/reportes/reporte_caso_1_1737385200.md/descargar" \
  -H "Authorization: Bearer ${TOKEN}" \
  -o "reporte_caso_1.md"
```

## 6. Flujo Completo de Trabajo

### Escenario: Investigacion de Fraude Digital

```bash
# 1. Login
TOKEN=$(curl -s -X POST "${API_URL}/login" \
  -H "Content-Type: application/json" \
  -d '{"usuario": "capturista1", "contrasena": "password123"}' \
  | jq -r '.data.token')

# 2. Ver casos asignados
curl -X GET "${API_URL}/capturista/casos" \
  -H "Authorization: Bearer ${TOKEN}" \
  | jq

# 3. Ver detalle del caso #1
curl -X GET "${API_URL}/capturista/casos/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  | jq

# 4. Agregar evidencia de redes sociales
curl -X POST "${API_URL}/capturista/evidencias" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "id_caso": 1,
    "tipo": "Perfil de Redes Sociales",
    "descripcion": "Perfil de Facebook del sospechoso muestra actividad relacionada con venta de productos falsificados"
  }' | jq

# 5. Agregar evidencia de dominio
curl -X POST "${API_URL}/capturista/evidencias" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "id_caso": 1,
    "tipo": "Analisis de Dominio",
    "descripcion": "Dominio ejemplo-fraude.com registrado con informacion falsa, hosting en servidor compartido"
  }' | jq

# 6. Generar reporte completo
curl -X GET "${API_URL}/capturista/casos/1/reporte-completo" \
  -H "Authorization: Bearer ${TOKEN}" \
  | jq -r '.data.contenido' > reporte_completo.md

# 7. Generar reporte de persona
curl -X POST "${API_URL}/capturista/casos/1/reporte-personalizado" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_reporte": "persona",
    "datos": {
      "nombre_completo": "Juan Perez",
      "aliases": "JPerez",
      "emails": "jperez@email.com",
      "telefonos": "+52 444 123 4567",
      "redes_sociales": [
        {
          "plataforma": "Facebook",
          "usuario": "jperez",
          "url": "https://facebook.com/jperez",
          "estado": "Activo"
        }
      ]
    }
  }' | jq -r '.data.contenido' > reporte_persona.md

echo "Reportes generados: reporte_completo.md, reporte_persona.md"
```

## 7. Coleccion Postman

Puedes importar esta coleccion en Postman:

```json
{
  "info": {
    "name": "UDINT - Modulo Capturista",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000/api"
    },
    {
      "key": "token",
      "value": ""
    }
  ],
  "item": [
    {
      "name": "Autenticacion",
      "item": [
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"usuario\": \"capturista1\",\n  \"contrasena\": \"password123\"\n}"
            },
            "url": "{{base_url}}/login"
          }
        }
      ]
    },
    {
      "name": "Casos",
      "item": [
        {
          "name": "Obtener Casos Asignados",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}"
              }
            ],
            "url": "{{base_url}}/capturista/casos"
          }
        },
        {
          "name": "Ver Caso",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}"
              }
            ],
            "url": "{{base_url}}/capturista/casos/1"
          }
        }
      ]
    },
    {
      "name": "Evidencias",
      "item": [
        {
          "name": "Agregar Evidencia",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}"
              },
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"id_caso\": 1,\n  \"tipo\": \"Captura de Pantalla\",\n  \"descripcion\": \"Evidencia de conversacion sospechosa\"\n}"
            },
            "url": "{{base_url}}/capturista/evidencias"
          }
        }
      ]
    }
  ]
}
```

## Notas Importantes

1. Reemplaza `http://localhost:8000` con la URL de tu servidor
2. El token debe ser actualizado despues de cada login
3. Los IDs de casos y evidencias deben existir en la base de datos
4. Todos los reportes se generan en formato Markdown
5. Los reportes se almacenan en `storage/app/reportes/`

## Solucion de Problemas

### Error: Usuario no autenticado
Verifica que el token sea valido y este correctamente incluido en el header Authorization.

### Error: No tiene permiso
Asegura que el usuario tenga rol de capturista y que el caso este asignado al usuario.

### Error: Caso no encontrado
Verifica que el ID del caso exista en la base de datos.

