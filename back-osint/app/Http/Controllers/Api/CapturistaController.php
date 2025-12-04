<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Caso;
use App\Evidencia;
use App\AsignacionCaso;
use App\LogActividad;
use App\Services\ReporteOsintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CapturistaController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteOsintService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    /**
     * Obtener todos los casos asignados al usuario autenticado
     */
    public function getCasosAsignados(Request $request)
    {
        try {
            $usuario = Auth::user();
            
            \Illuminate\Support\Facades\Log::info('CapturistaController: getCasosAsignados called');
            
            if (!$usuario) {
                \Illuminate\Support\Facades\Log::warning('CapturistaController: Usuario no autenticado');
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            \Illuminate\Support\Facades\Log::info('CapturistaController: Usuario autenticado', ['id' => $usuario->id_usuario, 'usuario' => $usuario->usuario]);

            // Obtener casos asignados al usuario
            $asignaciones = AsignacionCaso::with(['caso.creador', 'caso.evidencias'])
                ->where('id_usuario', $usuario->id_usuario)
                ->get();
                
            \Illuminate\Support\Facades\Log::info('CapturistaController: Asignaciones encontradas', ['count' => $asignaciones->count()]);

            $casos = $asignaciones->map(function ($asignacion) {
                $caso = $asignacion->caso;
                return [
                    'id_caso' => $caso->id_caso,
                    'nombre' => $caso->nombre,
                    'tipo_caso' => $caso->tipo_caso,
                    'descripcion' => $caso->descripcion,
                    'estado' => $caso->estado,
                    'fecha_creacion' => $caso->fecha_creacion,
                    'fecha_actualizacion' => $caso->fecha_actualizacion,
                    'creador' => [
                        'id' => $caso->creador->id_usuario,
                        'nombre' => $caso->creador->nombre
                    ],
                    'total_evidencias' => $caso->evidencias->count(),
                    'fecha_asignacion' => $asignacion->fecha_asignacion
                ];
            });

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'consulta',
                'descripcion' => 'Consulta de casos asignados',
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Casos obtenidos exitosamente',
                'data' => $casos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener casos asignados',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener detalles de un caso específico asignado
     */
    public function verCaso(Request $request, $id)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $id)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para acceder a este caso'
                ], 403);
            }

            // Obtener caso con todas sus relaciones
            $caso = Caso::with(['creador', 'evidencias', 'actividades'])
                ->find($id);

            if (!$caso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Caso no encontrado'
                ], 404);
            }

            // Formatear respuesta
            $casoData = [
                'id_caso' => $caso->id_caso,
                'nombre' => $caso->nombre,
                'tipo_caso' => $caso->tipo_caso,
                'descripcion' => $caso->descripcion,
                'estado' => $caso->estado,
                'fecha_creacion' => $caso->fecha_creacion,
                'fecha_actualizacion' => $caso->fecha_actualizacion,
                'creador' => [
                    'id' => $caso->creador->id_usuario,
                    'nombre' => $caso->creador->nombre,
                    'mail' => $caso->creador->mail
                ],
                'evidencias' => $caso->evidencias->map(function ($evidencia) {
                    return [
                        'id_evidencia' => $evidencia->id_evidencia,
                        'tipo' => $evidencia->tipo,
                        'descripcion' => $evidencia->descripcion,
                        'fecha_creacion' => $evidencia->fecha_creacion
                    ];
                }),
                'actividades' => $caso->actividades->map(function ($actividad) {
                    return [
                        'id_actividad' => $actividad->id_actividad,
                        'descripcion' => $actividad->descripcion ?? '',
                        'fecha' => $actividad->fecha ?? $actividad->created_at
                    ];
                })
            ];

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'consulta',
                'descripcion' => "Consulta de caso #{$id}",
                'caso_id_relacionado' => $id,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Caso obtenido exitosamente',
                'data' => $casoData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el caso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar nueva evidencia a un caso
     */
    public function agregarEvidencia(Request $request)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Validar datos de entrada
            $validator = Validator::make($request->all(), [
                'id_caso' => 'required|integer|exists:casos,id_caso',
                'tipo' => 'required|string|max:50',
                'descripcion' => 'required|string'
            ], [
                'id_caso.required' => 'El ID del caso es obligatorio',
                'id_caso.exists' => 'El caso no existe',
                'tipo.required' => 'El tipo de evidencia es obligatorio',
                'descripcion.required' => 'La descripción es obligatoria'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $request->id_caso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para agregar evidencia a este caso'
                ], 403);
            }

            // Crear nueva evidencia
            $evidencia = Evidencia::create([
                'id_caso' => $request->id_caso,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
                'fecha_creacion' => now()
            ]);

            // Actualizar fecha de actualización del caso
            $caso = Caso::find($request->id_caso);
            $caso->fecha_actualizacion = now();
            $caso->save();

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'creacion',
                'descripcion' => "Nueva evidencia agregada al caso #{$request->id_caso}",
                'caso_id_relacionado' => $request->id_caso,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evidencia agregada exitosamente',
                'data' => [
                    'id_evidencia' => $evidencia->id_evidencia,
                    'tipo' => $evidencia->tipo,
                    'descripcion' => $evidencia->descripcion,
                    'fecha_creacion' => $evidencia->fecha_creacion
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar evidencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una evidencia existente
     */
    public function actualizarEvidencia(Request $request, $id)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Validar datos de entrada
            $validator = Validator::make($request->all(), [
                'tipo' => 'sometimes|string|max:50',
                'descripcion' => 'sometimes|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar evidencia
            $evidencia = Evidencia::find($id);

            if (!$evidencia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evidencia no encontrada'
                ], 404);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $evidencia->id_caso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para actualizar esta evidencia'
                ], 403);
            }

            // Actualizar evidencia
            if ($request->has('tipo')) {
                $evidencia->tipo = $request->tipo;
            }
            if ($request->has('descripcion')) {
                $evidencia->descripcion = $request->descripcion;
            }
            $evidencia->save();

            // Actualizar fecha de actualización del caso
            $caso = Caso::find($evidencia->id_caso);
            $caso->fecha_actualizacion = now();
            $caso->save();

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'actualizacion',
                'descripcion' => "Evidencia #{$id} actualizada en caso #{$evidencia->id_caso}",
                'caso_id_relacionado' => $evidencia->id_caso,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evidencia actualizada exitosamente',
                'data' => [
                    'id_evidencia' => $evidencia->id_evidencia,
                    'tipo' => $evidencia->tipo,
                    'descripcion' => $evidencia->descripcion,
                    'fecha_creacion' => $evidencia->fecha_creacion
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar evidencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una evidencia (soft delete)
     */
    public function eliminarEvidencia(Request $request, $id)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Buscar evidencia
            $evidencia = Evidencia::find($id);

            if (!$evidencia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evidencia no encontrada'
                ], 404);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $evidencia->id_caso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para eliminar esta evidencia'
                ], 403);
            }

            $idCaso = $evidencia->id_caso;
            
            // Eliminar evidencia (soft delete)
            $evidencia->delete();

            // Actualizar fecha de actualización del caso
            $caso = Caso::find($idCaso);
            $caso->fecha_actualizacion = now();
            $caso->save();

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'eliminacion',
                'descripcion' => "Evidencia #{$id} eliminada del caso #{$idCaso}",
                'caso_id_relacionado' => $idCaso,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evidencia eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar evidencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener evidencias de un caso específico
     */
    public function getEvidencias(Request $request, $idCaso)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $idCaso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para acceder a este caso'
                ], 403);
            }

            // Obtener evidencias del caso
            $evidencias = Evidencia::where('id_caso', $idCaso)
                ->orderBy('fecha_creacion', 'desc')
                ->get();

            $evidenciasData = $evidencias->map(function ($evidencia) {
                return [
                    'id_evidencia' => $evidencia->id_evidencia,
                    'tipo' => $evidencia->tipo,
                    'descripcion' => $evidencia->descripcion,
                    'fecha_creacion' => $evidencia->fecha_creacion
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Evidencias obtenidas exitosamente',
                'data' => $evidenciasData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener evidencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las evidencias de los casos asignados al usuario
     */
    public function getAllEvidencias(Request $request)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Obtener IDs de casos asignados
            $casosIds = AsignacionCaso::where('id_usuario', $usuario->id_usuario)
                ->pluck('id_caso');

            // Obtener evidencias de esos casos con información del caso
            $evidencias = Evidencia::whereIn('id_caso', $casosIds)
                ->with('caso:id_caso,nombre')
                ->orderBy('fecha_creacion', 'desc')
                ->get();

            $evidenciasData = $evidencias->map(function ($evidencia) {
                return [
                    'id_evidencia' => $evidencia->id_evidencia,
                    'tipo' => $evidencia->tipo,
                    'descripcion' => $evidencia->descripcion,
                    'fecha_creacion' => $evidencia->fecha_creacion,
                    'caso' => [
                        'id_caso' => $evidencia->caso->id_caso,
                        'nombre' => $evidencia->caso->nombre,
                        'codigo' => $evidencia->caso->codigo_caso ?? 'N/A'
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Todas las evidencias obtenidas exitosamente',
                'data' => $evidenciasData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener todas las evidencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar reporte completo del caso
     */
    public function generarReporteCompleto(Request $request, $idCaso)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $idCaso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para generar reporte de este caso'
                ], 403);
            }

            // Obtener caso con todas sus relaciones
            $caso = Caso::with(['creador', 'evidencias', 'actividades', 'asignaciones.usuario'])
                ->find($idCaso);

            if (!$caso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Caso no encontrado'
                ], 404);
            }

            // Generar reporte
            $reporteMarkdown = $this->reporteService->generarReporteCompleto($caso);

            // Guardar reporte en storage
            $nombreArchivo = "reporte_caso_{$idCaso}_" . time() . ".md";
            Storage::disk('local')->put("reportes/{$nombreArchivo}", $reporteMarkdown);

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'reporte',
                'descripcion' => "Reporte completo generado para caso #{$idCaso}",
                'caso_id_relacionado' => $idCaso,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'data' => [
                    'nombre_archivo' => $nombreArchivo,
                    'contenido' => $reporteMarkdown
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar reporte de evidencias
     */
    public function generarReporteEvidencias(Request $request, $idCaso)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $idCaso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para generar reporte de este caso'
                ], 403);
            }

            // Obtener caso con evidencias
            $caso = Caso::with(['evidencias'])->find($idCaso);

            if (!$caso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Caso no encontrado'
                ], 404);
            }

            // Generar reporte de evidencias
            $reporteMarkdown = $this->reporteService->generarReporteEvidencias($caso);

            // Guardar reporte en storage
            $nombreArchivo = "reporte_evidencias_{$idCaso}_" . time() . ".md";
            Storage::disk('local')->put("reportes/{$nombreArchivo}", $reporteMarkdown);

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'reporte',
                'descripcion' => "Reporte de evidencias generado para caso #{$idCaso}",
                'caso_id_relacionado' => $idCaso,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reporte de evidencias generado exitosamente',
                'data' => [
                    'nombre_archivo' => $nombreArchivo,
                    'contenido' => $reporteMarkdown
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte de evidencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar reporte personalizado (persona, dominio, email, teléfono)
     */
    public function generarReportePersonalizado(Request $request, $idCaso)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Validar datos de entrada
            $validator = Validator::make($request->all(), [
                'tipo_reporte' => 'required|string|in:persona,dominio,email,telefono',
                'datos' => 'required|array'
            ], [
                'tipo_reporte.required' => 'El tipo de reporte es obligatorio',
                'tipo_reporte.in' => 'Tipo de reporte no válido',
                'datos.required' => 'Los datos del reporte son obligatorios'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $idCaso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para generar reporte de este caso'
                ], 403);
            }

            // Obtener caso
            $caso = Caso::with(['creador', 'evidencias'])->find($idCaso);

            if (!$caso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Caso no encontrado'
                ], 404);
            }

            // Generar reporte según el tipo
            $reporteMarkdown = '';
            $tipoReporte = $request->tipo_reporte;
            $datos = $request->datos;

            switch ($tipoReporte) {
                case 'persona':
                    $reporteMarkdown = $this->reporteService->generarReportePersona($caso, $datos);
                    break;
                case 'dominio':
                    $reporteMarkdown = $this->reporteService->generarReporteDominio($caso, $datos);
                    break;
                case 'email':
                    $reporteMarkdown = $this->reporteService->generarReporteEmail($caso, $datos);
                    break;
                case 'telefono':
                    $reporteMarkdown = $this->reporteService->generarReporteTelefono($caso, $datos);
                    break;
            }

            // Guardar reporte en storage
            $nombreArchivo = "reporte_{$tipoReporte}_{$idCaso}_" . time() . ".md";
            Storage::disk('local')->put("reportes/{$nombreArchivo}", $reporteMarkdown);

            // Registrar actividad
            LogActividad::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_accion' => 'reporte',
                'descripcion' => "Reporte tipo '{$tipoReporte}' generado para caso #{$idCaso}",
                'caso_id_relacionado' => $idCaso,
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reporte personalizado generado exitosamente',
                'data' => [
                    'tipo_reporte' => $tipoReporte,
                    'nombre_archivo' => $nombreArchivo,
                    'contenido' => $reporteMarkdown
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte personalizado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar reporte generado
     */
    public function descargarReporte(Request $request, $nombreArchivo)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el archivo existe
            if (!Storage::disk('local')->exists("reportes/{$nombreArchivo}")) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reporte no encontrado'
                ], 404);
            }

            // Obtener contenido del archivo
            $contenido = Storage::disk('local')->get("reportes/{$nombreArchivo}");

            // Retornar archivo para descarga
            return response($contenido, 200)
                ->header('Content-Type', 'text/markdown')
                ->header('Content-Disposition', "attachment; filename=\"{$nombreArchivo}\"");

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al descargar reporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar reportes generados para un caso
     */
    public function listarReportes(Request $request, $idCaso)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el caso esté asignado al usuario
            $asignacion = AsignacionCaso::where('id_caso', $idCaso)
                ->where('id_usuario', $usuario->id_usuario)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para acceder a este caso'
                ], 403);
            }

            // Obtener todos los archivos de reportes para este caso
            $archivos = Storage::disk('local')->files('reportes');
            $reportesCaso = [];

            foreach ($archivos as $archivo) {
                $nombreArchivo = basename($archivo);
                
                // Verificar si el archivo corresponde a este caso
                if (strpos($nombreArchivo, "caso_{$idCaso}_") !== false ||
                    strpos($nombreArchivo, "evidencias_{$idCaso}_") !== false ||
                    preg_match("/_(persona|dominio|email|telefono)_{$idCaso}_/", $nombreArchivo)) {
                    
                    $reportesCaso[] = [
                        'nombre' => $nombreArchivo,
                        'tamano' => $this->formatBytes(Storage::disk('local')->size($archivo)),
                        'fecha' => date('d/m/Y H:i', Storage::disk('local')->lastModified($archivo))
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Reportes obtenidos exitosamente',
                'data' => [
                    'reportes' => $reportesCaso
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar reportes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        $bytes /= pow(1024, $pow); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
}

