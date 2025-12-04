<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Caso {{ $caso->codigo_caso ?? $caso->id }} - UDINIT</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/dashboard/index.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/navbar/navbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/components/components.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/capturista/capturista.css') }}" />
</head>
<body>
  <!-- NAVBAR -->
  @include('partials.navbar')

  <!-- CONTENIDO PRINCIPAL -->
  <div class="capturista-container">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 1.5rem;">
      <a href="{{ route('capturista.casos') }}" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.9375rem;">
        ← Volver a Casos
      </a>
    </div>

    <!-- DETALLE DEL CASO -->
    <div class="caso-detalle">
      <div class="caso-detalle-header">
        <div class="caso-detalle-info">
          <h2>Caso: {{ $caso->codigo_caso ?? 'N/A' }}</h2>
          <div class="caso-detalle-meta">
            <div class="caso-meta-item">
              <span class="caso-meta-label">Estado</span>
              <div class="estado-badge {{ $caso->estado == 'activo' ? 'estado-activo' : ($caso->estado == 'en_progreso' ? 'estado-progreso' : 'estado-finalizado') }}">
                <div class="estado-icono"></div>
                <span>{{ ucfirst(str_replace('_', ' ', $caso->estado)) }}</span>
              </div>
            </div>
            <div class="caso-meta-item">
              <span class="caso-meta-label">Fecha de Creación</span>
              <span class="caso-meta-value">{{ \Carbon\Carbon::parse($caso->fecha_creacion)->format('d/m/Y') }}</span>
            </div>
            @if($caso->prioridad)
            <div class="caso-meta-item">
              <span class="caso-meta-label">Prioridad</span>
              <span class="caso-meta-value">{{ ucfirst($caso->prioridad) }}</span>
            </div>
            @endif
          </div>
        </div>
      </div>
      
      <div class="caso-detalle-body">
        <h3 style="font-size: 1rem; font-weight: 600; color: rgba(255,255,255,0.9); margin: 0 0 0.75rem 0;">Descripción</h3>
        <p class="caso-detalle-description">{{ $caso->descripcion }}</p>
      </div>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
      <div class="tabs-header">
        <button class="tab-btn active" data-tab="evidencias">
          Evidencias ({{ $evidencias->count() }})
        </button>
        <button class="tab-btn" data-tab="reportes">
          Reportes
        </button>
      </div>

      <!-- TAB: EVIDENCIAS -->
      <div class="tab-content active" id="tab-evidencias">
        <div class="evidencias-header">
          <h3>Evidencias del Caso</h3>
          <button class="btn btn-primary" id="btnAgregarEvidencia">
            <span class="btn-icon">
              <svg viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 11h3v-2h-3V8h-2v3H8v2h3v3h2Z" />
              </svg>
            </span>
            Agregar Evidencia
          </button>
        </div>

        <div class="evidencias-list" id="evidenciasList">
          @forelse($evidencias as $evidencia)
            <div class="evidencia-item" data-evidencia-id="{{ $evidencia->id_evidencia }}">
              <div class="evidencia-header">
                <div class="evidencia-tipo">
                  <span class="evidencia-icon">
                    <svg viewBox="0 0 24 24">
                      <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
                    </svg>
                  </span>
                  {{ $evidencia->tipo }}
                </div>
                <div class="evidencia-actions">
                  <button class="evidencia-btn evidencia-btn-edit" onclick="editarEvidencia({{ $evidencia->id_evidencia }})">
                    Editar
                  </button>
                  <button class="evidencia-btn evidencia-btn-delete" onclick="eliminarEvidencia({{ $evidencia->id_evidencia }})">
                    Eliminar
                  </button>
                </div>
              </div>
              <p class="evidencia-descripcion">{{ $evidencia->descripcion }}</p>
              <div class="evidencia-footer">
                <span>Registrada: {{ \Carbon\Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i') }}</span>
                <span>ID: {{ $evidencia->id_evidencia }}</span>
              </div>
            </div>
          @empty
            <div class="empty-state">
              <div class="empty-state-icon">
                <svg viewBox="0 0 24 24">
                  <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
                </svg>
              </div>
              <h3 class="empty-state-title">No hay evidencias</h3>
              <p class="empty-state-description">Aún no se han agregado evidencias a este caso.</p>
            </div>
          @endforelse
        </div>
      </div>

      <!-- TAB: REPORTES -->
      <div class="tab-content" id="tab-reportes">
        <div class="evidencias-header" style="margin-bottom: 2rem;">
          <h3>Generar Reportes</h3>
        </div>

        <!-- Botones de generación de reportes -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
          <button class="btn btn-primary" onclick="generarReporteCompleto()">
            <span class="btn-icon">
              <svg viewBox="0 0 24 24">
                <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
              </svg>
            </span>
            Reporte Completo
          </button>
          <button class="btn btn-primary" onclick="generarReporteEvidencias()">
            <span class="btn-icon">
              <svg viewBox="0 0 24 24">
                <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
              </svg>
            </span>
            Reporte de Evidencias
          </button>
          <button class="btn btn-secondary" onclick="mostrarFormularioReportePersona()">
            Reporte de Persona
          </button>
          <button class="btn btn-secondary" onclick="mostrarFormularioReporteDominio()">
            Reporte de Dominio
          </button>
          <button class="btn btn-secondary" onclick="mostrarFormularioReporteEmail()">
            Reporte de Email
          </button>
          <button class="btn btn-secondary" onclick="mostrarFormularioReporteTelefono()">
            Reporte de Teléfono
          </button>
        </div>

        <!-- Lista de reportes generados -->
        <div class="evidencias-header">
          <h3>Reportes Generados</h3>
          <button class="btn btn-secondary" onclick="cargarReportes()">
            <span class="btn-icon">
              <svg viewBox="0 0 24 24">
                <path d="M17.65 6.35A7.958 7.958 0 0 0 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0 1 12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
            </span>
            Actualizar
          </button>
        </div>
        <div class="reportes-grid" id="reportesList">
          <div class="empty-state" style="grid-column: 1 / -1;">
            <div class="empty-state-icon">
              <svg viewBox="0 0 24 24">
                <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
              </svg>
            </div>
            <h3 class="empty-state-title">No hay reportes generados</h3>
            <p class="empty-state-description">Genera tu primer reporte usando los botones de arriba.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // Pasar datos del caso a JavaScript
    window.casoData = {
      id_caso: '{{ $caso->id_caso }}',
      codigo: '{{ $caso->codigo_caso ?? 'N/A' }}'
    };
  </script>
  <script src="{{ asset('assets/dashboard/services/capturista-api.js') }}"></script>
  <script src="{{ asset('assets/dashboard/components/modal.js') }}"></script>
  <script src="{{ asset('assets/dashboard/components/toast.js') }}"></script>
  <script src="{{ asset('assets/dashboard/components/loading.js') }}"></script>
  <script src="{{ asset('assets/dashboard/navbar/navbar.js') }}"></script>
  <script src="{{ asset('assets/dashboard/capturista/evidencias.js') }}"></script>
  <script src="{{ asset('assets/dashboard/capturista/reportes.js') }}"></script>
  <script src="{{ asset('assets/dashboard/capturista/caso-detalle.js') }}"></script>
</body>
</html>
