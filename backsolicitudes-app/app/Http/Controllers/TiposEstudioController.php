<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\TipoEstudio;

/**
 * @group Gestión de Tipos de Estudio
 *
 * API para la gestión de tipos de estudio en el sistema.
 */
class TiposEstudioController extends Controller
{
    /**
     * Crear un nuevo tipo de estudio.
     *
     * @bodyParam nombre string requerido Nombre del tipo de estudio (máx: 100 caracteres). Ejemplo: "Prueba de polígrafo"
     * @bodyParam descripcion string requerido Descripción del tipo de estudio (máx: 500 caracteres). Ejemplo: "Un estudio avanzado para detectar la verdad."
     * @bodyParam precio float requerido Precio del estudio (mín: 0, máx: 99999999.99). Ejemplo: 150.50
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 201 {
     *   "tipoEstudio": {
     *     "id": 1,
     *     "nombre": "Prueba de polígrafo",
     *     "descripcion": "Un estudio avanzado para detectar la verdad.",
     *     "precio": 150.50
     *   },
     *   "status": 201
     * }
     */
    public function store(Request $request)
    {
        Log::info('Intentando crear un nuevo tipo de estudio', ['request' => $request->all()]);

        $validation = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'descripcion' => 'required|max:500',
            'precio' => 'required|numeric|min:0|max:99999999.99'
        ]);

        if ($validation->fails()) {
            Log::error('Error en validación al crear tipo de estudio', ['errors' => $validation->errors()]);
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validation->errors(),
                'status' => 400
            ], 400);
        }

        $tipoEstudio = TipoEstudio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio
        ]);

        if (!$tipoEstudio) {
            Log::error('Error al crear el tipo de estudio');
            return response()->json([
                'message' => 'Error al crear el tipo de estudio',
                'status' => 500
            ], 500);
        }

        Log::info('Tipo de estudio creado con éxito', ['tipoEstudio' => $tipoEstudio]);

        return response()->json([
            'tipoEstudio' => $tipoEstudio,
            'status' => 201
        ], 201);
    }

    /**
     * Obtener todos los tipos de estudio.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 [{
     *   "id": 1,
     *   "nombre": "Prueba de polígrafo",
     *   "descripcion": "Incluye pruebas estándar.",
     *   "precio": 100.00
     * }]
     */
    public function obtenerTiposEstudio()
    {
        Log::info('Obteniendo lista de tipos de estudio');

        $tiposEstudio = TipoEstudio::all();

        if ($tiposEstudio->isEmpty()) {
            Log::warning('No se encontraron tipos de estudio en la base de datos');
            return response()->json([
                'message' => 'No se encontraron tipos de estudio',
                'status' => 200
            ], 200);
        }

        Log::info('Lista de tipos de estudio obtenida con éxito', ['total' => count($tiposEstudio)]);

        return response()->json($tiposEstudio, 200);
    }
}
