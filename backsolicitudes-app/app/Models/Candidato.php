<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id ID del candidato.
 * @property string $nombre Nombre del candidato.
 * @property string $apellido Apellido del candidato.
 * @property string $documento_identidad Documento de identidad único del candidato.
 * @property string $correo Correo electrónico único del candidato.
 * @property string $telefono Número de teléfono del candidato.
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación del registro.
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última actualización.
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Solicitud[] $solicitudes Relación con solicitudes del candidato.
 */
class Candidato extends Model
{
    use HasFactory;

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'candidatos';

    /** @var array Campos que pueden ser asignados masivamente */
    protected $fillable = [
        'nombre',
        'apellido',
        'documento_identidad',
        'correo',
        'telefono'
    ];

    /**
     * Relación con Solicitud.
     * Un candidato puede tener muchas solicitudes.
     *
     * @return HasMany
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }
}
