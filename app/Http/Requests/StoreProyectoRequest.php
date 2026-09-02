<?php

namespace App\Http\Requests;

use App\Models\Proyecto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Validaciones para POST /api/proyectos.
 * Apunte 3, punto 3.2: clases de Form Request.
 */
class StoreProyectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Si no se envia created_by, se toma del usuario autenticado por JWT.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('created_by') && auth('api')->check()) {
            $this->merge(['created_by' => auth('api')->id()]);
        }
    }

    /**
     * Requisito de la evaluacion:
     * "Todos los campos son requeridos y no deben estar vacios".
     */
    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'estado'       => ['required', 'string', Rule::in(Proyecto::ESTADOS)],
            'responsable'  => ['required', 'string', 'max:255'],
            'monto'        => ['required', 'numeric', 'min:0'],
            'created_by'   => ['required', 'integer', 'exists:usuarios,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'          => 'El campo :attribute es obligatorio y no puede estar vacio.',
            'estado.in'         => 'El estado debe ser uno de: ' . implode(', ', Proyecto::ESTADOS) . '.',
            'monto.numeric'     => 'El campo monto debe ser un valor numerico.',
            'monto.min'         => 'El campo monto no puede ser negativo.',
            'fecha_inicio.date' => 'El campo fecha de inicio debe ser una fecha valida (YYYY-MM-DD).',
            'created_by.exists' => 'El usuario indicado en created_by no existe.',
        ];
    }

    /**
     * En la API fuerza SIEMPRE una respuesta JSON 422, aunque el cliente
     * no envie el header Accept: application/json.
     * En las vistas web mantiene el comportamiento normal de Laravel
     * (redireccion con los errores en la sesion).
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->is('api/*') || $this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Los datos enviados no son validos.',
                    'errors'  => $validator->errors(),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
