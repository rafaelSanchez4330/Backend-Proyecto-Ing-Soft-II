<?php

namespace App\Services;

use App\Caso;
use App\Evidencia;
use Carbon\Carbon;

class ReporteOsintService
{
    /**
     * Generar reporte completo de un caso en formato Markdown
     */
    public function generarReporteCompleto($caso)
    {
        $markdown = $this->generarEncabezado($caso);
        $markdown .= $this->generarResumenEjecutivo($caso);
        $markdown .= $this->generarInformacionGeneral($caso);
        $markdown .= $this->generarEvidencias($caso);
        $markdown .= $this->generarLineasDeTiempo($caso);
        $markdown .= $this->generarConclusiones($caso);
        
        return $markdown;
    }

    /**
     * Generar encabezado del reporte
     */
    private function generarEncabezado($caso)
    {
        $markdown = "# Reporte de Investigación OSINT\n\n";
        $markdown .= "## Caso: {$caso->nombre}\n\n";
        $markdown .= "---\n\n";
        
        return $markdown;
    }

    /**
     * Generar resumen ejecutivo
     */
    private function generarResumenEjecutivo($caso)
    {
        $totalEvidencias = $caso->evidencias->count();
        $fechaCreacion = Carbon::parse($caso->fecha_creacion)->format('d/m/Y H:i');
        $fechaActualizacion = $caso->fecha_actualizacion 
            ? Carbon::parse($caso->fecha_actualizacion)->format('d/m/Y H:i')
            : 'N/A';
        
        $markdown = "## Resumen Ejecutivo\n\n";
        $markdown .= "| Campo | Valor |\n";
        $markdown .= "|-------|-------|\n";
        $markdown .= "| **ID del Caso** | {$caso->id_caso} |\n";
        $markdown .= "| **Nombre del Caso** | {$caso->nombre} |\n";
        $markdown .= "| **Tipo de Caso** | {$caso->tipo_caso} |\n";
        $markdown .= "| **Estado** | {$caso->estado} |\n";
        $markdown .= "| **Fecha de Creación** | {$fechaCreacion} |\n";
        $markdown .= "| **Última Actualización** | {$fechaActualizacion} |\n";
        $markdown .= "| **Investigador Principal** | {$caso->creador->nombre} |\n";
        $markdown .= "| **Total de Evidencias** | {$totalEvidencias} |\n\n";
        
        return $markdown;
    }

    /**
     * Generar información general del caso
     */
    private function generarInformacionGeneral($caso)
    {
        $markdown = "## Información General\n\n";
        $markdown .= "### Descripción del Caso\n\n";
        $markdown .= "{$caso->descripcion}\n\n";
        
        // Obtener usuarios asignados
        $asignaciones = $caso->asignaciones()->with('usuario')->get();
        if ($asignaciones->count() > 0) {
            $markdown .= "### Equipo Asignado\n\n";
            foreach ($asignaciones as $asignacion) {
                $fechaAsignacion = Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y');
                $markdown .= "- **{$asignacion->usuario->nombre}** ({$asignacion->usuario->rol}) - Asignado el {$fechaAsignacion}\n";
            }
            $markdown .= "\n";
        }
        
        return $markdown;
    }

    /**
     * Generar sección de evidencias
     */
    private function generarEvidencias($caso)
    {
        $evidencias = $caso->evidencias;
        
        if ($evidencias->count() === 0) {
            return "## Evidencias\n\nNo se han registrado evidencias para este caso.\n\n";
        }
        
        $markdown = "## Evidencias Recopiladas\n\n";
        
        // Agrupar evidencias por tipo
        $evidenciasPorTipo = $evidencias->groupBy('tipo');
        
        foreach ($evidenciasPorTipo as $tipo => $evidenciasTipo) {
            $markdown .= "### {$tipo}\n\n";
            
            foreach ($evidenciasTipo as $evidencia) {
                $fechaCreacion = Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i');
                $markdown .= "#### Evidencia #{$evidencia->id_evidencia}\n\n";
                $markdown .= "**Fecha de Captura:** {$fechaCreacion}\n\n";
                $markdown .= "**Descripción:**\n\n";
                $markdown .= "```\n";
                $markdown .= $evidencia->descripcion . "\n";
                $markdown .= "```\n\n";
                $markdown .= "---\n\n";
            }
        }
        
        return $markdown;
    }

    /**
     * Generar línea de tiempo
     */
    private function generarLineasDeTiempo($caso)
    {
        $markdown = "## Línea de Tiempo\n\n";
        
        // Crear una línea de tiempo con todas las actividades
        $eventos = [];
        
        // Agregar fecha de creación del caso
        $eventos[] = [
            'fecha' => $caso->fecha_creacion,
            'tipo' => 'Caso Creado',
            'descripcion' => "Caso '{$caso->nombre}' creado por {$caso->creador->nombre}"
        ];
        
        // Agregar asignaciones
        foreach ($caso->asignaciones as $asignacion) {
            $eventos[] = [
                'fecha' => $asignacion->fecha_asignacion,
                'tipo' => 'Asignación',
                'descripcion' => "Caso asignado a {$asignacion->usuario->nombre}"
            ];
        }
        
        // Agregar evidencias
        foreach ($caso->evidencias as $evidencia) {
            $eventos[] = [
                'fecha' => $evidencia->fecha_creacion,
                'tipo' => 'Evidencia Agregada',
                'descripcion' => "Evidencia tipo '{$evidencia->tipo}' agregada"
            ];
        }
        
        // Ordenar eventos por fecha
        usort($eventos, function($a, $b) {
            return strtotime($a['fecha']) - strtotime($b['fecha']);
        });
        
        // Generar tabla de línea de tiempo
        $markdown .= "| Fecha | Tipo | Descripción |\n";
        $markdown .= "|-------|------|-------------|\n";
        
        foreach ($eventos as $evento) {
            $fecha = Carbon::parse($evento['fecha'])->format('d/m/Y H:i');
            $markdown .= "| {$fecha} | {$evento['tipo']} | {$evento['descripcion']} |\n";
        }
        
        $markdown .= "\n";
        
        return $markdown;
    }

    /**
     * Generar conclusiones
     */
    private function generarConclusiones($caso)
    {
        $markdown = "## Conclusiones y Observaciones\n\n";
        
        $totalEvidencias = $caso->evidencias->count();
        $tiposEvidencia = $caso->evidencias->pluck('tipo')->unique()->count();
        
        $markdown .= "### Estadísticas del Caso\n\n";
        $markdown .= "- **Total de evidencias recopiladas:** {$totalEvidencias}\n";
        $markdown .= "- **Tipos de evidencia diferentes:** {$tiposEvidencia}\n";
        $markdown .= "- **Estado actual:** {$caso->estado}\n\n";
        
        $markdown .= "### Notas Adicionales\n\n";
        $markdown .= "_Espacio para conclusiones y observaciones del investigador._\n\n";
        
        $markdown .= "---\n\n";
        $markdown .= "**Reporte generado el:** " . Carbon::now()->format('d/m/Y H:i:s') . "\n";
        $markdown .= "**Plataforma UDINT** - Unidad de Delitos Informáticos\n";
        
        return $markdown;
    }

    /**
     * Generar reporte de evidencias únicamente
     */
    public function generarReporteEvidencias($caso)
    {
        $markdown = "# Reporte de Evidencias\n\n";
        $markdown .= "## Caso: {$caso->nombre}\n\n";
        $markdown .= "---\n\n";
        $markdown .= $this->generarEvidencias($caso);
        
        return $markdown;
    }

    /**
     * Generar reporte tipo Person Template (según obsidian-osint-templates)
     */
    public function generarReportePersona($caso, $datosPersona)
    {
        $markdown = "# Person Investigation Report\n\n";
        $markdown .= "## Case: {$caso->nombre}\n\n";
        $markdown .= "---\n\n";
        
        $markdown .= "## Personal Information\n\n";
        $markdown .= "| Field | Information |\n";
        $markdown .= "|-------|-------------|\n";
        $markdown .= "| **Full Name** | " . ($datosPersona['nombre_completo'] ?? 'N/A') . " |\n";
        $markdown .= "| **Aliases** | " . ($datosPersona['aliases'] ?? 'N/A') . " |\n";
        $markdown .= "| **Date of Birth** | " . ($datosPersona['fecha_nacimiento'] ?? 'N/A') . " |\n";
        $markdown .= "| **Age** | " . ($datosPersona['edad'] ?? 'N/A') . " |\n";
        $markdown .= "| **Gender** | " . ($datosPersona['genero'] ?? 'N/A') . " |\n";
        $markdown .= "| **Nationality** | " . ($datosPersona['nacionalidad'] ?? 'N/A') . " |\n\n";
        
        $markdown .= "## Contact Information\n\n";
        $markdown .= "| Type | Details |\n";
        $markdown .= "|------|----------|\n";
        $markdown .= "| **Email Addresses** | " . ($datosPersona['emails'] ?? 'N/A') . " |\n";
        $markdown .= "| **Phone Numbers** | " . ($datosPersona['telefonos'] ?? 'N/A') . " |\n";
        $markdown .= "| **Physical Addresses** | " . ($datosPersona['direcciones'] ?? 'N/A') . " |\n\n";
        
        $markdown .= "## Online Presence\n\n";
        $markdown .= "### Social Media Accounts\n\n";
        $markdown .= "| Platform | Username | URL | Status |\n";
        $markdown .= "|----------|----------|-----|--------|\n";
        
        if (isset($datosPersona['redes_sociales']) && is_array($datosPersona['redes_sociales'])) {
            foreach ($datosPersona['redes_sociales'] as $red) {
                $markdown .= "| {$red['plataforma']} | {$red['usuario']} | {$red['url']} | {$red['estado']} |\n";
            }
        } else {
            $markdown .= "| N/A | N/A | N/A | N/A |\n";
        }
        
        $markdown .= "\n## Evidence Collected\n\n";
        $markdown .= $this->generarEvidencias($caso);
        
        $markdown .= "## Analysis Summary\n\n";
        $markdown .= "_Space for investigator's analysis and conclusions._\n\n";
        
        $markdown .= "---\n\n";
        $markdown .= "**Report Generated:** " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        $markdown .= "**Investigator:** " . ($datosPersona['investigador'] ?? $caso->creador->nombre) . "\n";
        
        return $markdown;
    }

    /**
     * Generar reporte tipo Domain/Website Template
     */
    public function generarReporteDominio($caso, $datosDominio)
    {
        $markdown = "# Domain/Website Investigation Report\n\n";
        $markdown .= "## Case: {$caso->nombre}\n\n";
        $markdown .= "---\n\n";
        
        $markdown .= "## Domain Information\n\n";
        $markdown .= "| Field | Information |\n";
        $markdown .= "|-------|-------------|\n";
        $markdown .= "| **Domain Name** | " . ($datosDominio['dominio'] ?? 'N/A') . " |\n";
        $markdown .= "| **IP Address** | " . ($datosDominio['ip'] ?? 'N/A') . " |\n";
        $markdown .= "| **Registrar** | " . ($datosDominio['registrador'] ?? 'N/A') . " |\n";
        $markdown .= "| **Registration Date** | " . ($datosDominio['fecha_registro'] ?? 'N/A') . " |\n";
        $markdown .= "| **Expiration Date** | " . ($datosDominio['fecha_expiracion'] ?? 'N/A') . " |\n";
        $markdown .= "| **Name Servers** | " . ($datosDominio['name_servers'] ?? 'N/A') . " |\n\n";
        
        $markdown .= "## WHOIS Information\n\n";
        $markdown .= "```\n";
        $markdown .= ($datosDominio['whois'] ?? 'No WHOIS data available');
        $markdown .= "\n```\n\n";
        
        $markdown .= "## DNS Records\n\n";
        $markdown .= "| Type | Value |\n";
        $markdown .= "|------|-------|\n";
        
        if (isset($datosDominio['dns_records']) && is_array($datosDominio['dns_records'])) {
            foreach ($datosDominio['dns_records'] as $record) {
                $markdown .= "| {$record['tipo']} | {$record['valor']} |\n";
            }
        } else {
            $markdown .= "| N/A | N/A |\n";
        }
        
        $markdown .= "\n## Website Analysis\n\n";
        $markdown .= "- **Status:** " . ($datosDominio['estado'] ?? 'N/A') . "\n";
        $markdown .= "- **Server:** " . ($datosDominio['servidor'] ?? 'N/A') . "\n";
        $markdown .= "- **Technologies:** " . ($datosDominio['tecnologias'] ?? 'N/A') . "\n\n";
        
        $markdown .= "## Evidence Collected\n\n";
        $markdown .= $this->generarEvidencias($caso);
        
        $markdown .= "## Security Analysis\n\n";
        $markdown .= "_Space for security findings and vulnerabilities._\n\n";
        
        $markdown .= "---\n\n";
        $markdown .= "**Report Generated:** " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        
        return $markdown;
    }

    /**
     * Generar reporte tipo Email Template
     */
    public function generarReporteEmail($caso, $datosEmail)
    {
        $markdown = "# Email Investigation Report\n\n";
        $markdown .= "## Case: {$caso->nombre}\n\n";
        $markdown .= "---\n\n";
        
        $markdown .= "## Email Information\n\n";
        $markdown .= "| Field | Information |\n";
        $markdown .= "|-------|-------------|\n";
        $markdown .= "| **Email Address** | " . ($datosEmail['email'] ?? 'N/A') . " |\n";
        $markdown .= "| **Domain** | " . ($datosEmail['dominio'] ?? 'N/A') . " |\n";
        $markdown .= "| **Valid** | " . ($datosEmail['valido'] ?? 'N/A') . " |\n";
        $markdown .= "| **Disposable** | " . ($datosEmail['desechable'] ?? 'N/A') . " |\n\n";
        
        $markdown .= "## Associated Accounts\n\n";
        $markdown .= "| Service | Status | Details |\n";
        $markdown .= "|---------|--------|----------|\n";
        
        if (isset($datosEmail['servicios']) && is_array($datosEmail['servicios'])) {
            foreach ($datosEmail['servicios'] as $servicio) {
                $markdown .= "| {$servicio['nombre']} | {$servicio['estado']} | {$servicio['detalles']} |\n";
            }
        } else {
            $markdown .= "| N/A | N/A | N/A |\n";
        }
        
        $markdown .= "\n## Data Breaches\n\n";
        if (isset($datosEmail['brechas']) && is_array($datosEmail['brechas'])) {
            foreach ($datosEmail['brechas'] as $brecha) {
                $markdown .= "### {$brecha['sitio']}\n";
                $markdown .= "- **Date:** {$brecha['fecha']}\n";
                $markdown .= "- **Compromised Data:** {$brecha['datos_comprometidos']}\n\n";
            }
        } else {
            $markdown .= "No data breaches found.\n\n";
        }
        
        $markdown .= "## Evidence Collected\n\n";
        $markdown .= $this->generarEvidencias($caso);
        
        $markdown .= "---\n\n";
        $markdown .= "**Report Generated:** " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        
        return $markdown;
    }

    /**
     * Generar reporte tipo Phone Number Template
     */
    public function generarReporteTelefono($caso, $datosTelefono)
    {
        $markdown = "# Phone Number Investigation Report\n\n";
        $markdown .= "## Case: {$caso->nombre}\n\n";
        $markdown .= "---\n\n";
        
        $markdown .= "## Phone Number Information\n\n";
        $markdown .= "| Field | Information |\n";
        $markdown .= "|-------|-------------|\n";
        $markdown .= "| **Phone Number** | " . ($datosTelefono['numero'] ?? 'N/A') . " |\n";
        $markdown .= "| **Country** | " . ($datosTelefono['pais'] ?? 'N/A') . " |\n";
        $markdown .= "| **Carrier** | " . ($datosTelefono['operador'] ?? 'N/A') . " |\n";
        $markdown .= "| **Type** | " . ($datosTelefono['tipo'] ?? 'N/A') . " |\n";
        $markdown .= "| **Valid** | " . ($datosTelefono['valido'] ?? 'N/A') . " |\n\n";
        
        $markdown .= "## Location Information\n\n";
        $markdown .= "- **Region:** " . ($datosTelefono['region'] ?? 'N/A') . "\n";
        $markdown .= "- **City:** " . ($datosTelefono['ciudad'] ?? 'N/A') . "\n\n";
        
        $markdown .= "## Associated Profiles\n\n";
        if (isset($datosTelefono['perfiles']) && is_array($datosTelefono['perfiles'])) {
            foreach ($datosTelefono['perfiles'] as $perfil) {
                $markdown .= "- **{$perfil['plataforma']}:** {$perfil['informacion']}\n";
            }
        } else {
            $markdown .= "No associated profiles found.\n";
        }
        
        $markdown .= "\n## Evidence Collected\n\n";
        $markdown .= $this->generarEvidencias($caso);
        
        $markdown .= "---\n\n";
        $markdown .= "**Report Generated:** " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        
        return $markdown;
    }
}

