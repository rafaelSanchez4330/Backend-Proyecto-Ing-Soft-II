<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificarRolCapturista
{
    /**
     * Verificar que el usuario tenga rol de capturista o administrador
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Permitir acceso a capturistas y administradores
        $rolesPermitidos = ['capturista', 'administrador', 'admin'];
        
        if (!in_array(strtolower($usuario->rol), $rolesPermitidos)) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder a este módulo. Se requiere rol de capturista.'
            ], 403);
        }

        return $next($request);
    }
}

