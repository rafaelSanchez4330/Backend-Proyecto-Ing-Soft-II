<?php

namespace App\Http\Controllers\Api\Consultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LogActividad;

class AccionesController extends Controller
{
    /**
     * Obtener acciones realizadas por un usuario dado.
     * Endpoint: GET /api/consultor/usuarios/{id}/acciones
     */
    public function accionesUsuario($id_usuario)
    {
        $acciones = LogActividad::where('id_usuario', $id_usuario)
            ->orderBy('fecha_hora', 'desc')
            ->get([
                'id_log',
                'id_usuario',
                'fecha_hora',
                'tipo_accion',
                'descripcion',
                'caso_id_relacionado'
            ]);

        return response()->json([
            'success' => true,
            'acciones' => $acciones
        ]);
    }

    /**
     * Obtener historial (log actividad) de un caso.
     * Endpoint: GET /api/consultor/casos/{id}/historial
     */
    public function historialCaso($id_caso)
    {
        $historial = LogActividad::where('caso_id_relacionado', $id_caso)
            ->orderBy('fecha_hora', 'desc')
            ->get([
                'id_log',
                'id_usuario',
                'fecha_hora',
                'tipo_accion',
                'descripcion'
            ]);

        return response()->json([
            'success' => true,
            'historial' => $historial
        ]);
    }
}
