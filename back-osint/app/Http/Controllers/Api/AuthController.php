<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'contrasena' => 'required|string'
        ], [
            'usuario.required' => 'El campo usuario es obligatorio',
            'contrasena.required' => 'El campo contraseña es obligatorio'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar usuario por nombre de usuario o email
        $usuario = Usuario::where('usuario', $request->usuario)
            ->orWhere('mail', $request->usuario)
            ->first();

        // Verificar si el usuario existe
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Verificar si el usuario está activo
        if (!$usuario->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario inactivo. Contacte al administrador'
            ], 403);
        }

        // Verificar contraseña
        if (!Hash::check($request->contrasena, $usuario->contrasena)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Actualizar última conexión
        $usuario->ultima_conexion = now();
        $usuario->save();

        // Generar token simple (puedes usar Laravel Sanctum o Passport para tokens más seguros)
        $token = base64_encode($usuario->id_usuario . '|' . time() . '|' . uniqid());

        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'data' => [
                'token' => $token,
                'usuario' => [
                    'id' => $usuario->id_usuario,
                    'nombre' => $usuario->nombre,
                    'usuario' => $usuario->usuario,
                    'mail' => $usuario->mail,
                    'rol' => $usuario->rol,
                    'ultima_conexion' => $usuario->ultima_conexion
                ]
            ]
        ], 200);
    }

    /**
     * Logout de usuario
     */
    public function logout(Request $request)
    {
        // Aquí puedes invalidar el token si usas un sistema de tokens
        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ], 200);
    }

    /**
     * Verificar token
     */
    public function verify(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado'
            ], 401);
        }

        // Aquí puedes implementar la lógica de verificación del token
        return response()->json([
            'success' => true,
            'message' => 'Token válido'
        ], 200);
    }
}
