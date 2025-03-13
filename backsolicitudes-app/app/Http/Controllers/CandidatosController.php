<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Candidato;
use Illuminate\Support\Facades\Validator;

class CandidatosController extends Controller
{
    public function index()
    {
        $candidatos = Candidato::all();

        if($candidatos->isEmpty()){
            $data = [
                'message' => 'No se encontraron candidatos',
                'status' => 200
            ];
            return response() ->json($data);
        }
        return response()->json($candidatos,200);
    }

    public function store(Request $request){
       $validation = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'documento_identidad' => 'required|unique:candidatos',
            'correo' => 'required|email|unique:candidatos',
            'telefono' => 'required|digits:10'

        ]);

        if($validation -> fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validation->errors(),
                'status' => 400 
            ];
            return response()->json($data);
        }

        $candidato = Candidato::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'documento_identidad' => $request->documento_identidad,
            'correo' => $request->correo,
            'telefono' => $request->telefono
        ]);

        if(!$candidato){
            $data = [
                'message' => 'Error al crear el candidato',
                'status' => 500 
            ];
            return response()->json($data);
        }

        $data = [
            'candidato' => $candidato,
            'status' => 201 
        ];
        return response()->json($data);
    }

    public function show($id){
        $candidato = Candidato::find($id);

        if(!$candidato){
            $data = [
                "message" => "Candidato no encontrado",
                "status" => 400
            ];
            return response()->json($data);
        }

        $data = [
            "candidato" => $candidato,
            "status" => 200
        ];
        return response()->json($data);
    }

    public function delete($id){
        $candidato = Candidato::find($id);

        if(!$candidato){
            $data = [
                "message" => "Candidato no encontrado",
                "status" => 400
            ];
            return response()->json($data);
        }

        $candidato->delete();
        $data = [
            "message" => "Candidato eliminado",
            "status" => 200
        ];
        return response()->json($data);
    }

    public function update(Request $request, $id){
        $candidato = Candidato::find($id);

        if(!$candidato){
            $data = [
                "message" => "Candidato no encontrado",
                "status" => 400
            ];
            return response()->json($data);
        }

        $validation = Validator::make($request->all(), [
            'nombre' => 'max:100',
            'apellido' => 'max:100',
            'documento_identidad' => 'unique:candidatos',
            'correo' => 'email|unique:candidatos',
            'telefono' => 'digits:10'

        ]);

        if($validation -> fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validation->errors(),
                'status' => 400 
            ];
            return response()->json($data);
        }

        if($request->has('nombre')){
            $candidato->nombre = $request->nombre;
        }
        if($request->has('apellido')){
            $candidato->apellido = $request->apellido;
        }
        if($request->has('documento_identidad')){
            $candidato->documento_identidad = $request->documento_identidad;
        }
        if($request->has('correo')){
            $candidato->correo = $request->correo;
        }
        if($request->has('telefono')){
            $candidato->telefono = $request->telefono;
        }

        $candidato->save();
        $data = [
            'message' => 'Candidato actualizado',
            'candidato' => $candidato,
            'status' => 201 
        ];
        return response()->json($data);
    }

}
