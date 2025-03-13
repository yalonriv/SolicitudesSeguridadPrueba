<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FiltroSolicitudDTO;

class SolicitudesController extends Controller
{
     /**
     * Listar todas las solicitudes con filtrado opcional por estado y tipo de estudio.
     */
    public function index(FiltroSolicitudDTO $request)
    {
        $query = Solicitud::query();

        // Filtrar por estado si se envía en la consulta
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por tipo de estudio si se envía en la consulta
        if ($request->has('tipo_estudio_id')) {
            $query->where('tipo_estudio_id', $request->tipo_estudio_id);
        }

        return response()->json($query->with(['candidato', 'tipoEstudio'])->get());
    }

    /**
     * Crear una nueva solicitud.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'candidato_id' => 'required|exists:candidatos,id',
            'tipo_estudio_id' => 'required|exists:tipos_estudio,id',
            'estado' => ['required', Rule::in(Solicitud::getEstadosPermitidos())]
        ]);

        if($validated -> fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validated->errors(),
                'status' => 400 
            ];
            return response()->json($data);
        }

        $solicitud = Solicitud::create([
            'candidato_id' => $request->candidato_id,
            'tipo_estudio_id' => $request->tipo_estudio_id,
            'estado' => $request->estado,
            'fecha_solicitud' => now()
        ]);

        if(!$solicitud){
            $data = [
                'message' => 'Error al crear el candidato',
                'status' => 500 
            ];
            return response()->json($data);
        }

        $data = [
            'candidato' => $solicitud,
            'status' => 201 
        ];
        return response()->json($data);
    }

    /**
     * Mostrar una solicitud específica.
     */
    public function show($id)
    {
        $solicitud = Solicitud::with(['candidato', 'tipoEstudio'])->findOrFail($id);
        return response()->json($solicitud);
    }

    /**
     * Actualizar una solicitud.
     */
    public function update(Request $request, $id)
{
    try {
        $solicitud = Solicitud::findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            "message" => "Solicitud no encontrada",
            "status" => 404
        ], 404);
    }

    $validation = Validator::make($request->all(), [
        'estado' => ['sometimes', Rule::in(Solicitud::getEstadosPermitidos())]
    ]);

    if ($validation->fails()) {
        return response()->json([
            'message' => 'Error en la validación de los datos',
            'errors' => $validation->errors(),
            'status' => 400
        ], 400);
    }

    if ($request->has('estado')) {
        $solicitud->estado = $request->estado;
        if ($request->estado === 'completada' && !$solicitud->fecha_completado) {
            $solicitud->fecha_completado = now();
        }
    }
    if ($request->has('fecha_completado')) {
        $solicitud->fecha_completado = $request->fecha_completado;
    }

    $solicitud->save();

    return response()->json([
        'message' => 'Solicitud actualizada',
        'solicitud' => $solicitud,
        'status' => 200
    ], 200);
}

    /**
     * Eliminar una solicitud.
     */
    public function destroy($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return response()->json(['message' => 'Solicitud eliminada correctamente']);
    }

    public function cantidadSolicitudesPorEstado()
    {
        $solicitudesPorEstado = Solicitud::select('estado', DB::raw('count(*) as total'))
           ->groupBy('estado')
            ->get();

        return response()->json($solicitudesPorEstado);
    }

}
