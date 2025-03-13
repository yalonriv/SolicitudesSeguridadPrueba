<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Candidato extends Model
{
    use HasFactory;
    protected $table = 'candidatos';
    protected $fillable = [
        'nombre',
        'apellido',
        'documento_identidad',
        'correo',
        'telefono'
    ];

    /**
     * Relación con Solicitud (Un candidato puede tener muchas solicitudes).
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
