<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo TipoEstudio
 *
 * Representa un tipo de estudio que puede estar asociado a múltiples solicitudes.
 *
 * @property int $id ID del tipo de estudio.
 * @property string $nombre Nombre del tipo de estudio.
 * @property string $descripcion Descripción del tipo de estudio.
 * @property float $precio Precio del tipo de estudio.
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación del registro.
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última actualización.
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Solicitud[] $solicitudes Relación con las solicitudes.
 */
class TipoEstudio extends Model
{
    use HasFactory;

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'tipos_estudio';

    /** @var array Campos que pueden ser asignados masivamente */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio'
    ];

    /**
     * Relación con Solicitud.
     * Un tipo de estudio puede estar en muchas solicitudes.
     *
     * @return HasMany
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'tipo_estudio_id');
    }
}
