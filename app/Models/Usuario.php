<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * Modelo de usuario de la Unidad 2 (tabla usuarios: nombre, correo, clave).
 * Unidad 3: implementa JWTSubject para poder emitir tokens JWT.
 */
class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'clave',
    ];

    protected $hidden = [
        'clave',
    ];

    /**
     * Como la columna de la contrasena se llama "clave" y no "password",
     * le indicamos a Laravel donde esta para que attempt() la valide.
     */
    public function getAuthPassword(): string
    {
        return $this->clave;
    }

    /** Identificador que se guarda en el claim "sub" del token. */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /** Claims personalizados adicionales del token. */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'created_by');
    }
}
