<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(RegistroRequest $request)
    {
        $datos = $request->validated();

        $usuario = Usuario::create([
            'nombre' => $datos['nombre'],
            'correo' => $datos['correo'],
            // Nunca se guarda la clave en texto plano: se cifra con bcrypt.
            'clave'  => Hash::make($datos['clave']),
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect('/inicio')
            ->with('status', "¡Registro exitoso! Bienvenido/a, {$usuario->nombre}.");
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $datos = $request->validated();

        // Auth::attempt busca al usuario por "correo" y compara "password"
        // contra la clave cifrada (ver Usuario::getAuthPassword).
        $credenciales = [
            'correo'   => $datos['correo'],
            'password' => $datos['clave'],
        ];

        if (! Auth::attempt($credenciales, $request->boolean('recordar'))) {
            return back()
                ->withInput($request->only('correo'))
                ->withErrors(['correo' => 'Las credenciales no son correctas.']);
        }

        $request->session()->regenerate();

        return redirect()->intended('/inicio')
            ->with('status', 'Inicio de sesión correcto.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Sesión cerrada correctamente.');
    }

    public function inicio()
    {
        return view('auth.inicio', ['usuario' => Auth::user()]);
    }
}
