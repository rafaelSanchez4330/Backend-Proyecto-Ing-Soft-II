<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas del Módulo Capturista (requiere rol capturista)
    Route::middleware(['capturista'])->prefix('capturista')->group(function () {
        // Rutas de Vistas
        Route::get('/casos', [App\Http\Controllers\CapturistaWebController::class, 'casos'])->name('capturista.casos');
        Route::get('/casos/{id}', [App\Http\Controllers\CapturistaWebController::class, 'casoDetalle'])->name('capturista.caso-detalle');
        Route::get('/casos/{idCaso}/evidencias', [App\Http\Controllers\CapturistaWebController::class, 'evidencias'])->name('capturista.evidencias');
        Route::get('/casos/{idCaso}/reportes', [App\Http\Controllers\CapturistaWebController::class, 'reportes'])->name('capturista.reportes');
        Route::get('/evidencias', [App\Http\Controllers\CapturistaWebController::class, 'todasEvidencias'])->name('capturista.todas-evidencias');

        // Rutas de API (Internas, usan sesión web)
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
            Route::get('/casos/{idCaso}/reportes', [App\Http\Controllers\Api\CapturistaController::class, 'listarReportes']);
            Route::get('/reportes/{nombreArchivo}/descargar', [App\Http\Controllers\Api\CapturistaController::class, 'descargarReporte']);
        });
    });
});
