<?php

namespace App\Http\Controllers\Api\Consultor;

use App\Http\Controllers\Controller;
use App\Caso;
use App\Usuario;
use App\AsignacionCaso;
use Illuminate\Http\Request;

class CasosController extends Controller
{
    // ========================================================
    //     LISTA DE CASOS
    //     GET /api/consultor/casos
    // ========================================================
   public function lista()
    {
        $casos = Caso::with(['creador:id_usuario,nombre'])
            ->select(
                'id_caso',
                'estado',
                'nombre',
                'tipo_caso',
                'descripcion',
                'fecha_creacion',
                'id_creador'
            )
            ->whereNull('deleted_at')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        return response()->json([
            "success" => true,
            "casos" => $casos
        ]);
    }


    // ========================================================
    //     DETALLE DE CASO (con nombre del creador)
    //     GET /api/consultor/casos/{id}
    // ========================================================
   public function detalleCaso($id)
{
    $caso = Caso::with(['creador'])
        ->where('id_caso', $id)
        ->first();

    if (!$caso) {
        return response()->json([
            "success" => false,
            "message" => "Caso no encontrado"
        ], 404);
    }

    return response()->json([
        "success" => true,
        "caso" => $caso
    ]);
}


    // ========================================================
    //     USUARIOS ASIGNADOS A UN CASO
    //     GET /api/consultor/casos/{id}/asignados
    // ========================================================
    public function usuariosAsignados($id)
    {
        // Confirmar que el caso existe
        $caso = Caso::where('id_caso', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$caso) {
            return response()->json(['error' => 'Caso no encontrado'], 404);
        }

        // Obtener asignaciones
        $asignaciones = AsignacionCaso::where('id_caso', $id)->get();

        // Extraer IDs
        $userIds = $asignaciones->pluck('id_usuario');

        // Obtener datos de usuarios
        $usuarios = Usuario::select('id_usuario', 'nombre', 'usuario', 'mail', 'rol')
            ->whereIn('id_usuario', $userIds)
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'caso' => [
                'id' => $caso->id_caso,
                'nombre' => $caso->nombre,
                'descripcion' => $caso->descripcion
            ],
            'asignados' => $usuarios
        ]);
    }
}
