<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Proyecto</title>
</head>
<body>
    <h1>Detalle del Proyecto</h1>

    <p><strong>Id:</strong> {{ $proyecto->id }}</p>
    <p><strong>Nombre:</strong> {{ $proyecto->nombre }}</p>
    <p><strong>Fecha de Inicio:</strong> {{ $proyecto->fecha_inicio->format('Y-m-d') }}</p>
    <p><strong>Estado:</strong> {{ $proyecto->estado }}</p>
    <p><strong>Responsable:</strong> {{ $proyecto->responsable }}</p>
    <p><strong>Monto:</strong> {{ number_format($proyecto->monto, 2, ',', '.') }}</p>
    <p><strong>Creado por (usuario):</strong> {{ $proyecto->created_by }}</p>

    <p>
        <a href="/proyectos">Volver al listado</a> |
        <a href="/proyectos/{{ $proyecto->id }}/edit">Editar</a>
    </p>
</body>
</html>
