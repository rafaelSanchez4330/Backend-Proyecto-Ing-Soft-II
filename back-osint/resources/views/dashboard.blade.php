@extends('layouts.dashboard')

@section('content')
  <!-- SECCIÓN SUPERIOR: CASOS RECIENTES -->
  <section class="casos-section" aria-label="Casos recientes">
    <div class="casos-header">
      <div>
        <div class="casos-title">Casos recientes</div>
        <div class="casos-subtitle">
          Desliza para explorar los incidentes activos y finalizados.
        </div>
      </div>
      <div class="casos-controles">
        <button class="casos-arrow" type="button" id="casosPrev" aria-label="Casos anteriores">
          &#x2039;
        </button>
        <button class="casos-arrow" type="button" id="casosNext" aria-label="Casos siguientes">
          &#x203A;
        </button>
      </div>
    </div>

    <div class="casos-slider" id="casosSlider">
      <div class="casos-track">
        @foreach($casos as $caso)
          <article class="caso-card">
            <header class="caso-header">
              <div
                class="estado-badge {{ $caso->estado == 'activo' ? 'estado-activo' : ($caso->estado == 'en_progreso' ? 'estado-progreso' : 'estado-finalizado') }}">
                <div class="estado-icono"></div>
                <span>{{ ucfirst($caso->estado) }}</span>
              </div>
            </header>
            <div class="caso-body">
              <p>{{ $caso->descripcion }}</p>
            </div>
            <footer class="caso-footer">
              <span>CASE ID:</span> {{ $caso->codigo_caso ?? 'N/A' }}
            </footer>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- PANEL DE CONTROL -->
  <section class="panel-control" aria-label="Panel de control">
    <div class="panel-card">
      <button class="panel-item" type="button" id="btnAdmin">
        <span class="panel-icono">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M12 2a5 5 0 0 1 5 5v1a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5Zm0 11a3 3 0 0 0 3-3V7a3 3 0 0 0-6 0v3a3 3 0 0 0 3 3Zm0 2c3.31 0 6 2.02 6 4.5V21h-2v-1.5c0-1.41-1.79-2.5-4-2.5s-4 1.09-4 2.5V21H6v-1.5C6 17.02 8.69 15 12 15Z" />
          </svg>
        </span>
        <span>Admin</span>
      </button>

      @if(Auth::user()->rol === 'capturista')
        <a href="{{ route('capturista.casos') }}" class="panel-item" style="text-decoration: none; color: white;">
          <span class="panel-icono">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M9 3V2h6v1h4a2 2 0 0 1 2 2v4H3V5a2 2 0 0 1 2-2h4Zm12 7H3v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9Z" />
            </svg>
          </span>
          <span>Mis Casos</span>
        </a>
      @endif

      <button class="panel-item" type="button" id="btnNewCase">
        <span class="panel-icono plus">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 11h3v-2h-3V8h-2v3H8v2h3v3h2Z" />
          </svg>
        </span>
        <span>New case</span>
      </button>

      <button class="panel-item" type="button" id="btnNewUser">
        <span class="panel-icono panel-icono-user-new">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M15 12a4 4 0 1 0-4-4 4.003 4.003 0 0 0 4 4Zm0-6a2 2 0 1 1-2 2 2.003 2.003 0 0 1 2-2ZM8 13a4 4 0 1 0-4-4 4.003 4.003 0 0 0 4 4Zm0-6a2 2 0 1 1-2 2 2.003 2.003 0 0 1 2-2Zm7 7a5.99 5.99 0 0 0-4.78 2.39A6.99 6.99 0 0 1 2.062 15 4.987 4.987 0 0 1 8 12h2.1a6.97 6.97 0 0 0 4.9 2Zm0 2a5 5 0 0 0-5 5h2a3 3 0 0 1 6 0h2a5 5 0 0 0-5-5Z" />
          </svg>
        </span>
        <span>New user</span>
      </button>

      @if(Auth::user()->rol === 'capturista')
        <a href="{{ route('capturista.todas-evidencias') }}" class="panel-item"
          style="text-decoration: none; color: inherit;">
          <span class="panel-icono panel-icono-evidence">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M10 2a6 6 0 0 1 4.8 9.6l4.8 4.8-1.4 1.4-4.8-4.8A6 6 0 1 1 10 2Zm0 2a4 4 0 1 0 4 4 4.005 4.005 0 0 0-4-4Z" />
            </svg>
          </span>
          <span>Evidence</span>
        </a>
      @else
        <button class="panel-item" type="button">
          <span class="panel-icono panel-icono-evidence">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M10 2a6 6 0 0 1 4.8 9.6l4.8 4.8-1.4 1.4-4.8-4.8A6 6 0 1 1 10 2Zm0 2a4 4 0 1 0 4 4 4.005 4.005 0 0 0-4-4Z" />
            </svg>
          </span>
          <span>Evidence</span>
        </button>
      @endif


      <a href="{{ route('reports') }}" class="panel-item" style="text-decoration: none; color: inherit;">
        <span class="panel-icono panel-icono-report">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Zm4 18H6V4h7v5h5v11Z" />
          </svg>
        </span>
        <span>Reports</span>
      </a>

      <button class="panel-item" type="button">
        <span class="panel-icono panel-icono-tools">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M21.6 7.2 18 10.8l-2.8-.7-.7-2.8 3.6-3.6a4.5 4.5 0 0 0-5.7 5.7L5 17.4V21h3.6l7.4-7.4a4.5 4.5 0 0 0 5.6-6.4Z" />
          </svg>
        </span>
        <span>Tools</span>
      </button>
    </div>
  </section>

  <!-- PANEL DE USUARIO -->
  <section class="usuario-section" aria-label="Actividad reciente de usuario">
    <div class="usuario-card">
      <div class="usuario-top">
        <div class="usuario-main">
          <div class="usuario-avatar">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M12 2a5 5 0 0 1 5 5v1a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5Zm0 11a3 3 0 0 0 3-3V7a3 3 0 0 0-6 0v3a3 3 0 0 0 3 3Zm0 2c3.31 0 6 2.02 6 4.5V21h-2v-1.5c0-1.41-1.79-2.5-4-2.5s-4 1.09-4 2.5V21H6v-1.5C6 17.02 8.69 15 12 15Z" />
            </svg>
          </div>
          <div class="usuario-textos">
            <div class="usuario-nombre">{{ Auth::user()->nombre }}</div>
            <div class="usuario-tiempo">Online</div>
          </div>
        </div>

        <div class="usuario-email">
          <div class="usuario-email-icono">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm0 2v.51l8 5.33 8-5.33V6H4Zm16 2.49-7.44 4.96a2 2 0 0 1-2.12 0L3 8.49V18h17Z" />
            </svg>
          </div>
          <span>{{ Auth::user()->email }}</span>
        </div>
      </div>

      <div class="usuario-bottom">
        <span>New Tool: </span>
        <a href="https://haveibeenpwned.com/" target="_blank" rel="noopener noreferrer">
          https://haveibeenpwned.com/
        </a>
      </div>
    </div>
  </section>

  <!-- MODAL CREAR REPORTE -->
  <div class="modal-overlay" id="modalReporte">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalReporteTitulo">
      <div class="modal-header">
        <div class="modal-title" id="modalReporteTitulo">Crear reporte</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body">
        <div class="campo">
          <label for="reporteCaso">Caso relacionado</label>
          <select id="reporteCaso">
            <!-- opciones generadas desde JS -->
          </select>
        </div>

        <div class="campo">
          <label for="reporteTipo">Tipo</label>
          <select id="reporteTipo">
            <option value="Incidente">Incidente</option>
            <option value="Alerta">Alerta</option>
            <option value="Análisis">Análisis</option>
            <option value="Otro">Otro</option>
          </select>
        </div>

        <div class="campo">
          <label for="reporteSeveridad">Severidad</label>
          <select id="reporteSeveridad">
            <option value="Baja">Baja</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
            <option value="Crítica">Crítica</option>
          </select>
        </div>

        <div class="campo modal-body-full">
          <label for="reporteResumen">Resumen</label>
          <textarea id="reporteResumen" placeholder="Resumen ejecutivo del reporte..."></textarea>
        </div>

        <div class="campo modal-body-full">
          <label for="reporteDetalle">Detalle técnico</label>
          <textarea id="reporteDetalle" placeholder="Describe hallazgos, evidencia, acciones tomadas..."></textarea>
        </div>

        <div class="campo modal-body-full">
          <label for="reporteAdjuntos">Adjuntar evidencia</label>
          <input type="file" id="reporteAdjuntos" multiple />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cancelar</button>
        <button class="btn btn-primario" type="button" id="btnCrearReporte">Crear reporte</button>
      </div>
    </div>
  </div>
  <!-- MODAL TOOLS -->
      <div id="modalTools" class="modal-overlay">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalToolsTitulo">
          <div class="modal-header">
            <div class="modal-title" id="modalToolsTitulo">Gestión de Herramientas</div>
            <button class="modal-close" type="button" data-modal-close>&times;</button>
          </div>

          <div class="modal-body modal-body-tools">
            <!-- Formulario -->
            <form id="formAgregarTool" class="form-modal">
              <div class="form-group">
                <label for="toolNombre">Nombre de la herramienta</label>
                <input type="text" id="toolNombre" name="toolNombre" required />
              </div>

              <div class="form-group">
                <label for="toolUrl">Enlace / URL</label>
                <input type="url" id="toolUrl" name="toolUrl" placeholder="https://ejemplo.com" required />
              </div>

              <div class="form-group">
                <label for="toolCategoria">Categoría</label>
                <select id="toolCategoria" name="toolCategoria" required>
                  <option value="">Seleccione una opción</option>
                  <option value="none">Sin categoría</option>
                  <option value="other">Otra categoría...</option>
                </select>
              </div>

              <!-- Input para nueva categoría (visible sólo si elige "Otra") -->
              <div class="form-group hidden" id="categoriaNuevaGroup">
                <label for="categoriaNueva">Nueva categoría</label>
                <input type="text" id="categoriaNueva" name="categoriaNueva" placeholder="Escribe la categoría" />
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-primario">Agregar herramienta</button>
                <button type="button" class="btn btn-secundario" data-modal-close>Cerrar</button>
              </div>
            </form>

            <hr class="modal-sep">

            <!-- Lista de herramientas -->
            <div class="tools-list" id="toolsLista">
              <!-- items --> 
            </div>
          </div>

          <div class="modal-footer">
            <small class="muted">Tip: Puedes pegar un enlace válido (https://) para acceder rápido.</small>
          </div>
        </div>
      </div>
  <!-- MODAL ADMIN -->
  <div class="modal-overlay" id="modalAdmin">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalAdminTitulo">
      <div class="modal-header">
        <div class="modal-title" id="modalAdminTitulo">Admin - usuarios e incidentes</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body modal-body-admin">
        <div class="modal-col">
          <h3>Usuarios</h3>
          <ul id="adminUsuariosLista" class="admin-usuarios-lista"></ul>
        </div>
        <div class="modal-col">
          <h3>Incidentes</h3>
          <div id="adminCasosLista" class="admin-casos-lista"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cerrar</button>
      </div>
    </div>
  </div>

  <!-- MODAL GESTIÓN DE CASOS (TABLA) -->
  <div class="modal-overlay" id="modalGestionCasos" style="z-index: 1050;">
    <div class="modal modal-xl" role="dialog" aria-modal="true" aria-labelledby="modalGestionCasosTitulo"
      style="width: 95%; max-width: 1200px; display: flex; flex-direction: column; max-height: 90vh;">
      <div class="modal-header">
        <div class="modal-title" id="modalGestionCasosTitulo">Administración</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>

      <!-- Tabs de navegación -->
      <div class="modal-tabs"
        style="padding: 0 1.5rem; border-bottom: 1px solid #334155; display: flex; gap: 1rem; flex-shrink: 0;">
        <button class="tab-btn active" data-tab="casos"
          style="background: none; border: none; color: white; padding: 1rem; cursor: pointer; border-bottom: 2px solid #3b82f6;">Casos</button>
        <button class="tab-btn" data-tab="usuarios"
          style="background: none; border: none; color: #94a3b8; padding: 1rem; cursor: pointer;">Usuarios</button>
      </div>

      <div class="modal-body" style="padding: 1.5rem; overflow-y: auto; flex: 1; display: flex; flex-direction: column;">

        <!-- VISTA CASOS -->
        <div id="adminCasosView" style="width: 100%;">
          <!-- Filtros Responsivos (Flexbox) -->
          <div class="filtros-container"
            style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; align-items: center;">
            <input type="text" id="busquedaCasos" placeholder="Buscar por ID, Título..." class="input-filtro"
              style="flex: 1 1 200px; background: #1e293b; border: 1px solid #334155; color: white; padding: 0.5rem; border-radius: 0.375rem;">

            <select id="filtroEstado" class="select-filtro"
              style="flex: 1 1 150px; background: #1e293b; border: 1px solid #334155; color: white; padding: 0.5rem; border-radius: 0.375rem;">
              <option value="">Todos los estados</option>
              <option value="activo">Activo</option>
              <option value="en progreso">En Progreso</option>
              <option value="pausado">Pausado</option>
              <option value="cerrado">Cerrado</option>
            </select>

            <input type="date" id="filtroFecha" class="input-filtro"
              style="flex: 1 1 150px; background: #1e293b; border: 1px solid #334155; color: white; padding: 0.5rem; border-radius: 0.375rem;">
          </div>

          <div class="tabla-container"
            style="overflow-x: auto; border: 1px solid #334155; border-radius: 0.375rem; width: 100%;">
            <table class="tabla-gestion" style="width: 100%; border-collapse: collapse; color: white; min-width: 800px;">
              <thead>
                <tr style="border-bottom: 1px solid #334155; text-align: left; background-color: #0f172a;">
                  <th style="padding: 1rem; white-space: nowrap;">CASE ID</th>
                  <th style="padding: 1rem; white-space: nowrap;">STATUS</th>
                  <th style="padding: 1rem;">TITLE</th>
                  <th style="padding: 1rem; white-space: nowrap;">DATE</th>
                  <th style="padding: 1rem; white-space: nowrap;">ACTIONS</th>
                </tr>
              </thead>
              <tbody id="tablaGestionCasosBody">
                <!-- Rows injected by JS -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- VISTA USUARIOS -->
        <div id="adminUsuariosView" style="display: none; width: 100%;">
          <div class="filtros-container"
            style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; align-items: center;">
            <input type="text" id="busquedaUsuarios" placeholder="Buscar por Nombre, Email..." class="input-filtro"
              style="flex: 1 1 300px; background: #1e293b; border: 1px solid #334155; color: white; padding: 0.5rem; border-radius: 0.375rem;">
          </div>

          <div class="tabla-container"
            style="overflow-x: auto; border: 1px solid #334155; border-radius: 0.375rem; width: 100%;">
            <table class="tabla-gestion" style="width: 100%; border-collapse: collapse; color: white; min-width: 800px;">
              <thead>
                <tr style="border-bottom: 1px solid #334155; text-align: left; background-color: #0f172a;">
                  <th style="padding: 1rem; white-space: nowrap;">ID</th>
                  <th style="padding: 1rem;">NOMBRE</th>
                  <th style="padding: 1rem;">EMAIL</th>
                  <th style="padding: 1rem; white-space: nowrap;">ROL</th>
                  <th style="padding: 1rem; white-space: nowrap;">ACCIONES</th>
                </tr>
              </thead>
              <tbody id="tablaGestionUsuariosBody">
                <!-- Rows injected by JS -->
              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="modal-footer" style="flex-shrink: 0;">
        <button class="btn btn-secundario" type="button" data-modal-close>Cerrar</button>
      </div>
    </div>
  </div>

  <!-- MODAL BITACORA -->
  <div class="modal-overlay" id="modalBitacora" style="z-index: 1150;">
    <div class="modal modal-xl" role="dialog" aria-modal="true" aria-labelledby="modalBitacoraTitulo"
      style="width: 95%; max-width: 1000px; display: flex; flex-direction: column; max-height: 90vh;">
      <div class="modal-header">
        <div class="modal-title" id="modalBitacoraTitulo">Activity Log</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body" style="padding: 1.5rem; overflow-y: auto; display: block;">
        <div class="tabla-container" style="overflow-x: auto; border: 1px solid #334155; border-radius: 0.375rem;">
          <table class="tabla-gestion" style="width: 100%; border-collapse: collapse; color: white; min-width: 700px;">
            <thead>
              <tr style="border-bottom: 1px solid #334155; text-align: left; background-color: #0f172a;">
                <th style="padding: 1rem; border: 1px solid #334155;">Fecha/Hora</th>
                <th style="padding: 1rem; border: 1px solid #334155;">Usuario</th>
                <th style="padding: 1rem; border: 1px solid #334155;">Acción</th>
                <th style="padding: 1rem; border: 1px solid #334155;">Descripción</th>
              </tr>
            </thead>
            <tbody id="tablaBitacoraBody">
              <!-- JS injected -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cerrar</button>
      </div>
    </div>
  </div>

  <!-- MODAL EDITAR USUARIO -->
  <div class="modal-overlay" id="modalEditarUsuario" style="z-index: 1100;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditarUsuarioTitulo">
      <div class="modal-header">
        <div class="modal-title" id="modalEditarUsuarioTitulo">Editar usuario</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editUserId">

        <div class="campo modal-body-full">
          <label for="editNombreUsuario">Nombre completo</label>
          <input type="text" id="editNombreUsuario" placeholder="Ej: Juan Pérez" />
        </div>

        <div class="campo modal-body-full">
          <label for="editEmailUsuario">Correo electrónico</label>
          <input type="email" id="editEmailUsuario" placeholder="usuario@osint.com" />
        </div>

        <div class="campo">
          <label for="editRolUsuario">Rol</label>
          <select id="editRolUsuario">
            <option value="admin">Admin</option>
            <option value="consultor">Consultor</option>
            <option value="capturista">Capturista</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cancelar</button>
        <button class="btn btn-primario" type="button" id="btnGuardarUsuario">Guardar cambios</button>
      </div>
    </div>
  </div>
@endsection
  <!-- MODAL NUEVO CASO -->
  <div class="modal-overlay" id="modalNuevoCaso" style="z-index: 1100;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalNuevoCasoTitulo">
      <div class="modal-header">
        <div class="modal-title" id="modalNuevoCasoTitulo">Nuevo Caso</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body">
        <div class="campo modal-body-full">
          <label for="nuevoTitulo">Título</label>
          <input type="text" id="nuevoTitulo" placeholder="Título del caso" />
        </div>

        <div class="campo">
          <label for="nuevoEstado">Estado</label>
          <select id="nuevoEstado">
            <option value="activo">Activo</option>
            <option value="en_progreso">En Progreso</option>
            <option value="pausado">Pausado</option>
            <option value="cerrado">Cerrado</option>
          </select>
        </div>

        <div class="campo">
          <label for="nuevoEncargado">Encargado (Capturista)</label>
          <select id="nuevoEncargado">
            <option value="">Seleccionar capturista...</option>
          </select>
        </div>

        <div class="campo modal-body-full">
          <label for="nuevoNotas">Descripción / Notas</label>
          <textarea id="nuevoNotas" placeholder="Descripción inicial del caso..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cancelar</button>
        <button class="btn btn-primario" type="button" id="btnCrearCaso">Crear Caso</button>
      </div>
    </div>
  </div>

  <!-- MODAL NUEVO USUARIO -->
  <div class="modal-overlay" id="modalNuevoUsuario" style="z-index: 1100;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalNuevoUsuarioTitulo">
      <div class="modal-header">
        <div class="modal-title" id="modalNuevoUsuarioTitulo">Nuevo Usuario</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body">
        <div class="campo modal-body-full">
          <label for="nuevoNombreUsuario">Nombre completo</label>
          <input type="text" id="nuevoNombreUsuario" placeholder="Ej: Ana López" />
        </div>

        <div class="campo modal-body-full">
          <label for="nuevoCorreoUsuario">Correo electrónico</label>
          <input type="email" id="nuevoCorreoUsuario" placeholder="usuario@osint.com" />
        </div>

        <div class="campo">
          <label for="nuevoPasswordUsuario">Contraseña</label>
          <input type="password" id="nuevoPasswordUsuario" placeholder="******" />
        </div>

        <div class="campo">
          <label for="nuevoCelularUsuario">Celular</label>
          <input type="text" id="nuevoCelularUsuario" placeholder="Ej: 555-123-4567" />
        </div>

        <div class="campo modal-body-full">
          <label for="nuevoRolUsuario">Rol</label>
          <select id="nuevoRolUsuario">
            <option value="capturista">Capturista</option>
            <option value="consultor">Consultor</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cancelar</button>
        <button class="btn btn-primario" type="button" id="btnCrearUsuario">Crear Usuario</button>
      </div>
    </div>
  </div>

  <!-- MODAL EDITAR CASO -->
  <div class="modal-overlay" id="modalEditarCaso" style="z-index: 1100;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditarCasoTitulo">
      <div class="modal-header">
        <div class="modal-title" id="modalEditarCasoTitulo">Editar Caso</div>
        <button class="modal-close" type="button" data-modal-close>&times;</button>
      </div>
      <div class="modal-body">
        <div class="campo">
          <label for="campoId">ID Caso</label>
          <input type="text" id="campoId" readonly class="input-readonly" />
        </div>

        <div class="campo">
          <label for="campoInicioReporte">Fecha Creación</label>
          <input type="text" id="campoInicioReporte" readonly class="input-readonly" />
        </div>

        <div class="campo modal-body-full">
          <label for="campoTitulo">Título</label>
          <input type="text" id="campoTitulo" />
        </div>

        <div class="campo">
          <label for="campoEstado">Estado</label>
          <select id="campoEstado">
            <option value="activo">Activo</option>
            <option value="en_progreso">En Progreso</option>
            <option value="pausado">Pausado</option>
            <option value="cerrado">Cerrado</option>
          </select>
        </div>

        <div class="campo">
          <label for="campoEncargado">Encargado Actual</label>
          <input type="text" id="campoEncargado" readonly class="input-readonly" />
        </div>

        <div class="campo modal-body-full">
          <label for="campoNotas">Descripción / Notas</label>
          <textarea id="campoNotas"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secundario" type="button" data-modal-close>Cancelar</button>
        <button class="btn btn-primario" type="button" id="btnGuardarCaso">Guardar Cambios</button>
      </div>
    </div>
  </div>

@endsection
