<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caso;
use App\AsignacionCaso;
use App\Evidencia;
use App\ActividadCaso;
use App\Usuario;

class ReportsController extends Controller
{
    public function index()
    {
        // Get all cases with their related data, ordered by date
        $casos = Caso::orderBy('fecha_creacion', 'desc')->get();

        return view('reports', compact('casos'));
    }

    public function show($id)
    {
        // Get case with all related data
        $caso = Caso::findOrFail($id);

        // Get assigned users
        $asignados = AsignacionCaso::where('id_caso', $id)
            ->join('usuarios', 'asignaciones_casos.id_usuario', '=', 'usuarios.id_usuario')
            ->select('usuarios.*', 'asignaciones_casos.fecha_asignacion')
            ->get();

        // Get evidences
        $evidencias = Evidencia::where('id_caso', $id)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        // Get activities
        $actividades = ActividadCaso::where('id_caso', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('case-detail', compact('caso', 'asignados', 'evidencias', 'actividades'));
    }

    public function exportPdf($id)
    {
        $caso = Caso::findOrFail($id);

        // Get related data
        $asignados = AsignacionCaso::where('id_caso', $id)
            ->join('usuarios', 'asignaciones_casos.id_usuario', '=', 'usuarios.id_usuario')
            ->select('usuarios.*', 'asignaciones_casos.fecha_asignacion')
            ->get();

        $evidencias = Evidencia::where('id_caso', $id)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $actividades = ActividadCaso::where('id_caso', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        // Return HTML view optimized for printing/PDF
        return view('pdf.case-report', compact('caso', 'asignados', 'evidencias', 'actividades'));
    }

    public function exportObsidian($id)
    {
        $caso = Caso::findOrFail($id);

        // Get related data
        $asignados = AsignacionCaso::where('id_caso', $id)
            ->join('usuarios', 'asignaciones_casos.id_usuario', '=', 'usuarios.id_usuario')
            ->select('usuarios.*', 'asignaciones_casos.fecha_asignacion')
            ->get();

        $evidencias = Evidencia::where('id_caso', $id)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $actividades = ActividadCaso::where('id_caso', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        // Generate Obsidian markdown
        $markdown = "# Caso #{$caso->id_caso}: {$caso->codigo_caso}\n\n";
        $markdown .= "## Información General\n\n";
        $markdown .= "- **Estado**: {$caso->estado}\n";
        $markdown .= "- **Descripción**: {$caso->descripcion}\n";
        $markdown .= "- **Fecha de Creación**: {$caso->fecha_creacion}\n";
        $markdown .= "- **Prioridad**: " . ($caso->prioridad ?? 'Medium') . "\n\n";

        $markdown .= "## Usuarios Asignados\n\n";
        foreach ($asignados as $usuario) {
            $markdown .= "- **{$usuario->nombre}** ({$usuario->rol}) - Asignado: {$usuario->fecha_asignacion}\n";
        }

        $markdown .= "\n## Evidencias\n\n";
        foreach ($evidencias as $evidencia) {
            $markdown .= "### {$evidencia->tipo}\n";
            $markdown .= "{$evidencia->descripcion}\n";
            $markdown .= "*Creado: {$evidencia->fecha_creacion}*\n\n";
        }

        $markdown .= "## Actividades\n\n";
        foreach ($actividades as $actividad) {
            $markdown .= "- **{$actividad->fecha}**: {$actividad->actividad}\n";
        }

        return response($markdown)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="caso-' . $caso->codigo_caso . '.md"');
    }
}
