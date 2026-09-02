<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Panel de pruebas de la API (Unidad 3)
|--------------------------------------------------------------------------
| Interfaz que consume la API con JWT y muestra en pantalla el codigo
| de respuesta HTTP de cada operacion del CRUD.
*/
Route::view('/panel', 'api.panel')->name('panel');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación (Unidad 2)
|--------------------------------------------------------------------------
*/

// Registro de usuario
Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registrar']);

// Inicio de sesión
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Panel protegido: solo usuarios autenticados (middleware auth)
Route::get('/inicio', [AuthController::class, 'inicio'])
    ->middleware('auth')
    ->name('inicio');



Route::get('/proyectos', [ProyectoController::class, 'listarTodos']);

// Formulario para crear un nuevo proyecto
Route::get('/proyectos/create', [ProyectoController::class, 'crear']);

// Formulario para editar un proyecto
Route::get('/proyectos/{id}/edit', [ProyectoController::class, 'editar']);

Route::get('/proyectos/{id}', [ProyectoController::class, 'obtenerPorId']);

Route::post('/proyectos', [ProyectoController::class, 'agregar']);

Route::put('/proyectos/{id}', [ProyectoController::class, 'actualizarPorId']);

Route::delete('/proyectos/{id}', [ProyectoController::class, 'eliminarPorId']);