<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importamos los controladores aquí arriba para mantener las rutas limpias
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AlexaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ----------------------------------------------------------------------
// 1. Rutas de autenticación (Públicas)
// ----------------------------------------------------------------------
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/verify', [AuthController::class, 'verify']);

// ----------------------------------------------------------------------
// 2. Rutas protegidas por Token (API)
// ----------------------------------------------------------------------
Route::middleware('auth:api')->get('/user', [UserController::class, 'show']);

// ----------------------------------------------------------------------
// 3. Rutas del Panel de Administración (Middleware WEB)
// ----------------------------------------------------------------------
// Nota: Usas el middleware 'web' aquí. Esto habilitará sesiones y protección CSRF.
// Asegúrate de que esto es lo que quieres en un archivo api.php.

Route::middleware(['web'])->group(function () {
    // Dashboard principal
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
});
// Rutas protegidas
Route::middleware('auth:api')->get('/user', [App\Http\Controllers\Api\UserController::class, 'show']);
// Ruta para el dashboard del administrador
Route::middleware(['web'])->get('/admin/dashboard', [AdminDashboardController::class, 'index']);

Route::middleware(['web'])->group(function () {
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

//Rutas para el modulo Consultor
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
// RUTAS ACCIONES / HISTORIAL
// =======================

Route::prefix('consultor')->group(function () {

    // Acciones de un usuario
    Route::get('/usuarios/{id}/acciones', 
        [App\Http\Controllers\Api\Consultor\AccionesController::class, 'accionesUsuario']);

    // Historial de un caso
    Route::get('/casos/{id}/historial', 
        [App\Http\Controllers\Api\Consultor\AccionesController::class, 'historialCaso']);
});
// =======================
// RUTAS PLATAFORMAS
// =======================
Route::prefix('consultor')->group(function () {

    // Plataformas de un usuario
    Route::get('/usuarios/{id}/plataformas',
        [App\Http\Controllers\Api\Consultor\PlataformasController::class, 'plataformasUsuario']);

    // Todas las plataformas vinculadas
    Route::get('/plataformas',
        [App\Http\Controllers\Api\Consultor\PlataformasController::class, 'todasPlataformas']);
});

