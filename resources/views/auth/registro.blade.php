<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="card">
        <h1>Crear cuenta</h1>
        <p class="subtitulo">Tech Solutions — Gestión de Proyectos</p>

        @if ($errors->any())
            <div class="alerta alerta-error">
                <ul class="errores">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/registro">
            @csrf

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
            </div>

            <div class="campo">
                <label for="clave">Clave</label>
                <input type="password" id="clave" name="clave" required>
            </div>

            <div class="campo">
                <label for="clave_confirmation">Confirmar clave</label>
                <input type="password" id="clave_confirmation" name="clave_confirmation" required>
            </div>

            <button type="submit" class="btn">Registrarme</button>
        </form>

        <a class="enlace" href="/login">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</body>
</html>
