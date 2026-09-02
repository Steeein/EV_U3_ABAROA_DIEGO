<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Eloquent de Proyecto.
 * Unidad 3: reemplaza los datos estaticos de la Unidad 1 por persistencia real
 * en la base de datos a traves del ORM.
 */
class Proyecto extends Model
{
    use HasFactory;

    /** Estados validos. Se usan en las reglas de los Form Request. */
    public const ESTADOS = [
        'Pendiente',
        'En Proceso',
        'Finalizado',
        'Cancelado',
    ];

    protected $table = 'proyectos';

    /**
     * Asignacion masiva controlada: protege contra Mass Assignment
     * (criterio "de manera segura" del indicador 4 de la rubrica).
     */
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'estado',
        'responsable',
        'monto',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date:Y-m-d',
            'monto'        => 'decimal:2',
            'created_by'   => 'integer',
        ];
    }

    /** created_by guarda el id del usuario que creo el proyecto. */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }
}
