<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:255'],
            'correo' => ['required', 'email', 'max:255', 'unique:usuarios,correo'],
            'clave'  => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email'    => 'Debe ingresar un correo válido.',
            'correo.unique'   => 'Este correo ya está registrado.',
            'clave.required'  => 'La clave es obligatoria.',
            'clave.min'       => 'La clave debe tener al menos 6 caracteres.',
            'clave.confirmed' => 'La confirmación de la clave no coincide.',
        ];
    }
}
