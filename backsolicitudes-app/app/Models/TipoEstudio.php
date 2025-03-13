<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEstudio extends Model
{
    use HasFactory;
    protected $table = 'tipos_estudio';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio'
    ];

    /**
     * Relación con Solicitud (Un tipo de estudio puede estar en muchas solicitudes).
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'tipo_estudio_id');
    }
}
