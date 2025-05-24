<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\RetroItemController;

Route::get('/test', function () {
    return response()->json(['ok' => true]);
});

Route::post('/sprints', [SprintController::class, 'store']);
// Rutas de Sprints
Route::apiResource('sprints', SprintController::class);

// Rutas de RetroItems
Route::apiResource('retro-items', RetroItemController::class);

// Rutas específicas de items por sprint
Route::get('sprints/{sprint}/retro-items', [RetroItemController::class, 'getBySprintId']);
Route::get('sprints/{sprint}/acciones', [RetroItemController::class, 'getAcciones']);
Route::get('sprints/{sprint}/logros', [RetroItemController::class, 'getLogros']);
Route::get('sprints/{sprint}/impedimentos', [RetroItemController::class, 'getImpedimentos']);
Route::get('sprints/{sprint}/comentarios', [RetroItemController::class, 'getComentarios']);

// Funcionalidades específicas para acciones
Route::patch('retro-items/{retroItem}/toggle-cumplida', [RetroItemController::class, 'toggleCumplida']);
Route::get('acciones/pendientes', [RetroItemController::class, 'getAccionesPendientes']);
Route::get('acciones/cumplidas', [RetroItemController::class, 'getAccionesCumplidas']);

// Estadísticas y reportes
Route::get('sprints/{sprint}/estadisticas', [SprintController::class, 'getEstadisticas']);
Route::get('estadisticas/general', [SprintController::class, 'getEstadisticasGenerales']);

// Buscar y filtrar
Route::get('retro-items/search', [RetroItemController::class, 'search']);
Route::get('sprints/activos', [SprintController::class, 'getSprintsActivos']);