<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caso;
use App\Evidencia;
use App\AsignacionCaso;
use Illuminate\Support\Facades\Auth;

class CapturistaWebController extends Controller
{
    /**
     * Vista principal de casos
     */
    public function casos()
    {
        $user = Auth::user();
        
        // Obtener casos asignados al usuario
        $casosAsignados = AsignacionCaso::where('id_usuario', $user->id_usuario)
            ->pluck('id_caso')
            ->toArray();
        
        $casos = Caso::whereIn('id_caso', $casosAsignados)
            ->orderBy('fecha_creacion', 'desc')
            ->get();
        
        return view('capturista.casos', compact('casos', 'user'));
    }

    /**
     * Vista de detalle de un caso
     */
    public function casoDetalle($id)
    {
        $user = Auth::user();
        
        // Verificar que el caso esté asignado al usuario
        $asignacion = AsignacionCaso::where('id_caso', $id)
            ->where('id_usuario', $user->id_usuario)
            ->first();
        
        if (!$asignacion) {
            abort(403, 'No tiene permiso para ver este caso');
        }
        
        $caso = Caso::findOrFail($id);
        $evidencias = Evidencia::where('id_caso', $id)
            ->orderBy('fecha_creacion', 'desc')
            ->get();
        
        return view('capturista.caso-detalle', compact('caso', 'evidencias', 'user'));
    }

    /**
     * Vista de evidencias de un caso
     */
    public function evidencias($idCaso)
    {
        $user = Auth::user();
        
        // Verificar que el caso esté asignado al usuario
        $asignacion = AsignacionCaso::where('id_caso', $idCaso)
            ->where('id_usuario', $user->id_usuario)
            ->first();
        
        if (!$asignacion) {
            abort(403, 'No tiene permiso para ver este caso');
        }
        
        $caso = Caso::findOrFail($idCaso);
        $evidencias = Evidencia::where('id_caso', $idCaso)
            ->orderBy('fecha_creacion', 'desc')
            ->get();
        
        return view('capturista.evidencias', compact('caso', 'evidencias', 'user'));
    }

    /**
     * Vista de reportes de un caso
     */
    public function reportes($idCaso)
    {
        $user = Auth::user();
        
        // Verificar asignación
        $asignacion = AsignacionCaso::where('id_caso', $idCaso)
            ->where('id_usuario', $user->id_usuario)
            ->first();
            
        if (!$asignacion) {
            abort(403, 'No tiene permiso para acceder a este caso');
        }
        
        $caso = Caso::find($idCaso);
        
        return view('capturista.reportes', compact('caso'));
    }

    public function todasEvidencias()
    {
        $herramientas = \App\Herramienta::all();
        return view('capturista.evidencias-global', compact('herramientas'));
    }
}
