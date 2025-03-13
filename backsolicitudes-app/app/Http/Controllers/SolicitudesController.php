<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\FiltroSolicitudDTO;

/**
 * @group Gestión de Solicitudes
 *
 * API para gestionar solicitudes en el sistema.
 */
class SolicitudesController extends Controller
{
    /**
     * Listar todas las solicitudes con filtrado opcional.
     *
     * @queryParam estado string Estado de la solicitud. Ejemplo: pendiente
     * @queryParam tipo_estudio_id int ID del tipo de estudio. Ejemplo: 1
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response 200 [{
     *   "id": 1,
     *   "candidato_id": 2,
     *   "tipo_estudio_id": 3,
     *   "estado": "pendiente",
     *   "fecha_solicitud": "2025-03-13"
     * }]
     */
    public function index(FiltroSolicitudDTO $request)
    {
        Log::info('Obteniendo listado de solicitudes', ['filtros' => $request->all()]);

        $query = Solicitud::query();

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('tipo_estudio_id')) {
            $query->where('tipo_estudio_id', $request->tipo_estudio_id);
        }

        return response()->json($query->with(['candidato', 'tipoEstudio'])->get());
    }

    /**
     * Crear una nueva solicitud.
     *
     * @bodyParam candidato_id int requerido ID del candidato. Ejemplo: 2
     * @bodyParam tipo_estudio_id int requerido ID del tipo de estudio. Ejemplo: 3
     * @bodyParam estado string requerido Estado de la solicitud (pendiente, aprobada, rechazada). Ejemplo: pendiente
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 201 {
     *   "solicitud": {
     *     "id": 1,
     *     "candidato_id": 2,
     *     "tipo_estudio_id": 3,
     *     "estado": "pendiente",
     *     "fecha_solicitud": "2025-03-13"
     *   },
     *   "status": 201
     * }
     */
    public function store(Request $request)
    {
        Log::info('Intentando crear una nueva solicitud', ['request' => $request->all()]);

        $validated = Validator::make($request->all(), [
            'candidato_id' => 'required|exists:candidatos,id',
            'tipo_estudio_id' => 'required|exists:tipos_estudio,id',
            'estado' => ['required', Rule::in(Solicitud::getEstadosPermitidos())]
        ]);

        if ($validated->fails()) {
            Log::error('Error en validación al crear solicitud', ['errors' => $validated->errors()]);
            return response()->json(['message' => 'Error en la validación de los datos', 'errors' => $validated->errors(), 'status' => 400]);
        }

        $solicitud = Solicitud::create([
            'candidato_id' => $request->candidato_id,
            'tipo_estudio_id' => $request->tipo_estudio_id,
            'estado' => $request->estado,
            'fecha_solicitud' => now()
        ]);

        if (!$solicitud) {
            Log::error('Error al crear la solicitud');
            return response()->json(['message' => 'Error al crear la solicitud', 'status' => 500]);
        }

        Log::info('Solicitud creada con éxito', ['solicitud' => $solicitud]);

        return response()->json(['solicitud' => $solicitud, 'status' => 201]);
    }

    /**
     * Mostrar una solicitud específica.
     *
     * @urlParam id int requerido ID de la solicitud. Ejemplo: 1
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "id": 1,
     *   "candidato_id": 2,
     *   "tipo_estudio_id": 3,
     *   "estado": "pendiente",
     *   "fecha_solicitud": "2025-03-13"
     * }
     */
    public function show($id)
    {
        Log::info("Consultando solicitud con ID: $id");

        $solicitud = Solicitud::with(['candidato', 'tipoEstudio'])->findOrFail($id);

        return response()->json($solicitud);
    }

    /**
     * Actualizar una solicitud.
     *
     * @urlParam id int requerido ID de la solicitud. Example: 1
     * @bodyParam estado string Estado nuevo de la solicitud. Example: aprobada
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "message": "Solicitud actualizada",
     *   "solicitud": {
     *     "id": 1,
     *     "estado": "aprobada",
     *     "fecha_completado": "2025-03-13"
     *   },
     *   "status": 200
     * }
     */
    public function updateEstado(Request $request, $id)
    {
        try {
            Log::info("Intentando actualizar solicitud con ID: $id");
            $solicitud = Solicitud::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Solicitud con ID: $id no encontrada");
            return response()->json(["message" => "Solicitud no encontrada", "status" => 404], 404);
        }

        $validation = Validator::make($request->all(), [
            'estado' => ['required', Rule::in(Solicitud::getEstadosPermitidos())]
        ]);

        if ($validation->fails()) {
            Log::error('Error en validación al actualizar solicitud', ['errors' => $validation->errors()]);
            return response()->json(['message' => 'Error en la validación de los datos', 'errors' => $validation->errors(), 'status' => 400], 400);
        }

        if ($request->has('estado')) {
            $solicitud->estado = $request->estado;
            if ($request->estado === 'completada' && !$solicitud->fecha_completado) {
                $solicitud->fecha_completado = now();
            }
        }

        $solicitud->save();
        Log::info("Solicitud con ID: $id actualizada");

        return response()->json(['message' => 'Solicitud actualizada', 'solicitud' => $solicitud, 'status' => 200], 200);
    }

    /**
     * Eliminar una solicitud.
     *
     * @urlParam id int requerido ID de la solicitud. Ejemplo: 1
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response 200 {
     *   "message": "Solicitud eliminada correctamente"
     * }
     */
    public function destroy($id)
    {
        Log::warning("Eliminando solicitud con ID: $id");

        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return response()->json(['message' => 'Solicitud eliminada correctamente']);
    }

    /**
     * Obtener la cantidad de solicitudes por estado.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 [{"estado": "pendiente", "total": 5}]
     */
    public function cantidadSolicitudesPorEstado()
    {
        $solicitudesPorEstado = Solicitud::select('estado', DB::raw('count(*) as total'))->groupBy('estado')->get();

        return response()->json($solicitudesPorEstado);
    }
}
