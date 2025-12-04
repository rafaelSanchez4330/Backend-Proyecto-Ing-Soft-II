<?php

namespace App\Http\Controllers\Api\Consultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ChatbotTelegram;
use App\ChatbotAlexa;
use App\ChatbotWhatsapp;
use App\Usuario;

class PlataformasController extends Controller
{
    /**
     * Obtener todas las plataformas vinculadas a un usuario.
     * Endpoint: GET /api/consultor/usuarios/{id}/plataformas
     */
    public function plataformasUsuario($id_usuario)
    {
        // Validar existencia del usuario
        $usuario = Usuario::find($id_usuario);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Consultar enlaces en las tres plataformas
        $telegram = ChatbotTelegram::where('user_id', $id_usuario)->first();
        $alexa = ChatbotAlexa::where('user_id', $id_usuario)->first();
        $whatsapp = ChatbotWhatsapp::where('user_id', $id_usuario)->first();

        return response()->json([
            'success' => true,
            'usuario' => [
                'id_usuario' => $usuario->id_usuario,
                'nombre' => $usuario->nombre,
                'usuario' => $usuario->usuario,
                'mail' => $usuario->mail,
            ],
            'plataformas' => [
                'telegram' => $telegram,
                'alexa' => $alexa,
                'whatsapp' => $whatsapp
            ]
        ]);
    }

    /**
     * Obtener listado global de plataformas vinculadas (todos los usuarios)
     * Endpoint: GET /api/consultor/plataformas
     */
    public function todasPlataformas()
    {
        return response()->json([
            'success' => true,
            'telegram' => ChatbotTelegram::all(),
            'alexa' => ChatbotAlexa::all(),
            'whatsapp' => ChatbotWhatsapp::all()
        ]);
    }
}
