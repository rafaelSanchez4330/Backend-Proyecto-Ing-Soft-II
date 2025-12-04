<?php

namespace App\Http\Controllers\Api\Consultor;

use App\Http\Controllers\Controller;
use App\Evidencia;
use App\Caso;
use Illuminate\Http\Request;

class EvidenciasController extends Controller
{
    // ========================================================
    //     OBTENER EVIDENCIAS DE UN CASO
    //     GET /api/consultor/casos/{id}/evidencias
    // ========================================================
    public function porCaso($id)
    {
        // Verificar que el caso existe
        $caso = Caso::where('id_caso', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$caso) {
            return response()->json(['error' => 'Caso no encontrado'], 404);
        }

        // Obtener evidencias asociadas al caso
        $evidencias = Evidencia::select(
                'id_evidencia',
                'id_caso',
                'tipo',
                'descripcion',
                'fecha_creacion'
            )
            ->where('id_caso', $id)
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'caso' => [
                'id' => $caso->id_caso,
                'nombre' => $caso->nombre,
                'descripcion' => $caso->descripcion
            ],
            'evidencias' => $evidencias
        ]);
    }
}
