<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte - Caso #{{ $caso->codigo_caso ?? $caso->id_caso }}</title>
    <style>
        @media print {
            @page {
                margin: 2cm;
            }

            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        .print-button:hover {
            background: #2563eb;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3b82f6;
        }

        .header h1 {
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #64748b;
        }

        .section {
            margin-bottom: 30px;
            break-inside: avoid;
        }

        .section-title {
            font-size: 20px;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 15px;
        }

        .info-item {
            padding: 12px;
            background: #f8fafc;
            border-left: 3px solid #3b82f6;
            border-radius: 4px;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge.activo {
            background: #dcfce7;
            color: #166534;
        }

        .badge.en_progreso {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge.finalizado {
            background: #f1f5f9;
            color: #475569;
        }

        .descripcion-box {
            background: #f8fafc;
            padding: 15px;
            border-left: 3px solid #94a3b8;
            border-radius: 4px;
            margin-top: 10px;
        }

        .user-list,
        .evidence-list {
            list-style: none;
        }

        .user-item,
        .evidence-item {
            padding: 12px;
            margin-bottom: 10px;
            background: #f8fafc;
            border-left: 3px solid #3b82f6;
            border-radius: 4px;
            break-inside: avoid;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .user-role {
            font-size: 12px;
            color: #3b82f6;
            font-weight: 600;
            text-transform: uppercase;
        }

        .user-date {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .evidence-type {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .evidence-desc {
            color: #1e293b;
            margin-bottom: 6px;
        }

        .evidence-date {
            font-size: 12px;
            color: #64748b;
        }

        .timeline {
            position: relative;
            padding-left: 25px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            break-inside: avoid;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -21px;
            top: 6px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid #fff;
        }

        .timeline-date {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .timeline-activity {
            color: #1e293b;
        }

        .no-data {
            text-align: center;
            color: #94a3b8;
            font-style: italic;
            padding: 20px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>

    <div class="header">
        <h1>📋 Reporte del Caso</h1>
        <div class="subtitle">Caso #{{ $caso->codigo_caso ?? $caso->id_caso }}</div>
    </div>

    <!-- Información General -->
    <div class="section">
        <div class="section-title">📄 Información General</div>
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
                <div class="info-value">{{ \Carbon\Carbon::parse($caso->fecha_creacion)->format('d/m/Y H:i') }}</div>
            </div>
            @if($caso->fecha_actualizacion)
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Última Actualización</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($caso->fecha_actualizacion)->format('d/m/Y H:i') }}
                    </div>
                </div>
            @endif
        </div>
        @if($caso->descripcion)
            <div>
                <div class="info-label">Descripción</div>
                <div class="descripcion-box">{{ $caso->descripcion }}</div>
            </div>
        @endif
    </div>

    <!-- Usuarios Asignados -->
    <div class="section">
        <div class="section-title">👥 Usuarios Asignados ({{ count($asignados) }})</div>
        @if(count($asignados) > 0)
            <ul class="user-list">
                @foreach($asignados as $usuario)
                    <li class="user-item">
                        <div class="user-name">{{ $usuario->nombre }}</div>
                        <div class="user-role">{{ ucfirst($usuario->rol) }}</div>
                        <div class="user-date">Asignado:
                            {{ \Carbon\Carbon::parse($usuario->fecha_asignacion)->format('d/m/Y') }}</div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="no-data">No hay usuarios asignados a este caso</div>
        @endif
    </div>

    <!-- Evidencias -->
    <div class="section">
        <div class="section-title">📎 Evidencias ({{ count($evidencias) }})</div>
        @if(count($evidencias) > 0)
            <ul class="evidence-list">
                @foreach($evidencias as $evidencia)
                    <li class="evidence-item">
                        <span class="evidence-type">{{ strtoupper($evidencia->tipo) }}</span>
                        <div class="evidence-desc">{{ $evidencia->descripcion }}</div>
                        <div class="evidence-date">📅
                            {{ \Carbon\Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i') }}</div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="no-data">No hay evidencias registradas para este caso</div>
        @endif
    </div>

    <!-- Historial de Actividades -->
    <div class="section">
        <div class="section-title">📊 Historial de Actividades ({{ count($actividades) }})</div>
        @if(count($actividades) > 0)
            <div class="timeline">
                @foreach($actividades as $actividad)
                    <div class="timeline-item">
                        <div class="timeline-date">📅 {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-activity">{{ $actividad->actividad }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-data">No hay actividades registradas para este caso</div>
        @endif
    </div>

    <div class="footer no-print">
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p>Sistema UDINIT - Case Management</p>
    </div>
</body>

</html>