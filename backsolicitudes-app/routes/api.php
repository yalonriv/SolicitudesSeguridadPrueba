<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\TiposEstudioController;
use App\Http\Controllers\AuthController;

// ✅ LOGIN SIN AUTENTICACIÓN
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/candidatos', [CandidatosController::class, 'index']);
    Route::get('/candidatos/{id}', [CandidatosController::class, 'show']);
    Route::post('/candidatos', [CandidatosController::class, 'store']);
    Route::put('/candidatos/{id}', [CandidatosController::class, 'update']);
    Route::delete('/candidatos/{id}', [CandidatosController::class, 'delete']);

    Route::post('/tiposEstudio', [TiposEstudioController::class, 'store']);
    Route::get('/tiposEstudio', [TiposEstudioController::class, 'obtenerTiposEstudio']);

    Route::get('/solicitudes', [SolicitudesController::class, 'index']);
    Route::get('/solicitudes/{id}', [SolicitudesController::class, 'show']);
    Route::post('/solicitudes', [SolicitudesController::class, 'store']);
    Route::put('/solicitudes/{id}', [SolicitudesController::class, 'update']);
    Route::delete('/solicitudes/{id}', [SolicitudesController::class, 'destroy']);
    Route::get('/solicitudes-estadisticas', [SolicitudesController::class, 'cantidadSolicitudesPorEstado']);

    // ✅ SOLO USUARIOS AUTENTICADOS PUEDEN VER SU INFO O CERRAR SESIÓN
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
