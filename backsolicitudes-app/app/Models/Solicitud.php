<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Solicitud
 *
 * Representa una solicitud asociada a un candidato y un tipo de estudio.
 *
 * @property int $id ID de la solicitud.
 * @property int $candidato_id ID del candidato asociado a la solicitud.
 * @property int $tipo_estudio_id ID del tipo de estudio solicitado.
 * @property string $estado Estado de la solicitud (pendiente, en_proceso, completada).
 * @property \Illuminate\Support\Carbon $fecha_solicitud Fecha en la que se creó la solicitud.
 * @property \Illuminate\Support\Carbon|null $fecha_completado Fecha en la que la solicitud fue completada (puede ser nula).
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación del registro.
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última actualización.
 *
 * @property \App\Models\Candidato $candidato Relación con el candidato.
 * @property \App\Models\TipoEstudio $tipoEstudio Relación con el tipo de estudio.
 */
class Solicitud extends Model
{
    use HasFactory;

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'solicitudes';

    /** @var array Campos que pueden ser asignados masivamente */
    protected $fillable = [
        'candidato_id',
        'tipo_estudio_id',
        'estado',
        'fecha_solicitud',
        'fecha_completado'
    ];

    /** @var array Conversión automática de fechas */
    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_completado' => 'date',
    ];

    /**
     * Relación con el modelo Candidato.
     * Una solicitud pertenece a un candidato.
     *
     * @return BelongsTo
     */
    public function candidato(): BelongsTo
    {
        return $this->belongsTo(Candidato::class);
    }

    /**
     * Relación con el modelo TipoEstudio.
     * Una solicitud pertenece a un tipo de estudio.
     *
     * @return BelongsTo
     */
    public function tipoEstudio(): BelongsTo
    {
        return $this->belongsTo(TipoEstudio::class, 'tipo_estudio_id');
    }

    /**
     * Lista de estados permitidos para una solicitud.
     *
     * @return string[]
     */
    public static function getEstadosPermitidos(): array
    {
        return ['pendiente', 'en_proceso', 'completada'];
    }
}
