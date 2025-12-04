<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caso #{{ $caso->codigo_caso ?? $caso->id_caso }} - UDINIT</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/navbar/navbar.css') }}" />
    <style>
        body {
            background: #0f172a;
            color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-nav {
            padding: 0.75rem 1.5rem;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            background: #1e293b;
            color: #f1f5f9;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-nav:hover {
            background: #334155;
            border-color: #475569;
        }

        .btn-pdf {
            background: #dc2626;
            border-color: #b91c1c;
        }

        .btn-pdf:hover {
            background: #b91c1c;
        }

        .btn-obsidian {
            background: #7c3aed;
            border-color: #6d28d9;
        }

        .btn-obsidian:hover {
            background: #6d28d9;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.75rem;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .card h2 {
            font-size: 1.5rem;
            margin: 0 0 1.5rem 0;
            color: #f1f5f9;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 0.75rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.875rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 1rem;
            color: #f1f5f9;
            font-weight: 500;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge.activo {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge.en_progreso {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .badge.finalizado {
            background: rgba(148, 163, 184, 0.1);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .descripcion-box {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 0.5rem;
            line-height: 1.6;
        }

        .usuarios-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .usuario-card {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1.25rem;
        }

        .usuario-nombre {
            font-weight: 600;
            font-size: 1.1rem;
            color: #f1f5f9;
            margin-bottom: 0.5rem;
        }

        .usuario-rol {
            color: #3b82f6;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .usuario-fecha {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .evidencias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        .evidencia-card {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1.25rem;
        }

        .evidencia-tipo {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .evidencia-descripcion {
            color: #cbd5e1;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .evidencia-fecha {
            color: #64748b;
            font-size: 0.875rem;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #334155;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.4rem;
            top: 0.3rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3b82f6;
            border: 3px solid #0f172a;
        }

        .timeline-fecha {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .timeline-actividad {
            color: #f1f5f9;
            line-height: 1.5;
        }

        .no-data {
            text-align: center;
            color: #64748b;
            padding: 2rem;
            font-style: italic;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1 class="page-title">📋 Caso #{{ $caso->codigo_caso ?? $caso->id_caso }}</h1>
        <div class="nav-buttons">
            <button class="btn-nav btn-obsidian"
                onclick="window.location.href='{{ route('reports.obsidian', $caso->id_caso) }}';">
                📝 Descargar Obsidian
            </button>
            <button class="btn-nav btn-pdf"
                onclick="window.location.href='{{ route('reports.pdf', $caso->id_caso) }}';">
                📄 Descargar PDF
            </button>
            <a href="{{ route('reports') }}" class="btn-nav">← Volver a Reportes</a>
        </div>
    </nav>

    <div class="container">
        <!-- Información General -->
        <div class="card">
            <h2>Información General</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Código del Caso</div>
                    <div class="info-value">{{ $caso->codigo_caso ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Estado</div>
                    <div class="info-value">
                        <span class="badge {{ str_replace(' ', '_', strtolower($caso->estado)) }}">
                            {{ ucfirst($caso->estado) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Prioridad</div>
                    <div class="info-value">{{ $caso->prioridad ?? 'Medium' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Creación</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($caso->fecha_creacion)->format('d/m/Y H:i') }}
                    </div>
                </div>
                @if($caso->fecha_actualizacion)
                    <div class="info-item">
                        <div class="info-label">Última Actualización</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($caso->fecha_actualizacion)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @endif
            </div>
            @if($caso->descripcion)
                <div style="margin-top: 1.5rem;">
                    <div class="info-label">Descripción</div>
                    <div class="descripcion-box">{{ $caso->descripcion }}</div>
                </div>
            @endif
        </div>

        <!-- Usuarios Asignados -->
        <div class="card">
            <h2>👥 Usuarios Asignados ({{ count($asignados) }})</h2>
            <div class="usuarios-list">
                @forelse($asignados as $usuario)
                    <div class="usuario-card">
                        <div class="usuario-nombre">{{ $usuario->nombre }}</div>
                        <div class="usuario-rol">{{ ucfirst($usuario->rol) }}</div>
                        <div class="usuario-fecha">
                            Asignado: {{ \Carbon\Carbon::parse($usuario->fecha_asignacion)->format('d/m/Y') }}
                        </div>
                    </div>
                @empty
                    <div class="no-data">No hay usuarios asignados a este caso</div>
                @endforelse
            </div>
        </div>

        <!-- Evidencias -->
        <div class="card">
            <h2>📎 Evidencias ({{ count($evidencias) }})</h2>
            <div class="evidencias-grid">
                @forelse($evidencias as $evidencia)
                    <div class="evidencia-card">
                        <span class="evidencia-tipo">{{ strtoupper($evidencia->tipo) }}</span>
                        <div class="evidencia-descripcion">{{ $evidencia->descripcion }}</div>
                        <div class="evidencia-fecha">
                            📅 {{ \Carbon\Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @empty
                    <div class="no-data">No hay evidencias registradas para este caso</div>
                @endforelse
            </div>
        </div>

        <!-- Timeline de Actividades -->
        <div class="card">
            <h2>📊 Historial de Actividades ({{ count($actividades) }})</h2>
            <div class="timeline">
                @forelse($actividades as $actividad)
                    <div class="timeline-item">
                        <div class="timeline-fecha">
                            📅 {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y H:i') }}
                        </div>
                        <div class="timeline-actividad">{{ $actividad->actividad }}</div>
                    </div>
                @empty
                    <div class="no-data">No hay actividades registradas para este caso</div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/dashboard/navbar/navbar.js') }}"></script>
</body>

</html>