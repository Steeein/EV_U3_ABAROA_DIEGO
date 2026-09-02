<?php

namespace App\Http\Requests;

use App\Models\Proyecto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Validaciones para PUT/PATCH /api/proyectos/{id}.
 */
class UpdateProyectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * "sometimes" permite actualizaciones parciales con PATCH,
     * pero si el campo viene, no puede ir vacio ("required").
     * Con PUT se envian todos los campos y se validan todos.
     */
    public function rules(): array
    {
        return [
            'nombre'       => ['sometimes', 'required', 'string', 'max:255'],
            'fecha_inicio' => ['sometimes', 'required', 'date'],
            'estado'       => ['sometimes', 'required', 'string', Rule::in(Proyecto::ESTADOS)],
            'responsable'  => ['sometimes', 'required', 'string', 'max:255'],
            'monto'        => ['sometimes', 'required', 'numeric', 'min:0'],
            'created_by'   => ['sometimes', 'required', 'integer', 'exists:usuarios,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'  => 'El campo :attribute no puede estar vacio.',
            'estado.in' => 'El estado debe ser uno de: ' . implode(', ', Proyecto::ESTADOS) . '.',
        ];
    }

    /** Impide un PATCH con cuerpo vacio (no habria nada que actualizar). */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (empty($this->all())) {
                $validator->errors()->add(
                    'body',
                    'Debe enviar al menos un campo para actualizar.'
                );
            }
        });
    }

    /**
     * En la API responde JSON 422; en las vistas web redirige con los errores.
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
