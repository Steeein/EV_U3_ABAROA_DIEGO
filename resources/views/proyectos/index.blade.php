<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proyectos</title>
</head>
<body>

    <h1>Proyectos</h1>

    @if (session('status'))
        <p style="background:#dcfce7;color:#166534;padding:8px;border-radius:4px;">
            {{ session('status') }}
        </p>
    @endif

    <p><a href="/proyectos/create">Crear nuevo proyecto</a></p>

    @forelse ($proyectos as $proyecto)
        <div style="border:1px solid #ccc;padding:8px;margin-bottom:8px;">
            <p><strong>{{ $proyecto->nombre }}</strong> (Id: {{ $proyecto->id }})</p>
            <p>Inicio: {{ $proyecto->fecha_inicio->format('Y-m-d') }} &mdash; Estado: {{ $proyecto->estado }}</p>
            <p>Responsable: {{ $proyecto->responsable }} &mdash; Monto: {{ number_format($proyecto->monto, 2, ',', '.') }}</p>
            <p>Creado por (usuario): {{ $proyecto->created_by }}</p>
            <p>
                <a href="/proyectos/{{ $proyecto->id }}">Ver</a> |
                <a href="/proyectos/{{ $proyecto->id }}/edit">Editar</a>
            </p>
        </div>
    @empty
        <p>No hay proyectos registrados.</p>
    @endforelse

</body>
</html>
