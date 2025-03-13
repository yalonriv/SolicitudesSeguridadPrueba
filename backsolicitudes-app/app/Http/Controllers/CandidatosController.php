<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidato;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


/**
 * @group Gestión de candidatos
 *
 * API para la gestión de candidatos.
 */
class CandidatosController extends Controller
{
    /**
     * Obtener la lista de candidatos.
     *
     * @OA\Get(
     *     path="/api/candidatos",
     *     summary="Listar todos los candidatos",
     *     tags={"Candidatos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de candidatos obtenida correctamente"     *     )
     * )
     */
    public function index()
    {
        $candidatos = Candidato::all();

        if ($candidatos->isEmpty()) {
            Log::warning('No se encontraron candidatos');
            return response()->json(['message' => 'No se encontraron candidatos', 'status' => 200]);
        }
        return response()->json($candidatos, 200);
    }

    /**
     * Crear un nuevo candidato.
     *
     * @OA\Post(
     *     path="/api/candidatos",
     *     summary="Registrar un candidato",
     *     tags={"Candidatos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","apellido","documento_identidad","correo","telefono"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Juan"),
     *             @OA\Property(property="apellido", type="string", maxLength=100, example="Pérez"),
     *             @OA\Property(property="documento_identidad", type="string", example="12345678"),
     *             @OA\Property(property="correo", type="string", format="email", example="juan.perez@example.com"),
     *             @OA\Property(property="telefono", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Candidato creado exitosamente"),
     *     @OA\Response(response=400, description="Error en la validación de los datos")
     * )
     */
    public function store(Request $request)
    {
        Log::info('Intentando registrar un candidato', ['request' => $request->all()]);

        $validation = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'documento_identidad' => 'required|unique:candidatos',
            'correo' => 'required|email|unique:candidatos',
            'telefono' => 'required|digits:10'
        ]);

        if ($validation->fails()) {
            return response()->json(['message' => 'Error en la validación de los datos', 'errors' => $validation->errors(), 'status' => 400]);
        }

        $candidato = Candidato::create($request->all());
        Log::info('Candidato registrado exitosamente', ['id' => $candidato->id]);
        return response()->json(['candidato' => $candidato, 'status' => 201]);
    }

    /**
     * Obtener un candidato por ID.
     *
     * @OA\Get(
     *     path="/api/candidatos/{id}",
     *     summary="Obtener un candidato específico",
     *     tags={"Candidatos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del candidato"
     *     ),
     *     @OA\Response(response=200, description="Candidato encontrado"),
     *     @OA\Response(response=400, description="Candidato no encontrado")
     * )
     */
    public function show($id)
    {
        Log::info('Buscando candidato', ['id' => $id]);
        $candidato = Candidato::find($id);

        if (!$candidato) {
            Log::warning('Candidato no encontrado', ['id' => $id]);
            return response()->json(["message" => "Candidato no encontrado", "status" => 400]);
        }
        return response()->json(["candidato" => $candidato, "status" => 200]);
    }

    /**
     * Actualizar datos de un candidato.
     *
     * @OA\Put(
     *     path="/api/candidatos/{id}",
     *     summary="Actualizar un candidato",
     *     tags={"Candidatos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del candidato"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido", type="string", maxLength=100),
     *             @OA\Property(property="documento_identidad", type="string"),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="telefono", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Candidato actualizado"),
     *     @OA\Response(response=400, description="Candidato no encontrado o error de validación")
     * )
     */
    public function update(Request $request, $id)
    {
        Log::info('Intentando actualizar candidato', ['id' => $id, 'request' => $request->all()]);

        $candidato = Candidato::find($id);

        if (!$candidato) {
            Log::warning('Candidato no encontrado para actualización', ['id' => $id]);
            return response()->json(["message" => "Candidato no encontrado", "status" => 400]);
        }

        $candidato->update($request->all());

        Log::info('Candidato actualizado exitosamente', ['id' => $id]);
        
        return response()->json(['message' => 'Candidato actualizado', 'candidato' => $candidato, 'status' => 201]);
    }

    /**
     * Eliminar un candidato.
     *
     * @OA\Delete(
     *     path="/api/candidatos/{id}",
     *     summary="Eliminar un candidato",
     *     tags={"Candidatos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del candidato"
     *     ),
     *     @OA\Response(response=200, description="Candidato eliminado"),
     *     @OA\Response(response=400, description="Candidato no encontrado")
     * )
     */
    public function delete($id)
    {
        $candidato = Candidato::find($id);

        Log::info('Intentando eliminar candidato', ['id' => $id]);

        if (!$candidato) {
            Log::warning('Candidato no encontrado para eliminación', ['id' => $id]);
            return response()->json(["message" => "Candidato no encontrado", "status" => 400]);
        }

        $candidato->delete();

        Log::info('Candidato eliminado exitosamente', ['id' => $id]);

        return response()->json(["message" => "Candidato eliminado", "status" => 200]);
    }
}
