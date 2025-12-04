<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caso;
use App\LogActividad;
use App\Usuario;
use App\Herramienta;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Contar casos activos
        $casosActivos = Caso::orderBy('fecha_creacion', 'desc')->take(10)->get();
        $conteoActivos = Caso::where('estado', 'activo')->count();

        // 2. Traer actividad reciente (últimos)
        $actividadReciente = LogActividad::with('usuario')
                            ->orderBy('fecha_hora', 'desc')
                            ->take(8)
                            ->get();

        // 3. Totales generales
        $totalUsuarios = Usuario::count();
        $totalCasos = Caso::count();

        // 4. Herramientas
        $herramientas = Herramienta::select('id_herramienta', 'nombre', 'enlace')
                                   ->orderBy('nombre', 'asc')
                                   ->get();

        // 5. Rol del usuario actual
        $rolUsuario = auth()->user() ? auth()->user()->rol : 'invitado';

        return response()->json([
            'success' => true,
            'resumen' => [
                'casos_activos_total' => $conteoActivos,
                'total_usuarios' => $totalUsuarios,
                'total_casos' => $totalCasos
            ],
            'lista_casos_activos' => $casosActivos,
            'bitacora_reciente' => $actividadReciente,
            'lista_herramientas' => $herramientas,
            'rol_usuario' => $rolUsuario
        ]);
    }

    public function getAllCases()
    {
        $casos = Caso::with('asignaciones.usuario')->orderBy('fecha_creacion', 'desc')->get();
        return response()->json([
            'success' => true,
            'casos' => $casos
        ]);
    }

    public function getCapturistas()
    {
        $capturistas = Usuario::where('rol', 'capturista')->select('id_usuario', 'nombre')->get();
        return response()->json([
            'success' => true,
            'capturistas' => $capturistas
        ]);
    }

    public function getAllUsers()
    {
        $usuarios = Usuario::all();
        return response()->json([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }

    public function getLogActividad()
    {
        $logs = LogActividad::with('usuario')
                            ->orderBy('fecha_hora', 'desc')
                            ->take(50)
                            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    private function registrarLog($tipo_accion, $descripcion, $caso_id = null)
    {
        try {
            $log = new LogActividad();
            $log->id_usuario = auth()->user() ? auth()->user()->id_usuario : null;
            $log->tipo_accion = $tipo_accion;
            $log->descripcion = $descripcion;
            $log->caso_id_relacionado = $caso_id;
            $log->fecha_hora = now();
            $log->save();
        } catch (\Exception $e) {
            // Silent fail to not disrupt main flow
            \Log::error("Error registrando log: " . $e->getMessage());
        }
    }

    public function storeCaso(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'estado' => 'required|string',
                'descripcion' => 'nullable|string',
                'id_usuario' => 'nullable|exists:usuarios,id_usuario',
            ]);

            $caso = new Caso();
            $caso->nombre = $request->nombre;
            $caso->estado = $request->estado;
            $caso->descripcion = $request->descripcion ?? '';
            $caso->tipo_caso = $request->input('tipo_caso', 'Incidente');
            
            if (auth()->user()) {
                $caso->id_creador = auth()->user()->id_usuario;
            }
            
            $caso->save();

            if ($request->has('id_usuario') && $request->id_usuario) {
                $asignacion = new \App\AsignacionCaso();
                $asignacion->id_caso = $caso->id_caso;
                $asignacion->id_usuario = $request->id_usuario;
                $asignacion->fecha_asignacion = now();
                $asignacion->save();
            }

            $this->registrarLog('crear_caso', "Caso '{$caso->nombre}' creado", $caso->id_caso);

            return response()->json([
                'success' => true,
                'message' => 'Caso creado exitosamente',
                'caso' => $caso
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el caso: ' . $e->getMessage()
            ], 500);
        }
    }


    public function storeUsuario(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:usuarios,mail',
                'password' => 'required|string|min:6',
                'rol' => 'required|string',
                // 'celular' => 'nullable|string'
            ]);

            $usuario = new Usuario();
            $usuario->nombre = $request->nombre;
            $usuario->mail = $request->email;
            $usuario->usuario = $request->email; // Map email to usuario field
            $usuario->contrasena = bcrypt($request->password); // Use contrasena instead of password
            $usuario->rol = $request->rol;
            $usuario->activo = 1; // Default to active
            // $usuario->celular = $request->celular;
            $usuario->save();

            $this->registrarLog('crear_usuario', "Usuario '{$usuario->nombre}' creado");

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'usuario' => $usuario
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCaso(Request $request, $id)
    {
        try {
            $caso = Caso::find($id);
            if (!$caso) {
                return response()->json(['success' => false, 'message' => 'Caso no encontrado'], 404);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'estado' => 'required|string',
                'descripcion' => 'nullable|string',
            ]);

            $caso->nombre = $request->nombre;
            $caso->estado = $request->estado;
            $caso->descripcion = $request->descripcion ?? '';
            $caso->save();

            $this->registrarLog('actualizar_caso', "Caso '{$caso->nombre}' actualizado", $caso->id_caso);

            return response()->json([
                'success' => true,
                'message' => 'Caso actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el caso: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateUsuario(Request $request, $id)
    {
        try {
            $usuario = Usuario::find($id);
            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:usuarios,mail,' . $id . ',id_usuario',
                'rol' => 'required|string',
                // 'celular' => 'nullable|string'
            ]);

            $usuario->nombre = $request->nombre;
            $usuario->mail = $request->email;
            $usuario->usuario = $request->email;
            $usuario->rol = $request->rol;
            // $usuario->celular = $request->celular;
            
            if ($request->filled('password')) {
                $usuario->contrasena = bcrypt($request->password);
            }

            $usuario->save();

            $this->registrarLog('actualizar_usuario', "Usuario '{$usuario->nombre}' actualizado");

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'usuario' => $usuario
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteUsuario($id)
    {
        try {
            $usuario = Usuario::find($id);
            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            }

            $usuario->delete(); // Soft delete

            $this->registrarLog('eliminar_usuario', "Usuario ID {$id} eliminado");

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteCaso($id)
    {
        try {
            $caso = Caso::find($id);
            if (!$caso) {
                return response()->json(['success' => false, 'message' => 'Caso no encontrado'], 404);
            }

            $caso->delete(); // Soft delete

            $this->registrarLog('eliminar_caso', "Caso ID {$id} eliminado", $id);

            return response()->json([
                'success' => true,
                'message' => 'Caso eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar caso: ' . $e->getMessage()
            ], 500);
        }
    }
}