<?php

use Illuminate\Support\Facades\Route;

// Controladores Generales y de Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HerramientaController;

// Controladores de Reportes (Mantengo ambos por si usas las dos versiones)
use App\Http\Controllers\ReportsController; // Versión con PDF/Obsidian
use App\Http\Controllers\ReportesController; // Versión con Descargas

// Controladores del Módulo Capturista
use App\Http\Controllers\CapturistaWebController;
use App\Http\Controllers\Api\CapturistaController;
use App\Http\Controllers\ConsultorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ----------------------------------------------------------------------
// 2. Rutas Protegidas (Requieren Login)
// ----------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {

    // Dashboard General
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/herramientas', [HerramientaController::class, 'index']);
    Route::get('/categorias', [HerramientaController::class, 'categorias']);
    Route::post('/herramientas', [HerramientaController::class, 'store']);
    Route::delete('/herramientas/{id}', [HerramientaController::class, 'destroy']);

    // -------------------------------------------------------
    // Módulo de Reportes (Versión A - PDF/Obsidian)
    // -------------------------------------------------------
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/case/{id}', [ReportsController::class, 'show'])->name('reports.show');
    Route::get('/reports/case/{id}/pdf', [ReportsController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/case/{id}/obsidian', [ReportsController::class, 'exportObsidian'])->name('reports.obsidian');

    // -------------------------------------------------------
    // Módulo de Reportes (Versión B - General)
    // -------------------------------------------------------
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/{nombreArchivo}/descargar', [ReportesController::class, 'descargar'])->name('reportes.descargar');

    // -------------------------------------------------------
    // Módulo Capturista (Middleware específico)
    // -------------------------------------------------------
    Route::middleware(['capturista'])->prefix('capturista')->group(function () {

        // --- Vistas Web ---
        Route::get('/casos', [CapturistaWebController::class, 'casos'])->name('capturista.casos');
        Route::get('/casos/{id}', [CapturistaWebController::class, 'casoDetalle'])->name('capturista.caso-detalle');
        Route::get('/casos/{idCaso}/evidencias', [CapturistaWebController::class, 'evidencias'])->name('capturista.evidencias');
        Route::get('/casos/{idCaso}/reportes', [CapturistaWebController::class, 'reportes'])->name('capturista.reportes');
        Route::get('/evidencias', [CapturistaWebController::class, 'todasEvidencias'])->name('capturista.todas-evidencias');

        // --- API Interna (Usa sesión web) ---
        Route::prefix('api')->group(function () {
            // Gestión de casos asignados
            Route::get('/casos', [App\Http\Controllers\Api\CapturistaController::class, 'getCasosAsignados']);
            Route::get('/casos/{id}', [App\Http\Controllers\Api\CapturistaController::class, 'verCaso']);

            // Gestión de evidencias
            Route::get('/evidencias', [App\Http\Controllers\Api\CapturistaController::class, 'getAllEvidencias']);
            Route::get('/casos/{idCaso}/evidencias', [App\Http\Controllers\Api\CapturistaController::class, 'getEvidencias']);
            Route::post('/evidencias', [App\Http\Controllers\Api\CapturistaController::class, 'agregarEvidencia']);
            Route::put('/evidencias/{id}', [App\Http\Controllers\Api\CapturistaController::class, 'actualizarEvidencia']);
            Route::delete('/evidencias/{id}', [App\Http\Controllers\Api\CapturistaController::class, 'eliminarEvidencia']);

            // Generación de reportes
            Route::get('/casos/{idCaso}/reporte-completo', [App\Http\Controllers\Api\CapturistaController::class, 'generarReporteCompleto']);
            Route::get('/casos/{idCaso}/reporte-evidencias', [App\Http\Controllers\Api\CapturistaController::class, 'generarReporteEvidencias']);
            Route::post('/casos/{idCaso}/reporte-personalizado', [App\Http\Controllers\Api\CapturistaController::class, 'generarReportePersonalizado']);

            // Gestión de reportes
            Route::get('/casos/{idCaso}/reportes', [CapturistaController::class, 'listarReportes']);
            Route::get('/reportes/{nombreArchivo}/descargar', [CapturistaController::class, 'descargarReporte']);
        });
    });

    // -------------------------------------------------------
    // Módulo Consultor (Middleware específico)
    // -------------------------------------------------------
    Route::middleware(['consultor'])->prefix('consultor')->group(function () {

        // Vista principal (dashboard del consultor)
        Route::get('/inicio', [App\Http\Controllers\ConsultorController::class, 'inicio'])->name('consultor.inicio');

        // Usuarios
        Route::get('/usuarios/lista-usuarios', [App\Http\Controllers\ConsultorController::class, 'usuariosIndex'])->name('consultor.usuarios.index');
        Route::get('/usuarios/{id}', [App\Http\Controllers\ConsultorController::class, 'usuariosShow'])->name('consultor.usuarios.show');

        // Casos
        Route::get('/casos/lista-casos', [App\Http\Controllers\ConsultorController::class, 'casosIndex'])->name('consultor.casos.index');
        Route::get('/casos/{id}', [App\Http\Controllers\ConsultorController::class, 'casosShow'])->name('consultor.casos.show');

    });

});