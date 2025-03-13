<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @group DTO de Filtro de Solicitudes
 *
 * Data Transfer Object para filtrar solicitudes según estado o tipo de estudio.
 */
class FiltroSolicitudDTO extends FormRequest
{
    /**
     * Reglas de validación para los filtros de solicitud.
     *
     * @return array
     *
     * @bodyParam estado string Opcional. Filtra las solicitudes por estado. Debe ser uno de: `pendiente`, `en_proceso`, `completada`. Example: "pendiente"
     * @bodyParam tipo_estudio_id integer Opcional. Filtra las solicitudes por el ID del tipo de estudio. Debe existir en la tabla `tipos_estudio`. Example: 3
     */
    public function rules()
    {
        return [
            'estado' => 'nullable|string|in:pendiente,en_proceso,completada',
            'tipo_estudio_id' => 'nullable|integer|exists:tipos_estudio,id'
        ];
    }

}
