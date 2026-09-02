<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proyecto</title>
</head>
<body>

    <h1>Crear Proyecto</h1>

    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:8px;border-radius:4px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/proyectos" method="POST">
        @csrf
        <p>
            <label>Nombre:<br>
            <input type="text" name="nombre" value="{{ old('nombre') }}"></label>
        </p>
        <p>
            <label>Fecha de Inicio:<br>
            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"></label>
        </p>
        <p>
            <label>Estado:<br>
            <select name="estado">
                <option value="">-- Seleccione --</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}" @selected(old('estado') === $estado)>{{ $estado }}</option>
                @endforeach
            </select></label>
        </p>
        <p>
            <label>Responsable:<br>
            <input type="text" name="responsable" value="{{ old('responsable') }}"></label>
        </p>
        <p>
            <label>Monto:<br>
            <input type="number" step="0.01" min="0" name="monto" value="{{ old('monto') }}"></label>
        </p>
        <p>
            <label>Creado por (Id usuario):<br>
            <input type="number" min="1" name="created_by" value="{{ old('created_by', auth()->id() ?? 1) }}"></label>
        </p>
        <p>
            <button type="submit">Crear</button>
            <a href="/proyectos">Cancelar</a>
        </p>
    </form>

</body>
</html>
