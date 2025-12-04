<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Caso;

class ReportesController extends Controller
{
    /**
     * Mostrar todos los reportes disponibles
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener todos los archivos del directorio de reportes
        $archivos = Storage::disk('local')->files('reportes');
        
        $reportes = [];
        
        foreach ($archivos as $archivo) {
            // Extraer información del nombre del archivo
            $nombreArchivo = basename($archivo);
            $info = $this->extraerInfoReporte($nombreArchivo);
            
            if ($info) {
                $reportes[] = [
                    'nombre_archivo' => $nombreArchivo,
                    'ruta' => $archivo,
                    'caso_id' => $info['caso_id'],
                    'tipo' => $info['tipo'],
                    'fecha' => Storage::disk('local')->lastModified($archivo),
                    'tamano' => Storage::disk('local')->size($archivo),
                ];
            }
        }
        
        // Ordenar por fecha (más recientes primero)
        usort($reportes, function($a, $b) {
            return $b['fecha'] - $a['fecha'];
        });
        
        // Obtener información de los casos
        foreach ($reportes as &$reporte) {
            $caso = Caso::find($reporte['caso_id']);
            $reporte['caso_nombre'] = $caso ? $caso->nombre : 'Caso no encontrado';
            $reporte['caso_estado'] = $caso ? $caso->estado : 'N/A';
        }
        
        return view('reportes', compact('reportes', 'user'));
    }
    
    /**
     * Descargar un reporte específico
     */
    public function descargar($nombreArchivo)
    {
        $ruta = 'reportes/' . $nombreArchivo;
        
        if (!Storage::disk('local')->exists($ruta)) {
            abort(404, 'Reporte no encontrado');
        }
        
        return Storage::disk('local')->download($ruta, $nombreArchivo);
    }
    
    /**
     * Extraer información del nombre del archivo
     * Formato esperado: reporte_caso_{id}_{tipo}_{timestamp}.md
     */
    private function extraerInfoReporte($nombreArchivo)
    {
        // Patrón: reporte_caso_X_tipo_timestamp.md
        if (preg_match('/reporte_caso_(\d+)_([^_]+)_\d+\.md/', $nombreArchivo, $matches)) {
            return [
                'caso_id' => $matches[1],
                'tipo' => ucfirst(str_replace('-', ' ', $matches[2])),
            ];
        }
        
        // Patrón alternativo: caso_X_reporte_tipo.md
        if (preg_match('/caso_(\d+)_reporte_([^\.]+)\.md/', $nombreArchivo, $matches)) {
            return [
                'caso_id' => $matches[1],
                'tipo' => ucfirst(str_replace('-', ' ', $matches[2])),
            ];
        }
        
        // Si no coincide con ningún patrón, intentar extraer al menos el ID del caso
        if (preg_match('/(\d+)/', $nombreArchivo, $matches)) {
            return [
                'caso_id' => $matches[1],
                'tipo' => 'General',
            ];
        }
        
        return null;
    }
}
