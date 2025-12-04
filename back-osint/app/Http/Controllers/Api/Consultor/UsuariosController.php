<?php

namespace App\Http\Controllers\Api\Consultor;

use App\Http\Controllers\Controller;
use App\Usuario;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    // ================================================
    //      LISTAR USUARIOS ACTIVOS (sin eliminados)
    //      GET /api/consultor/usuarios/activos
    // ================================================
    public function activos()
    {
        $usuarios = Usuario::select(
                'id_usuario',
                'nombre',
                'usuario',
                'mail',
                'rol',
                'activo'
            )
            ->where('activo', true)
            ->whereNull('deleted_at')
            ->get();

        return response()->json($usuarios);
    }

    // ================================================
    //      DETALLE DE UN USUARIO
    //      GET /api/consultor/usuarios/{id}
    // ================================================
    public function detalle($id)
    {
        $usuario = Usuario::where('id_usuario', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario);
    }
}
