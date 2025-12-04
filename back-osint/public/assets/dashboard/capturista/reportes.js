/**
 * Lógica para gestión de reportes
 */

/**
 * Generar reporte completo
 */
async function generarReporteCompleto() {
  try {
    loading.show('Generando reporte completo...');

    const response = await capturistaAPI.generarReporteCompleto(window.casoData.id_caso);

    loading.hide();

    if (response.success) {
      toast.success('Reporte completo generado exitosamente');

      // Mostrar preview del reporte
      mostrarPreviewReporte(response.data.contenido, response.data.nombre_archivo);

      // Recargar lista de reportes
      setTimeout(() => cargarReportes(), 500);
    }
  } catch (error) {
    loading.hide();
    toast.error('Error al generar reporte: ' + error.message);
  }
}

/**
 * Generar reporte de evidencias
 */
async function generarReporteEvidencias() {
  try {
    loading.show('Generando reporte de evidencias...');

    const response = await capturistaAPI.generarReporteEvidencias(window.casoData.id_caso);

    loading.hide();

    if (response.success) {
      toast.success('Reporte de evidencias generado exitosamente');
      mostrarPreviewReporte(response.data.contenido, response.data.nombre_archivo);
      setTimeout(() => cargarReportes(), 500);
    }
  } catch (error) {
    loading.hide();
    toast.error('Error al generar reporte: ' + error.message);
  }
}

/**
 * Helper para parsear texto a JSON
 * Espera formato: valor1, valor2, valor3 (una entrada por línea)
 */
function parseTextToJSON(text, keys) {
  if (!text) return null;

  return text.split('\n')
    .filter(line => line.trim() !== '')
    .map(line => {
      const values = line.split(',').map(v => v.trim());
      const obj = {};

      keys.forEach((key, index) => {
        obj[key] = values[index] || 'N/A';
      });

      return obj;
    });
}

/**
 * Mostrar formulario para reporte de persona
 */
function mostrarFormularioReportePersona() {
  const formHTML = `
    <form id="formReportePersona">
      <div class="form-group">
        <label class="form-label form-label-required">Nombre Completo</label>
        <input type="text" class="form-input" id="personaNombre" required placeholder="Ej: Juan Carlos Pérez García">
      </div>
      <div class="form-group">
        <label class="form-label">Aliases</label>
        <input type="text" class="form-input" id="personaAliases" placeholder="Ej: JuanP, JPerez">
      </div>
      <div class="form-group">
        <label class="form-label">Emails</label>
        <input type="text" class="form-input" id="personaEmails" placeholder="Separados por comas">
      </div>
      <div class="form-group">
        <label class="form-label">Teléfonos</label>
        <input type="text" class="form-input" id="personaTelefonos" placeholder="Separados por comas">
      </div>
      <div class="form-group">
        <label class="form-label">Fecha de Nacimiento</label>
        <input type="date" class="form-input" id="personaFechaNacimiento">
      </div>
      <div class="form-group">
        <label class="form-label">Direcciones</label>
        <textarea class="form-textarea" id="personaDirecciones" placeholder="Una por línea"></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Redes Sociales</label>
        <textarea class="form-textarea" id="personaRedes" placeholder="Plataforma, Usuario, URL, Estado&#10;Ej: Facebook, juan.perez, https://fb.com/juan, Activo"></textarea>
        <p class="form-help">Una entrada por línea. Formato: Plataforma, Usuario, URL, Estado</p>
      </div>
    </form>
  `;

  modal.show({
    title: 'Generar Reporte de Persona',
    content: formHTML,
    size: 'large',
    buttons: [
      {
        text: 'Cancelar',
        class: 'modal-btn-secondary'
      },
      {
        text: 'Generar Reporte',
        class: 'modal-btn-primary',
        onClick: async () => {
          const nombre = document.getElementById('personaNombre').value;

          if (!nombre) {
            toast.error('El nombre es requerido');
            return;
          }

          const datos = {
            nombre_completo: nombre,
            aliases: document.getElementById('personaAliases').value,
            emails: document.getElementById('personaEmails').value,
            telefonos: document.getElementById('personaTelefonos').value,
            fecha_nacimiento: document.getElementById('personaFechaNacimiento').value,
            direcciones: document.getElementById('personaDirecciones').value
          };

          // Parsear redes sociales
          const redesText = document.getElementById('personaRedes').value;
          if (redesText) {
            datos.redes_sociales = parseTextToJSON(redesText, ['plataforma', 'usuario', 'url', 'estado']);
          }

          try {
            loading.show('Generando reporte de persona...');

            const response = await capturistaAPI.generarReportePersonalizado(window.casoData.id_caso, {
              tipo_reporte: 'persona',
              datos: datos
            });

            loading.hide();

            if (response.success) {
              toast.success('Reporte de persona generado exitosamente');
              mostrarPreviewReporte(response.data.contenido, response.data.nombre_archivo);
              setTimeout(() => cargarReportes(), 500);
            }
          } catch (error) {
            loading.hide();
            toast.error('Error al generar reporte: ' + error.message);
          }
        },
        closeOnClick: false
      }
    ]
  });
}

/**
 * Mostrar formulario para reporte de dominio
 */
function mostrarFormularioReporteDominio() {
  const formHTML = `
    <form id="formReporteDominio">
      <div class="form-group">
        <label class="form-label form-label-required">Dominio</label>
        <input type="text" class="form-input" id="dominioDominio" required placeholder="ejemplo.com">
      </div>
      <div class="form-group">
        <label class="form-label">IP</label>
        <input type="text" class="form-input" id="dominioIP" placeholder="192.168.1.100">
      </div>
      <div class="form-group">
        <label class="form-label">Registrador</label>
        <input type="text" class="form-input" id="dominioRegistrador" placeholder="GoDaddy LLC">
      </div>
      <div class="form-group">
        <label class="form-label">Fecha de Registro</label>
        <input type="date" class="form-input" id="dominioFechaRegistro">
      </div>
      <div class="form-group">
        <label class="form-label">Name Servers</label>
        <input type="text" class="form-input" id="dominioNameServers" placeholder="ns1.ejemplo.com, ns2.ejemplo.com">
      </div>
      <div class="form-group">
        <label class="form-label">WHOIS</label>
        <textarea class="form-textarea" id="dominioWhois" placeholder="Información WHOIS completa"></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Registros DNS</label>
        <textarea class="form-textarea" id="dominioDNS" placeholder="Tipo, Valor&#10;Ej: A, 192.168.1.100"></textarea>
        <p class="form-help">Una entrada por línea. Formato: Tipo, Valor</p>
      </div>
    </form>
  `;

  modal.show({
    title: 'Generar Reporte de Dominio',
    content: formHTML,
    size: 'large',
    buttons: [
      {
        text: 'Cancelar',
        class: 'modal-btn-secondary'
      },
      {
        text: 'Generar Reporte',
        class: 'modal-btn-primary',
        onClick: async () => {
          const dominio = document.getElementById('dominioDominio').value;

          if (!dominio) {
            toast.error('El dominio es requerido');
            return;
          }

          const datos = {
            dominio: dominio,
            ip: document.getElementById('dominioIP').value,
            registrador: document.getElementById('dominioRegistrador').value,
            fecha_registro: document.getElementById('dominioFechaRegistro').value,
            name_servers: document.getElementById('dominioNameServers').value,
            whois: document.getElementById('dominioWhois').value
          };

          const dnsText = document.getElementById('dominioDNS').value;
          if (dnsText) {
            datos.dns_records = parseTextToJSON(dnsText, ['tipo', 'valor']);
          }

          try {
            loading.show('Generando reporte de dominio...');

            const response = await capturistaAPI.generarReportePersonalizado(window.casoData.id_caso, {
              tipo_reporte: 'dominio',
              datos: datos
            });

            loading.hide();

            if (response.success) {
              toast.success('Reporte de dominio generado exitosamente');
              mostrarPreviewReporte(response.data.contenido, response.data.nombre_archivo);
              setTimeout(() => cargarReportes(), 500);
            }
          } catch (error) {
            loading.hide();
            toast.error('Error al generar reporte: ' + error.message);
          }
        },
        closeOnClick: false
      }
    ]
  });
}

/**
 * Mostrar formulario para reporte de email
 */
function mostrarFormularioReporteEmail() {
  const formHTML = `
    <form id="formReporteEmail">
      <div class="form-group">
        <label class="form-label form-label-required">Email</label>
        <input type="email" class="form-input" id="emailEmail" required placeholder="ejemplo@email.com">
      </div>
      <div class="form-group">
        <label class="form-label">Dominio</label>
        <input type="text" class="form-input" id="emailDominio" placeholder="email.com">
      </div>
      <div class="form-group">
        <label class="form-label">¿Es válido?</label>
        <select class="form-select" id="emailValido">
          <option value="Si">Sí</option>
          <option value="No">No</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Servicios Asociados</label>
        <textarea class="form-textarea" id="emailServicios" placeholder="Nombre, Estado, Detalles&#10;Ej: Facebook, Registrado, Cuenta activa"></textarea>
        <p class="form-help">Una entrada por línea. Formato: Nombre, Estado, Detalles</p>
      </div>
      <div class="form-group">
        <label class="form-label">Brechas de Seguridad</label>
        <textarea class="form-textarea" id="emailBrechas" placeholder="Sitio, Fecha, Datos Comprometidos&#10;Ej: LinkedIn, 2021-06-22, Email y Password"></textarea>
        <p class="form-help">Una entrada por línea. Formato: Sitio, Fecha, Datos</p>
      </div>
    </form>
  `;

  modal.show({
    title: 'Generar Reporte de Email',
    content: formHTML,
    size: 'large',
    buttons: [
      {
        text: 'Cancelar',
        class: 'modal-btn-secondary'
      },
      {
        text: 'Generar Reporte',
        class: 'modal-btn-primary',
        onClick: async () => {
          const email = document.getElementById('emailEmail').value;

          if (!email) {
            toast.error('El email es requerido');
            return;
          }

          const datos = {
            email: email,
            dominio: document.getElementById('emailDominio').value,
            valido: document.getElementById('emailValido').value
          };

          const serviciosText = document.getElementById('emailServicios').value;
          if (serviciosText) {
            datos.servicios = parseTextToJSON(serviciosText, ['nombre', 'estado', 'detalles']);
          }

          const brechasText = document.getElementById('emailBrechas').value;
          if (brechasText) {
            datos.brechas = parseTextToJSON(brechasText, ['sitio', 'fecha', 'datos_comprometidos']);
          }

          try {
            loading.show('Generando reporte de email...');

            const response = await capturistaAPI.generarReportePersonalizado(window.casoData.id_caso, {
              tipo_reporte: 'email',
              datos: datos
            });

            loading.hide();

            if (response.success) {
              toast.success('Reporte de email generado exitosamente');
              mostrarPreviewReporte(response.data.contenido, response.data.nombre_archivo);
              setTimeout(() => cargarReportes(), 500);
            }
          } catch (error) {
            loading.hide();
            toast.error('Error al generar reporte: ' + error.message);
          }
        },
        closeOnClick: false
      }
    ]
  });
}

/**
 * Mostrar formulario para reporte de teléfono
 */
function mostrarFormularioReporteTelefono() {
  const formHTML = `
    <form id="formReporteTelefono">
      <div class="form-group">
        <label class="form-label form-label-required">Número de Teléfono</label>
        <input type="tel" class="form-input" id="telefonoNumero" required placeholder="+52 444 123 4567">
      </div>
      <div class="form-group">
        <label class="form-label">País</label>
        <input type="text" class="form-input" id="telefonoPais" placeholder="México">
      </div>
      <div class="form-group">
        <label class="form-label">Operador</label>
        <input type="text" class="form-input" id="telefonoOperador" placeholder="Telcel">
      </div>
      <div class="form-group">
        <label class="form-label">Tipo</label>
        <select class="form-select" id="telefonoTipo">
          <option value="Móvil">Móvil</option>
          <option value="Fijo">Fijo</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Región</label>
        <input type="text" class="form-input" id="telefonoRegion" placeholder="San Luis Potosí">
      </div>
      <div class="form-group">
        <label class="form-label">Perfiles Asociados</label>
        <textarea class="form-textarea" id="telefonoPerfiles" placeholder="Plataforma, Información&#10;Ej: WhatsApp, Cuenta activa"></textarea>
        <p class="form-help">Una entrada por línea. Formato: Plataforma, Información</p>
      </div>
    </form>
  `;

  modal.show({
    title: 'Generar Reporte de Teléfono',
    content: formHTML,
    size: 'large',
    buttons: [
      {
        text: 'Cancelar',
        class: 'modal-btn-secondary'
      },
      {
        text: 'Generar Reporte',
        class: 'modal-btn-primary',
        onClick: async () => {
          const numero = document.getElementById('telefonoNumero').value;

          if (!numero) {
            toast.error('El número de teléfono es requerido');
            return;
          }

          const datos = {
            numero: numero,
            pais: document.getElementById('telefonoPais').value,
            operador: document.getElementById('telefonoOperador').value,
            tipo: document.getElementById('telefonoTipo').value,
            region: document.getElementById('telefonoRegion').value
          };

          const perfilesText = document.getElementById('telefonoPerfiles').value;
          if (perfilesText) {
            datos.perfiles = parseTextToJSON(perfilesText, ['plataforma', 'informacion']);
          }

          try {
            loading.show('Generando reporte de teléfono...');

            const response = await capturistaAPI.generarReportePersonalizado(window.casoData.id_caso, {
              tipo_reporte: 'telefono',
              datos: datos
            });

            loading.hide();

            if (response.success) {
              toast.success('Reporte de teléfono generado exitosamente');
              mostrarPreviewReporte(response.data.contenido, response.data.nombre_archivo);
              setTimeout(() => cargarReportes(), 500);
            }
          } catch (error) {
            loading.hide();
            toast.error('Error al generar reporte: ' + error.message);
          }
        },
        closeOnClick: false
      }
    ]
  });
}

/**
 * Cargar lista de reportes generados
 */
async function cargarReportes() {
  try {
    const response = await capturistaAPI.listarReportes(window.casoData.id_caso);

    const reportesList = document.getElementById('reportesList');

    if (response.success && response.data.reportes && response.data.reportes.length > 0) {
      reportesList.innerHTML = response.data.reportes.map(reporte => `
        <div class="reporte-card">
          <div class="reporte-header">
            <div class="reporte-icon">
              <svg viewBox="0 0 24 24">
                <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
              </svg>
            </div>
            <div class="reporte-info">
              <h4 class="reporte-nombre">${reporte.nombre}</h4>
              <p class="reporte-tipo">Markdown</p>
            </div>
          </div>
          <div class="reporte-meta">
            <span>${reporte.fecha || 'N/A'}</span>
            <span>${reporte.tamano || 'N/A'}</span>
          </div>
          <div class="reporte-actions">
            <button class="btn btn-primary" onclick="descargarReporte('${reporte.nombre}')">
              Descargar
            </button>
          </div>
        </div>
      `).join('');
    } else {
      reportesList.innerHTML = `
        <div class="empty-state" style="grid-column: 1 / -1;">
          <div class="empty-state-icon">
            <svg viewBox="0 0 24 24">
              <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
            </svg>
          </div>
          <h3 class="empty-state-title">No hay reportes generados</h3>
          <p class="empty-state-description">Genera tu primer reporte usando los botones de arriba.</p>
        </div>
      `;
    }
  } catch (error) {
    console.error('Error al cargar reportes:', error);
  }
}

/**
 * Descargar reporte
 */
async function descargarReporte(nombreArchivo) {
  try {
    loading.show('Descargando reporte...');
    await capturistaAPI.descargarReporte(nombreArchivo);
    loading.hide();
    toast.success('Reporte descargado exitosamente');
  } catch (error) {
    loading.hide();
    toast.error('Error al descargar reporte: ' + error.message);
  }
}

/**
 * Mostrar preview de reporte
 */
function mostrarPreviewReporte(contenido, nombreArchivo) {
  const previewHTML = `
    <div style="max-height: 60vh; overflow-y: auto;">
      <pre style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; white-space: pre-wrap; word-wrap: break-word; font-size: 0.875rem; line-height: 1.6;">${contenido}</pre>
    </div>
  `;

  modal.show({
    title: `Preview: ${nombreArchivo}`,
    content: previewHTML,
    size: 'large',
    buttons: [
      {
        text: 'Cerrar',
        class: 'modal-btn-secondary'
      },
      {
        text: 'Descargar',
        class: 'modal-btn-primary',
        onClick: () => {
          descargarReporte(nombreArchivo);
        }
      }
    ]
  });
}

// Cargar reportes al cargar la página
document.addEventListener('DOMContentLoaded', function () {
  // Cargar reportes si estamos en la tab de reportes
  const tabReportes = document.getElementById('tab-reportes');
  if (tabReportes) {
    cargarReportes();
  }
});
