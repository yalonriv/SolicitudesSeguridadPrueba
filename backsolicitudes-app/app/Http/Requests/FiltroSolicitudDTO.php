<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltroSolicitudDTO extends FormRequest
{
    public function rules()
    {
        return [
            'estado' => 'nullable|string|in:pendiente,en_proceso,completada',
            'tipo_estudio_id' => 'nullable|integer|exists:tipos_estudio,id'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
