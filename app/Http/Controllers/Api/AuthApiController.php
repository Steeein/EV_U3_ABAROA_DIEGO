<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponseDTO;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Autenticacion JWT (Manual de Integracion JWT del curso),
 * adaptado al modelo Usuario de la Unidad 2 (correo / clave).
 */
class AuthApiController extends Controller
{
    /** POST /api/auth/register -> 201 */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'clave'  => 'required|string|min:6',
        ]);

        // Nunca se guarda la clave en texto plano: se cifra con bcrypt.
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'clave'  => Hash::make($request->clave),
        ]);

        $response = new ApiResponseDTO(201, 'Usuario registrado correctamente', $usuario);

        return response()->json($response->toArray(), 201);
    }

    /** POST /api/auth/login -> 200 (token) | 401 */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'correo' => 'required|email',
            'clave'  => 'required|string',
        ]);

        // El provider busca por "correo" y compara "password" contra
        // Usuario::getAuthPassword(), que devuelve la columna clave.
        $credenciales = [
            'correo'   => $request->correo,
            'password' => $request->clave,
        ];

        $token = auth('api')->attempt($credenciales);

        if (! $token) {
            $response = new ApiResponseDTO(401, 'Credenciales incorrectas', null);

            return response()->json($response->toArray(), 401);
        }

        $data = [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ];

        $response = new ApiResponseDTO(200, 'Inicio de sesion correcto', $data);

        return response()->json($response->toArray(), 200);
    }

    /** GET /api/auth/me -> 200 (requiere JWT) */
    public function me(): JsonResponse
    {
        $response = new ApiResponseDTO(
            200,
            'Usuario autenticado obtenido correctamente',
            auth('api')->user()
        );

        return response()->json($response->toArray(), 200);
    }

    /** POST /api/auth/logout -> 200 (invalida el token) */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        $response = new ApiResponseDTO(200, 'Sesion cerrada correctamente', null);

        return response()->json($response->toArray(), 200);
    }

    /** POST /api/auth/refresh -> 200 (token renovado) */
    public function refresh(): JsonResponse
    {
        $data = [
            'access_token' => auth('api')->refresh(),
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ];

        $response = new ApiResponseDTO(200, 'Token renovado correctamente', $data);

        return response()->json($response->toArray(), 200);
    }
}
