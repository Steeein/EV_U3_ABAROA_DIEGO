<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CRUD web (vistas Blade) de las Unidades 1 y 2.
 * Unidad 3: las operaciones dejan de ser simuladas y persisten
 * realmente en la base de datos a traves de Eloquent.
 */
class ProyectoController extends Controller
{
    /** GET /proyectos */
    public function listarTodos(): View
    {
        return view('proyectos.index', [
            'proyectos' => Proyecto::orderBy('id')->get(),
        ]);
    }

    /** GET /proyectos/{id} */
    public function obtenerPorId(string $id): View|RedirectResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return redirect('/proyectos')->with('status', "El proyecto {$id} no existe.");
        }

        return view('proyectos.show', ['proyecto' => $proyecto]);
    }

    /** GET /proyectos/create */
    public function crear(): View
    {
        return view('proyectos.create', ['estados' => Proyecto::ESTADOS]);
    }

    /** GET /proyectos/{id}/edit */
    public function editar(string $id): View|RedirectResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return redirect('/proyectos')->with('status', "El proyecto {$id} no existe.");
        }

        return view('proyectos.edit', [
            'proyecto' => $proyecto,
            'estados'  => Proyecto::ESTADOS,
        ]);
    }

    /** POST /proyectos */
    public function agregar(StoreProyectoRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        // Si no viene created_by, se usa el usuario autenticado por sesion.
        $datos['created_by'] = $datos['created_by'] ?? Auth::id();

        $proyecto = Proyecto::create($datos);

        return redirect('/proyectos')
            ->with('status', "Proyecto '{$proyecto->nombre}' creado correctamente.");
    }

    /** PUT /proyectos/{id} */
    public function actualizarPorId(UpdateProyectoRequest $request, string $id): RedirectResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return redirect('/proyectos')->with('status', "El proyecto {$id} no existe.");
        }

        $proyecto->update($request->validated());

        return redirect('/proyectos')
            ->with('status', "Proyecto {$id} actualizado correctamente.");
    }

    /** DELETE /proyectos/{id} */
    public function eliminarPorId(string $id): RedirectResponse
    {
        $proyecto = Proyecto::find($id);

        if ($proyecto === null) {
            return redirect('/proyectos')->with('status', "El proyecto {$id} no existe.");
        }

        $proyecto->delete();

        return redirect('/proyectos')
            ->with('status', "Proyecto {$id} eliminado correctamente.");
    }
}
