<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Caso; // Asegúrate de que el modelo Caso se llame 'Caso' y esté en App\
use App\Usuario; // Importamos Usuario para buscar investigadores si es necesario
use Illuminate\Support\Facades\DB; // ¡NUEVO! Necesario para la función whereRaw/LOWER()

class ApiController extends Controller
{
    /**
     * Obtiene el estado de un caso por su ID.
     * Endpoint: GET /api/casos/estado/{caso_id}
     */
    public function getEstadoCaso($caso_id)
    {
        // 1. Busca el caso por su ID (usando 'id_caso' o la columna que uses para el ID)
        $caso = Caso::where('id_caso', $caso_id)->first();

        if (!$caso) {
            // Si no existe, devuelve 404 para que Lambda sepa que no hay datos
            return response()->json(['estado' => 'desconocido', 'mensaje' => "El caso $caso_id no fue encontrado."], 404);
        }
        
        // 2. Devuelve el estado en formato JSON
        return response()->json(['estado' => $caso->estado]);
    }

    /**
     * Obtiene los investigadores asignados a un caso por su ID.
     * Endpoint: GET /api/casos/investigadores/{caso_id}
     */
    public function getInvestigadoresCaso($caso_id)
    {
        // 1. Busca el caso e incluye la relación de investigadores
        // NOTA: Esto asume que tienes definida una relación 'investigadores' en tu modelo Caso
        $caso = Caso::where('id_caso', $caso_id)->with('investigadores')->first();
        
        if (!$caso) {
             return response()->json(['investigadores' => [], 'mensaje' => "El caso $caso_id no fue encontrado."], 404);
        }
        
        // 2. Verifica si hay investigadores asignados y extrae solo los nombres
        if ($caso->relationLoaded('investigadores') && $caso->investigadores->isNotEmpty()) {
             $nombres = $caso->investigadores->pluck('nombre')->all();
        } else {
             $nombres = [];
        }

        return response()->json([
             'investigadores' => $nombres,
             'total' => count($nombres)
        ]);
    }

    /**
     * [FUNCIÓN CORREGIDA] Obtiene el estado, tipo y descripción de un caso por su NOMBRE.
     * Endpoint: GET /api/casos/info/nombre/{nombre}
     *
     * Solución al problema de mayúsculas/minúsculas (Case Insensitive):
     * Usa whereRaw y LOWER() para que la búsqueda en la base de datos no distinga entre mayúsculas y minúsculas.
     */
    public function getCaseInfoByName($nombre)
    {
        // 1. Convertimos el nombre que viene de Alexa a minúsculas para compararlo.
        // Esto previene que la sensibilidad de la base de datos cause un fallo.
        $nombreLower = strtolower($nombre);
        
        // 2. Buscamos el caso. whereRaw fuerza a la base de datos a comparar 
        // la columna 'nombre' en minúsculas con el nombre que buscamos en minúsculas.
        // ASUMIMOS que el campo de la DB se llama 'nombre'.
        $caso = Caso::whereRaw('LOWER(nombre) = ?', [$nombreLower])->first();

        if (!$caso) {
            // 404: Caso no encontrado.
            return response()->json([
                'error' => "El caso '$nombre' no fue encontrado en la base de datos o el nombre es incorrecto."
            ], 404); 
        }

        // 3. Devuelve los 3 campos requeridos por Lambda
        return response()->json([
            'estado' => $caso->estado,
            'tipo_caso' => $caso->tipo_caso, 
            'descripcion' => $caso->descripcion,
        ], 200);
    }
}