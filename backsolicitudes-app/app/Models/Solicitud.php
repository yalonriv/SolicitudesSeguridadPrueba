<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'candidato_id',
        'tipo_estudio_id',
        'estado',
        'fecha_solicitud',
        'fecha_completado'
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_completado' => 'date',
    ];

    /**
     * Relación con el modelo Candidato.
     */
    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    /**
     * Relación con el modelo TipoEstudio.
     */
    public function tipoEstudio()
    {
        return $this->belongsTo(TipoEstudio::class, 'tipo_estudio_id');
    }

    /**
     * Lista de estados permitidos.
     */
    public static function getEstadosPermitidos()
    {
        return ['pendiente', 'en_proceso', 'completada'];
    }
}
