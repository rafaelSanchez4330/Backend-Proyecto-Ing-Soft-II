# Integración con Alexa Skills

Esta documentación explica cómo configurar y usar la integración de Alexa Skills con el sistema OSINT para consultar reportes de casos mediante comandos de voz.

## 📋 Requisitos Previos

1. **Cuenta de Amazon Developer**
   - Registrarse en [Amazon Developer Console](https://developer.amazon.com/)
   - Acceso a la consola de Alexa Skills

2. **Proyecto hosteado en Render (o similar)**
   - URL HTTPS pública (requerido por Alexa)
   - Ejemplo: `https://tu-app.onrender.com`

3. **OAuth 2.0 configurado** (para autenticación de usuarios)
   - Sistema de autenticación en tu aplicación
   - Endpoints de autorización y token

---

## 🔧 Configuración en Amazon Developer Console

### Paso 1: Crear la Skill

1. Ve a [Alexa Developer Console](https://developer.amazon.com/alexa/console/ask)
2. Clic en **Create Skill**
3. Nombre de la skill: `OSINT Reporter` (o el nombre que prefieras)
4. Idioma: **Spanish (ES)**
5. Modelo: **Custom**
6. Hosting: **Provision your own**
7. Clic en **Create skill**

### Paso 2: Configurar el Modelo de Interacción

#### Invocation Name
- Configurar en la sección **Invocations > Skill Invocation Name**
- Nombre sugerido: `osint` o `reportes osint`
- Los usuarios dirán: "Alexa, abre osint"

#### Intents

Agregar los siguientes intents personalizados:

**1. GetCaseReportIntent**
```
Slot: CaseCode (tipo: AMAZON.AlphaNumeric)

Sample Utterances:
- dame el reporte del caso {CaseCode}
- reporte del caso {CaseCode}
- información del caso {CaseCode}
- consulta el caso {CaseCode}
- quiero el reporte del {CaseCode}
- busca el caso {CaseCode}
```

**2. GetActiveCasesIntent**
```
Sample Utterances:
- cuántos casos activos hay
- número de casos activos
- casos activos
- casos en proceso
- casos abiertos
```

#### Intents Built-in (ya incluidos)
- **AMAZON.HelpIntent**: Ayuda al usuario
- **AMAZON.CancelIntent**: Cancelar operación
- **AMAZON.StopIntent**: Detener la skill

### Paso 3: Configurar el Endpoint

1. En el menú lateral, ir a **Endpoint**
2. Seleccionar **HTTPS**
3. **Default Region**: Agregar tu URL
   ```
   https://tu-app.onrender.com/api/alexa/webhook
   ```
4. **SSL Certificate Type**: Seleccionar
   ```
   My development endpoint is a sub-domain of a domain that has a wildcard certificate from a certificate authority
   ```
5. Guardar endpoints

### Paso 4: Configurar Account Linking (Autenticación)

1. En el menú lateral, ir a **Account Linking**
2. Activar el toggle **Do you allow users to create an account or link to an existing account?**

3. **Authorization URI**: URL donde el usuario inicia sesión
   ```
   https://tu-app.onrender.com/oauth/authorize
   ```

4. **Access Token URI**: URL para obtener el token
   ```
   https://tu-app.onrender.com/oauth/token
   ```

5. **Client ID**: Tu client ID de OAuth 2.0
   
6. **Client Secret**: Tu client secret de OAuth 2.0

7. **Client Authentication Scheme**: Seleccionar
   ```
   HTTP Basic (Recommended)
   ```

8. **Scope**: Permisos necesarios (ejemplo: `read:cases`)

9. Guardar la configuración

> **Nota**: Necesitarás implementar los endpoints OAuth en Laravel si aún no los tienes. Puedes usar [Laravel Passport](https://laravel.com/docs/passport) para esto.

### Paso 5: Build del Modelo

1. Clic en **Build Model** en la parte superior
2. Esperar a que compile (puede tardar 1-2 minutos)
3. Verificar que muestre "Build Successful"

---

## 🧪 Testing

### Opción 1: Test en Amazon Developer Console

1. Ir a la pestaña **Test** en la parte superior
2. Activar testing: "Development" en el dropdown
3. Probar comandos:
   ```
   Texto: abre osint
   Respuesta esperada: "Bienvenido al sistema OSINT..."
   
   Texto: dame el reporte del caso ABC123
   Respuesta esperada: "Reporte del caso ABC123..."
   ```

### Opción 2: Test con Dispositivo Alexa Real

Si tienes un dispositivo Amazon Echo:

1. Asegúrate de usar la misma cuenta de Amazon
2. La skill aparecerá automáticamente en modo desarrollo
3. Di: **"Alexa, abre osint"**

### Opción 3: Test con Postman/cURL

Para probar el endpoint directamente:

```bash
curl -X POST https://tu-app.onrender.com/api/alexa/webhook \
  -H "Content-Type: application/json" \
  -d '{
    "version": "1.0",
    "session": {
      "new": true,
      "sessionId": "test-session",
      "user": {
        "userId": "test-user",
        "accessToken": "your-test-token"
      }
    },
    "request": {
      "type": "IntentRequest",
      "requestId": "test-request-123",
      "timestamp": "2024-12-03T22:00:00Z",
      "intent": {
        "name": "GetCaseReportIntent",
        "slots": {
          "CaseCode": {
            "name": "CaseCode",
            "value": "ABC123"
          }
        }
      }
    }
  }'
```

**Respuesta esperada:**
```json
{
  "version": "1.0",
  "response": {
    "outputSpeech": {
      "type": "PlainText",
      "text": "Reporte del caso ABC123. Estado: Activo..."
    },
    "shouldEndSession": false
  }
}
```

---

## 💬 Ejemplos de Conversación

**Ejemplo 1: Consultar reporte de caso**
```
Usuario: "Alexa, abre osint"
Alexa:   "Bienvenido al sistema OSINT. Puedes pedirme el reporte de un caso..."

Usuario: "Dame el reporte del caso ABC123"
Alexa:   "Reporte del caso ABC123. Estado: Activo. Descripción: [descripción]. 
          Asignado a: Juan Pérez. Tiene 5 evidencias registradas y 12 actividades. 
          Creado el 1 de diciembre de 2024. ¿Necesitas información de otro caso?"

Usuario: "Cuántos casos activos hay"
Alexa:   "Actualmente tienes 15 casos activos. ¿Deseas el reporte de algún caso específico?"
```

**Ejemplo 2: Solicitar ayuda**
```
Usuario: "Alexa, abre osint"
Alexa:   "Bienvenido al sistema OSINT..."

Usuario: "Ayuda"
Alexa:   "Puedes pedirme el reporte de un caso diciendo: dame el reporte del caso 
          seguido del código. También puedes preguntarme cuántos casos activos hay. 
          ¿Qué deseas hacer?"
```

---

## 🔐 Seguridad

### Account Linking

La autenticación se maneja mediante **OAuth 2.0 Account Linking**:

1. **Primera vez**: El usuario vincula su cuenta en la app de Alexa
2. **Access Token**: Alexa envía el token en cada petición
3. **Validación**: El controller verifica el token para acceder a datos

### Validación de Peticiones

El `AlexaController` incluye validación básica:
- ✅ Verificación de timestamp (peticiones no mayores a 150 segundos)
- ✅ Validación de estructura del request
- ⚠️ **Producción**: Implementar verificación de firma digital

Para producción, agregar verificación de firma según [documentación de Amazon](https://developer.amazon.com/docs/custom-skills/host-a-custom-skill-as-a-web-service.html#checking-the-signature-of-the-request).

---

## 📊 Estructura de Datos

### Request de Alexa (entrante)

```json
{
  "version": "1.0",
  "session": {
    "new": true,
    "sessionId": "amzn1.echo-api.session...",
    "user": {
      "userId": "amzn1.ask.account...",
      "accessToken": "oauth-token-aqui"
    }
  },
  "request": {
    "type": "IntentRequest",
    "requestId": "amzn1.echo-api.request...",
    "timestamp": "2024-12-03T22:00:00Z",
    "intent": {
      "name": "GetCaseReportIntent",
      "slots": {
        "CaseCode": {
          "name": "CaseCode",
          "value": "ABC123"
        }
      }
    }
  }
}
```

### Response al Alexa (saliente)

```json
{
  "version": "1.0",
  "response": {
    "outputSpeech": {
      "type": "PlainText",
      "text": "Reporte del caso ABC123..."
    },
    "shouldEndSession": false,
    "card": {
      "type": "LinkAccount"
    }
  }
}
```

---

## 🚀 Deployment en Render

### Variables de Entorno

Asegúrate de configurar en Render:

```env
APP_URL=https://tu-app.onrender.com
DB_CONNECTION=pgsql
DB_HOST=tu-db-host
DB_DATABASE=tu-db-name
DB_USERNAME=tu-db-user
DB_PASSWORD=tu-db-password

# OAuth (si usas Laravel Passport)
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=tu-client-id
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=tu-client-secret
```

### Build y Deploy

1. Push a GitHub en la rama `modulo-reportes`
2. En Render, conecta el repositorio
3. Configurar:
   - **Build Command**: `composer install && php artisan migrate --force`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=8080`
4. Deploy automático una vez hosteado

---

## 🐛 Troubleshooting

### "Por favor, vincula tu cuenta"

**Problema**: Alexa pide vincular cuenta constantemente

**Solución**:
1. Verificar que Account Linking esté configurado correctamente
2. Confirmar que los endpoints OAuth respondan correctamente
3. Revisar logs en Render: `php artisan log:tail`

### "No encontré el caso"

**Problema**: No encuentra casos existentes

**Solución**:
1. Verificar que el `codigo_caso` en base de datos coincida exactamente
2. Alexa puede interpretar mal acentos o caracteres especiales
3. Probar con códigos alfanuméricos simples (ej: "ABC123")

### Errores de timeout

**Problema**: Alexa se queja de timeout

**Solución**:
1. Las respuestas deben ser < 8 segundos
2. Optimizar queries de base de datos
3. Considerar cacheo para consultas frecuentes

---

## 📚 Referencias

- [Alexa Skills Kit Documentation](https://developer.amazon.com/docs/ask-overviews/build-skills-with-the-alexa-skills-kit.html)
- [Account Linking Guide](https://developer.amazon.com/docs/account-linking/understand-account-linking.html)
- [Laravel Passport OAuth](https://laravel.com/docs/passport)
- [Render Deployment](https://render.com/docs)

---

## 🎯 Próximos Pasos

1. **Implementar OAuth 2.0** en Laravel (si no lo tienes)
2. **Probar Account Linking** con tu cuenta de Amazon
3. **Expandir intents**: Agregar más consultas (evidencias, actividades, etc.)
4. **Agregar validación de firma** para producción
5. **Certificación de la skill** para hacerla pública

¿Preguntas? Revisa los logs en `storage/logs/laravel.log`
