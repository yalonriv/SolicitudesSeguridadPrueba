<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo User
 *
 * Representa a un usuario del sistema con autenticación.
 *
 * @property int $id ID del usuario.
 * @property string $name Nombre del usuario.
 * @property string $email Correo electrónico del usuario.
 * @property string $password Contraseña encriptada del usuario.
 * @property string|null $remember_token Token para recordar sesión.
 * @property \Illuminate\Support\Carbon|null $email_verified_at Fecha de verificación del correo electrónico.
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación del usuario.
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última actualización del usuario.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Atributos asignables masivamente.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos en la serialización.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos con casting automático.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
