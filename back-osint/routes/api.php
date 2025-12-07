<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importamos los controladores que ya estaban
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
// Importamos el controlador de Alexa/API
use App\Http\Controllers\Api\ApiController; 
// Importamos los controladores del Consultor
use App\Http\Controllers\Api\Consultor;

/*
|--------------------------------------------------------------------------
| API Routes (Archivo Unificado)
|--------------------------------------------------------------------------
*/

// ----------------------------------------------------------------------
// 1. Rutas de autenticación (Públicas) - (DEL ORIGINAL)
// ----------------------------------------------------------------------
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/verify', [AuthController::class, 'verify']);


// =====================================================================
// === RUTAS PÚBLICAS PARA SERVICIOS EXTERNOS (ALEXA / LAMBDA) - (TUS CAMBIOS) ===
// =====================================================================

// [GET] /api/casos/estado/{caso_id}
Route::get('/casos/estado/{caso_id}', [ApiController::class, 'getEstadoCaso']);

// [GET] /api/casos/investigadores/{caso_id}
Route::get('/casos/investigadores/{caso_id}', [ApiController::class, 'getInvestigadoresCaso']);

// [NUEVA RUTA] /api/casos/info/nombre/{nombre} - ESTA ES LA CLAVE PARA ALEXA
Route::get('/casos/info/nombre/{nombre}', [ApiController::class, 'getCaseInfoByName']); 


// ----------------------------------------------------------------------
// 2. Rutas protegidas por Token (API) - (DEL ORIGINAL Y TU USER)
// ----------------------------------------------------------------------
Route::middleware('auth:api')->get('/user', [UserController::class, 'show']);

// ----------------------------------------------------------------------
// 3. Rutas del Panel de Administración (Middleware WEB) - (DEL ORIGINAL)
// ----------------------------------------------------------------------
Route::middleware(['web'])->group(function () {
    // Dashboard principal
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
    
    // Rutas protegidas
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

    // Rutas del panel de administración
    Route::get('/admin/casos', [AdminDashboardController::class, 'getAllCases']);
    Route::get('/admin/capturistas', [AdminDashboardController::class, 'getCapturistas']);
    Route::get('/admin/casos', [AdminDashboardController::class, 'getAllCases']);
    Route::get('/admin/capturistas', [AdminDashboardController::class, 'getCapturistas']);
    Route::get('/admin/usuarios', [AdminDashboardController::class, 'getAllUsers']);
    Route::post('/admin/usuarios', [AdminDashboardController::class, 'storeUsuario']);
    Route::post('/admin/casos', [AdminDashboardController::class, 'storeCaso']);
    Route::put('/admin/casos/{id}', [AdminDashboardController::class, 'updateCaso']);
    Route::get('/admin/bitacora', [AdminDashboardController::class, 'getLogActividad']);
    Route::put('/admin/usuarios/{id}', [AdminDashboardController::class, 'updateUsuario']);
    Route::delete('/admin/usuarios/{id}', [AdminDashboardController::class, 'deleteUsuario']);
    Route::delete('/admin/casos/{id}', [AdminDashboardController::class, 'deleteCaso']);
});

// ----------------------------------------------------------------------
// 4. Rutas para el modulo Consultor - (DEL ORIGINAL)
// ----------------------------------------------------------------------
Route::middleware(['auth:api'])->prefix('consultor')->group(function () {

    // ---------- USUARIOS ----------
    Route::get('/usuarios/activos', [App\Http\Controllers\Api\Consultor\UsuariosController::class, 'activos']);
    Route::get('/usuarios/{id}', [App\Http\Controllers\Api\Consultor\UsuariosController::class, 'detalle']);

    // ---------- CASOS ----------
    Route::get('/casos', [App\Http\Controllers\Api\Consultor\CasosController::class, 'lista']);
    Route::get('/casos/{id}', [App\Http\Controllers\Api\Consultor\CasosController::class, 'detalle']);
    Route::get('/casos/{id}/asignados', [App\Http\Controllers\Api\Consultor\CasosController::class, 'usuariosAsignados']);

    // ---------- EVIDENCIAS ----------
    Route::get('/casos/{id}/evidencias', [App\Http\Controllers\Api\Consultor\EvidenciasController::class, 'porCaso']);

    // ---------- HERRAMIENTAS ----------
    Route::get('/herramientas', [App\Http\Controllers\Api\Consultor\HerramientasController::class, 'lista']);
    Route::get('/categorias/{id}/herramientas', [App\Http\Controllers\Api\Consultor\HerramientasController::class, 'porCategoria']);

    // ---------- ACCIONES e HISTORIAL ----------
    Route::get('/usuarios/{id}/acciones', [App\Http\Controllers\Api\Consultor\AccionesController::class, 'accionesDeUsuario']);
    Route::get('/casos/{id}/historial', [App\Http\Controllers\Api\Consultor\AccionesController::class, 'historialDeCaso']);

    // ---------- PLATAFORMAS ----------
    Route::get('/plataformas', [App\Http\Controllers\Api\Consultor\PlataformasController::class, 'vinculadas']);
});

// =======================
// RUTAS ACCIONES / HISTORIAL y PLATAFORMAS (DEL ORIGINAL - Refactorizadas)
// =======================

Route::prefix('consultor')->group(function () {

    // Acciones de un usuario
    Route::get('/usuarios/{id}/acciones', 
        [App\Http\Controllers\Api\Consultor\AccionesController::class, 'accionesUsuario']);

    // Historial de un caso
    Route::get('/casos/{id}/historial', 
        [App\Http\Controllers\Api\Consultor\AccionesController::class, 'historialCaso']);
    
    // Plataformas de un usuario
    Route::get('/usuarios/{id}/plataformas',
        [App\Http\Controllers\Api\Consultor\PlataformasController::class, 'plataformasUsuario']);

    // Todas las plataformas vinculadas
    Route::get('/plataformas',
        [App\Http\Controllers\Api\Consultor\PlataformasController::class, 'todasPlataformas']);
});