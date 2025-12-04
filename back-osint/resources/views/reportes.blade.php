@extends('layouts.dashboard')

@section('content')
  <!-- ENCABEZADO DE REPORTES -->
  <section class="reportes-header" aria-label="Encabezado de reportes">
    <div class="reportes-title-section">
      <h1 class="reportes-main-title">Reportes de Casos</h1>
      <p class="reportes-subtitle">
        Visualiza y descarga todos los reportes generados de investigación OSINT
      </p>
    </div>
  </section>

  <!-- ESTADÍSTICAS RÁPIDAS -->
  <section class="reportes-stats" aria-label="Estadísticas de reportes">
    <div class="stat-card">
      <div class="stat-icon stat-icon-total">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
        </svg>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ count($reportes) }}</div>
        <div class="stat-label">Total de Reportes</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon stat-icon-recent">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm1-13h-2v6l5.2 3.2.8-1.3-4-2.4Z" />
        </svg>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ count(array_filter($reportes, function($r) { return $r['fecha'] > strtotime('-7 days'); })) }}</div>
        <div class="stat-label">Últimos 7 días</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon stat-icon-size">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Zm0 16H5V5h14Z" />
        </svg>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ number_format(array_sum(array_column($reportes, 'tamano')) / 1024, 0) }} KB</div>
        <div class="stat-label">Tamaño Total</div>
      </div>
    </div>
  </section>

  <!-- LISTA DE REPORTES -->
  <section class="reportes-list-section" aria-label="Lista de reportes">
    @if(count($reportes) > 0)
      <div class="reportes-table-container">
        <table class="reportes-table">
          <thead>
            <tr>
              <th>Caso</th>
              <th>Tipo de Reporte</th>
              <th>Estado</th>
              <th>Fecha de Generación</th>
              <th>Tamaño</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($reportes as $reporte)
            <tr class="reporte-row">
              <td>
                <div class="caso-info">
                  <div class="caso-id">ID: {{ $reporte['caso_id'] }}</div>
                  <div class="caso-nombre">{{ $reporte['caso_nombre'] }}</div>
                </div>
              </td>
              <td>
                <span class="tipo-badge">{{ $reporte['tipo'] }}</span>
              </td>
              <td>
                <span class="estado-badge estado-{{ $reporte['caso_estado'] }}">
                  {{ ucfirst(str_replace('_', ' ', $reporte['caso_estado'])) }}
                </span>
              </td>
              <td>
                <div class="fecha-info">
                  {{ date('d/m/Y', $reporte['fecha']) }}
                  <span class="fecha-hora">{{ date('H:i', $reporte['fecha']) }}</span>
                </div>
              </td>
              <td>
                <span class="tamano-info">{{ number_format($reporte['tamano'] / 1024, 1) }} KB</span>
              </td>
              <td>
                <div class="acciones-grupo">
                  <a href="{{ route('reportes.descargar', $reporte['nombre_archivo']) }}" 
                     class="btn-accion btn-descargar" 
                     title="Descargar reporte">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M5 20h14v-2H5v2ZM19 9h-4V3H9v6H5l7 7 7-7Z" />
                    </svg>
                    Descargar
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="reportes-empty">
        <div class="empty-icon">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm0 2v16h12V9h-5V4H6Zm7 0v3h3Z" />
          </svg>
        </div>
        <h3>No hay reportes disponibles</h3>
        <p>Los reportes generados aparecerán aquí</p>
      </div>
    @endif
  </section>

  <style>
    /* Encabezado de Reportes */
    .reportes-header {
      margin-bottom: 2rem;
    }

    .reportes-title-section {
      text-align: left;
    }

    .reportes-main-title {
      font-size: 2rem;
      font-weight: 700;
      color: #111827;
      margin: 0 0 0.5rem 0;
      letter-spacing: -0.02em;
    }

    .reportes-subtitle {
      font-size: 1rem;
      color: #6b7280;
      margin: 0;
    }

    /* Estadísticas */
    .reportes-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      padding: 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      border-color: #d1d5db;
    }

    .stat-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .stat-icon svg {
      width: 28px;
      height: 28px;
      fill: white;
    }

    .stat-icon-total {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon-recent {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon-size {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-content {
      flex: 1;
    }

    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: #111827;
      line-height: 1.2;
    }

    .stat-label {
      font-size: 0.875rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }

    /* Tabla de Reportes */
    .reportes-list-section {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      padding: 1.5rem;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .reportes-table-container {
      overflow-x: auto;
    }

    .reportes-table {
      width: 100%;
      border-collapse: collapse;
    }

    .reportes-table thead th {
      text-align: left;
      padding: 1rem;
      font-size: 0.875rem;
      font-weight: 600;
      color: #374151;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 2px solid #e5e7eb;
      background: #f9fafb;
    }

    .reportes-table tbody tr {
      border-bottom: 1px solid #f3f4f6;
      transition: all 0.2s ease;
    }

    .reportes-table tbody tr:hover {
      background: #f9fafb;
    }

    .reportes-table tbody td {
      padding: 1rem;
      color: #111827;
    }

    .caso-info {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }

    .caso-id {
      font-size: 0.75rem;
      color: #6b7280;
      font-weight: 500;
    }

    .caso-nombre {
      font-size: 0.875rem;
      color: #111827;
      font-weight: 600;
    }

    .tipo-badge {
      display: inline-block;
      padding: 0.375rem 0.75rem;
      background: #ede9fe;
      border: 1px solid #c4b5fd;
      border-radius: 8px;
      font-size: 0.75rem;
      font-weight: 600;
      color: #6d28d9;
    }

    .estado-badge {
      display: inline-block;
      padding: 0.375rem 0.75rem;
      border-radius: 8px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: capitalize;
    }

    .estado-activo {
      background: #d1fae5;
      border: 1px solid #6ee7b7;
      color: #065f46;
    }

    .estado-en_progreso {
      background: #fef3c7;
      border: 1px solid #fcd34d;
      color: #92400e;
    }

    .estado-finalizado {
      background: #e5e7eb;
      border: 1px solid #d1d5db;
      color: #374151;
    }

    .fecha-info {
      display: flex;
      flex-direction: column;
      gap: 0.125rem;
      color: #111827;
    }

    .fecha-hora {
      font-size: 0.75rem;
      color: #6b7280;
    }

    .tamano-info {
      font-size: 0.875rem;
      color: #374151;
      font-weight: 500;
    }

    .acciones-grupo {
      display: flex;
      gap: 0.5rem;
    }

    .btn-accion {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      font-size: 0.875rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-accion svg {
      width: 16px;
      height: 16px;
      fill: currentColor;
    }

    .btn-descargar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
    }

    .btn-descargar:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    /* Estado vacío */
    .reportes-empty {
      text-align: center;
      padding: 4rem 2rem;
    }

    .empty-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      background: #f3f4f6;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .empty-icon svg {
      width: 40px;
      height: 40px;
      fill: #9ca3af;
    }

    .reportes-empty h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #111827;
      margin: 0 0 0.5rem 0;
    }

    .reportes-empty p {
      font-size: 0.875rem;
      color: #6b7280;
      margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .reportes-stats {
        grid-template-columns: 1fr;
      }

      .reportes-table-container {
        overflow-x: auto;
      }

      .reportes-table {
        min-width: 800px;
      }
    }
  </style>
@endsection
