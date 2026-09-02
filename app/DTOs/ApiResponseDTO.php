<?php

namespace App\DTOs;

/**
 * Estructura estandar de respuesta para los endpoints de autenticacion:
 * code, message y data (segun el Manual de Integracion JWT del curso).
 */
class ApiResponseDTO
{
    public function __construct(
        public int $code,
        public string $message,
        public mixed $data = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'code'    => $this->code,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }
}
