<?php

namespace App\Http\Controllers\Api\Consultor;

use App\Http\Controllers\Controller;
use App\Herramienta;
use App\CategoriaHerramienta;
use App\RelHerramientaCategoria;
use Illuminate\Http\Request;

class HerramientasController extends Controller
{
    // ========================================================
    //     LISTA GENERAL DE HERRAMIENTAS
    //     GET /api/consultor/herramientas
    // ========================================================
    public function lista()
    {
        $herramientas = Herramienta::select(
                'id_herramienta',
                'nombre',
                'enlace'
            )
            ->whereNull('deleted_at')
            ->get();

        return response()->json($herramientas);
    }

    // ========================================================
    //     HERRAMIENTAS POR CATEGORIA
    //     GET /api/consultor/categorias/{id}/herramientas
    // ========================================================
    public function porCategoria($id)
    {
        // Confirmar que la categoria existe
        $categoria = CategoriaHerramienta::where('id_categoria', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
        }

        // Obtener relaciones entre herramientas y esta categoría
        $relaciones = RelHerramientaCategoria::where('id_categoria', $id)->get();

        $ids = $relaciones->pluck('id_herramienta');

        // Obtener herramientas
        $herramientas = Herramienta::whereIn('id_herramienta', $ids)
            ->whereNull('deleted_at')
            ->select('id_herramienta', 'nombre', 'enlace')
            ->get();

        return response()->json([
            'categoria' => $categoria->nombre,
            'herramientas' => $herramientas
        ]);
    }
}
