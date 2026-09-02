<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto</title>
</head>
<body>

    <h1>Editar Proyecto</h1>

    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:8px;border-radius:4px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/proyectos/{{ $proyecto->id }}" method="POST">
        @csrf
        @method('PUT')
        <p>
            <label>Nombre:<br>
            <input type="text" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}"></label>
        </p>
        <p>
            <label>Fecha de Inicio:<br>
            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio->format('Y-m-d')) }}"></label>
        </p>
        <p>
            <label>Estado:<br>
            <select name="estado">
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $proyecto->estado) === $estado)>{{ $estado }}</option>
                @endforeach
            </select></label>
        </p>
        <p>
            <label>Responsable:<br>
            <input type="text" name="responsable" value="{{ old('responsable', $proyecto->responsable) }}"></label>
        </p>
        <p>
            <label>Monto:<br>
            <input type="number" step="0.01" min="0" name="monto" value="{{ old('monto', $proyecto->monto) }}"></label>
        </p>
        <p>
            <button type="submit">Guardar</button>
            <a href="/proyectos">Cancelar</a>
        </p>
    </form>

    <form action="/proyectos/{{ $proyecto->id }}" method="POST" style="margin-top:1rem;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Eliminar proyecto?')">Eliminar</button>
    </form>

</body>
</html>
