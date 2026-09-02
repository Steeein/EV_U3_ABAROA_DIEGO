<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Inicio</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="card panel">
        <h1>¡Bienvenido/a!</h1>

        @if (session('status'))
            <div class="alerta alerta-exito">{{ session('status') }}</div>
        @endif

        <p class="subtitulo">Has iniciado sesión correctamente.</p>

        <div class="dato"><strong>Id:</strong> {{ $usuario->id }}</div>
        <div class="dato"><strong>Nombre:</strong> {{ $usuario->nombre }}</div>
        <div class="dato"><strong>Correo:</strong> {{ $usuario->correo }}</div>

        <p style="margin-top:20px;">
            <a class="enlace" href="/proyectos">Ir a la lista de proyectos</a>
        </p>

        <form method="POST" action="/logout" style="margin-top:12px;">
            @csrf
            <button type="submit" class="btn" style="background:#dc2626;">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
