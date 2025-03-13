<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\TipoEstudio;

class TiposEstudioController extends Controller
{
    public function store(Request $request){
        $validation = Validator::make($request->all(), [
             'nombre' => 'required|max:100',
             'descripcion' => 'required|max:500',
             'precio' => 'required|numeric|min:0|max:99999999.99'
 
         ]);
 
         if($validation -> fails()){
             $data = [
                 'message' => 'Error en la validación de los datos',
                 'errors' => $validation->errors(),
                 'status' => 400 
             ];
             return response()->json($data);
         }
 
         $tipoEstudio = TipoEstudio::create([
             'nombre' => $request->nombre,
             'descripcion' => $request->descripcion,
             'precio' => $request->precio
         ]);
 
         if(!$tipoEstudio){
             $data = [
                 'message' => 'Error al crear el tipo de estudio',
                 'status' => 500 
             ];
             return response()->json($data);
         }
 
         $data = [
             'tipoEstudio' => $tipoEstudio,
             'status' => 201 
         ];
         return response()->json($data);
     }

     public function obtenerTiposEstudio()
    {
        $tiposEstudio = TipoEstudio::all();

        if($tiposEstudio->isEmpty()){
            $data = [
                'message' => 'No se encontraron tipos de estudio',
                'status' => 200
            ];
            return response() ->json($data);
        }
        return response()->json($tiposEstudio,200);
    }
}
