<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\TiposEstudioController;
use App\Http\Controllers\AuthController;

/**
 * API Routes
 * 
 * Este archivo contiene la definición de las rutas para el acceso a la API,
 * incluyendo autenticación, manejo de candidatos, solicitudes y tipos de estudio.
 */

/**
 * @route POST /login
 * @description Iniciar sesión y obtener un token de autenticación.
 * @access Público
 */
Route::post('/login', [AuthController::class, 'login']);

// RUTAS PROTEGIDAS POR AUTENTICACIÓN
Route::middleware('auth:sanctum')->group(function () {
    
    /**
     * @route GET /candidatos
     * @description Obtener la lista de todos los candidatos.
     * @access Requiere autenticación
     */
    Route::get('/candidatos', [CandidatosController::class, 'index']);

    /**
     * @route GET /candidatos/{id}
     * @description Obtener los datos de un candidato específico.
     * @access Requiere autenticación
     */
    Route::get('/candidatos/{id}', [CandidatosController::class, 'show']);

    /**
     * @route POST /candidatos
     * @description Crear un nuevo candidato.
     * @access Requiere autenticación
     */
    Route::post('/candidatos', [CandidatosController::class, 'store']);

    /**
     * @route PUT /candidatos/{id}
     * @description Actualizar la información de un candidato.
     * @access Requiere autenticación
     */
    Route::put('/candidatos/{id}', [CandidatosController::class, 'update']);

    /**
     * @route DELETE /candidatos/{id}
     * @description Eliminar un candidato.
     * @access Requiere autenticación
     */
    Route::delete('/candidatos/{id}', [CandidatosController::class, 'delete']);

    /**
     * @route POST /tiposEstudio
     * @description Crear un nuevo tipo de estudio.
     * @access Requiere autenticación
     */
    Route::post('/tiposEstudio', [TiposEstudioController::class, 'store']);

    /**
     * @route GET /tiposEstudio
     * @description Obtener todos los tipos de estudio disponibles.
     * @access Requiere autenticación
     */
    Route::get('/tiposEstudio', [TiposEstudioController::class, 'obtenerTiposEstudio']);

    /**
     * @route GET /solicitudes
     * @description Obtener la lista de solicitudes con filtros opcionales.
     * @access Requiere autenticación
     */
    Route::get('/solicitudes', [SolicitudesController::class, 'index']);

    /**
     * @route GET /solicitudes/{id}
     * @description Obtener los datos de una solicitud específica.
     * @access Requiere autenticación
     */
    Route::get('/solicitudes/{id}', [SolicitudesController::class, 'show']);

    /**
     * @route POST /solicitudes
     * @description Crear una nueva solicitud.
     * @access Requiere autenticación
     */
    Route::post('/solicitudes', [SolicitudesController::class, 'store']);

    /**
     * @route PUT /solicitudes/{id}
     * @description Actualizar la información de una solicitud.
     * @access Requiere autenticación
     */
    Route::put('/solicitudes/{id}', [SolicitudesController::class, 'update']);

    /**
     * @route DELETE /solicitudes/{id}
     * @description Eliminar una solicitud.
     * @access Requiere autenticación
     */
    Route::delete('/solicitudes/{id}', [SolicitudesController::class, 'destroy']);

    /**
     * @route GET /solicitudes-estadisticas
     * @description Obtener la cantidad de solicitudes agrupadas por estado.
     * @access Requiere autenticación
     */
    Route::get('/solicitudes-estadisticas', [SolicitudesController::class, 'cantidadSolicitudesPorEstado']);

    // SOLO USUARIOS AUTENTICADOS PUEDEN VER SU INFO O CERRAR SESIÓN
    /**
     * @route GET /user
     * @description Obtener información del usuario autenticado.
     * @access Requiere autenticación
     */
    Route::get('/user', [AuthController::class, 'user']);

    /**
     * @route POST /logout
     * @description Cerrar sesión del usuario autenticado.
     * @access Requiere autenticación
     */
    Route::post('/logout', [AuthController::class, 'logout']);
});
