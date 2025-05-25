<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\RetroItemController;

// Test route
Route::get('/test', fn() => response()->json(['ok' => true]));

// --- SPRINTS ---
Route::apiResource('sprints', SprintController::class)->except(['store']);
Route::post('sprints', [SprintController::class, 'store']); // Para compatibilidad si es necesario
Route::get('sprints/activos', [SprintController::class, 'getSprintsActivos']);
Route::get('sprints/{sprint}/estadisticas', [SprintController::class, 'getEstadisticas']);
Route::get('estadisticas/general', [SprintController::class, 'getEstadisticasGenerales']);

// --- RETRO ITEMS ---
Route::apiResource('retro-items', RetroItemController::class);

Route::get('retro-items/categorias', function () {
    return response()->json([
        'categorias' => ['accion', 'logro', 'impedimento', 'comentario', 'otro']
    ]);
});
// RetroItems por Sprint y categorías
Route::prefix('sprints/{sprint}')->group(function () {
    Route::get('retro-items', [RetroItemController::class, 'getBySprintId']);
    Route::get('acciones', [RetroItemController::class, 'getAcciones']);
    Route::get('logros', [RetroItemController::class, 'getLogros']);
    Route::get('impedimentos', [RetroItemController::class, 'getImpedimentos']);
    Route::get('comentarios', [RetroItemController::class, 'getComentarios']);
});

// --- ACCIONES ---
Route::patch('retro-items/{retroItem}/toggle-cumplida', [RetroItemController::class, 'toggleCumplida']);
Route::get('acciones/pendientes', [RetroItemController::class, 'getAccionesPendientes']);
Route::get('acciones/cumplidas', [RetroItemController::class, 'getAccionesCumplidas']);

// --- BUSCAR Y FILTRAR ---
Route::get('retro-items/search', [RetroItemController::class, 'search']);