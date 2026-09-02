<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="card">
        <h1>Iniciar sesión</h1>
        <p class="subtitulo">Tech Solutions — Gestión de Proyectos</p>

        @if (session('status'))
            <div class="alerta alerta-exito">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alerta alerta-error">
                <ul class="errores">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
            </div>

            <div class="campo">
                <label for="clave">Clave</label>
                <input type="password" id="clave" name="clave" required>
            </div>

            <label class="recordar">
                <input type="checkbox" name="recordar" value="1"> Recordarme
            </label>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <a class="enlace" href="/registro">¿No tienes cuenta? Regístrate</a>
    </div>
</body>
</html>
