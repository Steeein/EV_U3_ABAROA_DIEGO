<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * EVALUACION UNIDAD 3 - CRUD de Proyectos sobre la base de datos.
 *
 * Cada metodo devuelve explicitamente su codigo de respuesta HTTP,
 * que es el criterio de nivel Sobresaliente de la Rubrica_U3.
 */
class ProyectoApiController extends Controller
{
    /**
     * INDICADOR 2 - Recupera datos existentes (listado).
     * GET /api/proyectos  ->  200 OK
     * Si no hay registros devuelve un arreglo vacio [].
     */
    public function index(Request $request): JsonResponse
    {
        $query = Proyecto::query();

        // Filtros de busqueda dinamicos con Eloquent (Apunte 3, punto 4).
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->query('nombre') . '%');
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->query('estado'));
        }
        if ($request->filled('responsable')) {
            $query->where('responsable', 'like', '%' . $request->query('responsable') . '%');
        }

        $proyectos = $query->orderBy('id')->get();

        return response()->json($proyectos, Response::HTTP_OK); // 200
    }

    /**
     * INDICADOR 1 - Inserta nuevos registros.
     * POST /api/proyectos  ->  201 Created
     * Las validaciones se resuelven en StoreProyectoRequest (422 si fallan).
     */
    public function store(StoreProyectoRequest $request): JsonResponse
    {
        $proyecto = Proyecto::create($request->validated());

        // fresh() recarga desde la BD para devolver TODOS los campos.
        return response()->json($proyecto->fresh(), Response::HTTP_CREATED); // 201
    }

    /**
     * INDICADOR 2 - Recupera un registro por su Id.
     * GET /api/proyectos/{id}  ->  200 OK | 404 Not Found
     */
    public function show(string $id): JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return response()->json([
                'message' => 'Proyecto no encontrado.',
                'id'      => $id,
            ], Response::HTTP_NOT_FOUND); // 404
        }

        return response()->json($proyecto, Response::HTTP_OK); // 200
    }

    /**
     * INDICADOR 3 - Actualiza registros existentes.
     * PUT|PATCH /api/proyectos/{id}  ->  200 OK | 404 Not Found | 422
     * Devuelve el recurso completo con todos los campos actualizados.
     */
    public function update(UpdateProyectoRequest $request, string $id): JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return response()->json([
                'message' => 'Proyecto no encontrado.',
                'id'      => $id,
            ], Response::HTTP_NOT_FOUND); // 404
        }

        $proyecto->update($request->validated());

        return response()->json($proyecto->fresh(), Response::HTTP_OK); // 200
    }

    /**
     * INDICADOR 4 - Elimina registros de manera segura y eficiente.
     * DELETE /api/proyectos/{id}  ->  204 No Content | 404 Not Found
     * La respuesta debe ser vacia.
     */
    public function destroy(string $id): Response|JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return response()->json([
                'message' => 'Proyecto no encontrado.',
                'id'      => $id,
            ], Response::HTTP_NOT_FOUND); // 404
        }

        try {
            // Transaccion: garantiza atomicidad ("de manera segura").
            DB::transaction(function () use ($proyecto) {
                $proyecto->delete();
            });
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'No se pudo eliminar el proyecto.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }

        return response()->noContent(); // 204, cuerpo vacio
    }
}
