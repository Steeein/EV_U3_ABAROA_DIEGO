<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel API JWT - Tech Solutions</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f2f4f8; color: #1b2733; line-height: 1.5; padding: 24px; }
        .contenedor { max-width: 1100px; margin: 0 auto; }
        header { background: #16324f; color: #fff; padding: 20px 24px; border-radius: 8px; margin-bottom: 20px; }
        header h1 { font-size: 20px; }
        header p { font-size: 13px; opacity: .85; margin-top: 4px; }
        .tarjeta { background: #fff; border: 1px solid #dde3ec; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .tarjeta h2 { font-size: 16px; margin-bottom: 14px; border-bottom: 2px solid #c8a55b; padding-bottom: 8px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .campo { display: flex; flex-direction: column; }
        label { font-size: 12px; font-weight: 600; margin-bottom: 4px; }
        input, select { padding: 8px 10px; border: 1px solid #c4cdda; border-radius: 5px; font-size: 14px; font-family: inherit; }
        .acciones { margin-top: 14px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        button { padding: 9px 16px; border: 0; border-radius: 5px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; }
        .btn-primario { background: #16324f; color: #fff; }
        .btn-secundario { background: #64748b; color: #fff; }
        .btn-editar { background: #c8a55b; color: #1b2733; }
        .btn-eliminar { background: #b3261e; color: #fff; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 9px 10px; border-bottom: 1px solid #e6eaf0; text-align: left; }
        th { background: #eef2f7; font-size: 12px; text-transform: uppercase; }
        .consola { background: #0f172a; color: #e2e8f0; border-radius: 8px; padding: 16px; font-family: Consolas, monospace; font-size: 13px; white-space: pre-wrap; word-break: break-word; max-height: 320px; overflow-y: auto; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-weight: 700; font-size: 12px; margin-bottom: 8px; }
        .badge-ok { background: #dcfce7; color: #166534; }
        .badge-error { background: #fee2e2; color: #991b1b; }
        .vacio { text-align: center; color: #64748b; padding: 20px; }
        .estado-token { font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 12px; }
        .sin-token { background: #fee2e2; color: #991b1b; }
        .con-token { background: #dcfce7; color: #166534; }
        .token-texto { font-family: Consolas, monospace; font-size: 11px; color: #64748b; word-break: break-all; margin-top: 8px; }
        @media (max-width: 760px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="contenedor">

    <header>
        <h1>Tech Solutions &mdash; Panel API con autenticacion JWT</h1>
        <p>Evaluacion Unidad 3 &middot; CRUD con Laravel 12, Eloquent ORM y JWT</p>
    </header>

    <div class="tarjeta">
        <h2>1. Autenticacion JWT</h2>
        <div class="grid">
            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" value="admin@techsolutions.cl">
            </div>
            <div class="campo">
                <label for="clave">Clave</label>
                <input type="password" id="clave" value="desarrollo_software_1">
            </div>
        </div>
        <div class="acciones">
            <button type="button" class="btn-primario" id="btnLogin">Iniciar sesion (POST /auth/login)</button>
            <button type="button" class="btn-secundario" id="btnMe">Ver usuario (GET /auth/me)</button>
            <button type="button" class="btn-eliminar" id="btnLogout">Cerrar sesion (POST /auth/logout)</button>
            <span class="estado-token sin-token" id="estadoToken">SIN TOKEN</span>
        </div>
        <div class="token-texto" id="tokenTexto"></div>
    </div>

    <div class="tarjeta">
        <h2 id="tituloFormulario">2. Agregar Proyecto (POST &rarr; 201 Created)</h2>
        <form id="formProyecto">
            <input type="hidden" id="proyectoId">
            <div class="grid">
                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" placeholder="Ej: Migracion ERP">
                </div>
                <div class="campo">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" id="fecha_inicio">
                </div>
                <div class="campo">
                    <label for="estado">Estado</label>
                    <select id="estado">
                        <option value="">-- Seleccione --</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="campo">
                    <label for="responsable">Responsable</label>
                    <input type="text" id="responsable" placeholder="Ej: Camila Rojas">
                </div>
                <div class="campo">
                    <label for="monto">Monto (CLP)</label>
                    <input type="number" id="monto" step="0.01" min="0" placeholder="Ej: 1500000">
                </div>
                <div class="campo">
                    <label for="created_by">Creado por (Id usuario)</label>
                    <input type="number" id="created_by" value="1" min="1">
                </div>
            </div>
            <div class="acciones">
                <button type="submit" class="btn-primario">Guardar Proyecto</button>
                <button type="button" class="btn-secundario" id="btnCancelar">Cancelar edicion</button>
            </div>
        </form>
    </div>

    <div class="tarjeta">
        <h2>3. Listado de Proyectos (GET &rarr; 200 OK)</h2>
        <div class="acciones" style="margin-bottom:14px;">
            <input type="text" id="filtroNombre" placeholder="Filtrar por nombre...">
            <input type="text" id="filtroResponsable" placeholder="Filtrar por responsable...">
            <button type="button" class="btn-primario" id="btnListar">Buscar</button>
            <button type="button" class="btn-secundario" id="btnLimpiar">Limpiar</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Fecha Inicio</th><th>Estado</th>
                    <th>Responsable</th><th>Monto</th><th>Creado por</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaProyectos"></tbody>
        </table>
    </div>

    <div class="tarjeta">
        <h2>4. Consola de respuestas HTTP</h2>
        <div id="badgeEstado"></div>
        <div class="consola" id="consola">Esperando una peticion...</div>
    </div>

</div>

<script>
const API = '{{ url('/api') }}';
const $ = id => document.getElementById(id);

/** El token se guarda solo en este navegador (localStorage). */
function obtenerToken() {
    try { return localStorage.getItem('jwt_token') || ''; } catch { return ''; }
}
function guardarToken(token) {
    try { token ? localStorage.setItem('jwt_token', token) : localStorage.removeItem('jwt_token'); } catch {}
    pintarEstadoToken();
}
function pintarEstadoToken() {
    const token = obtenerToken();
    const el = $('estadoToken');
    el.textContent = token ? 'TOKEN ACTIVO' : 'SIN TOKEN';
    el.className = 'estado-token ' + (token ? 'con-token' : 'sin-token');
    $('tokenTexto').textContent = token ? 'Bearer ' + token.substring(0, 60) + '...' : '';
}

/** Muestra en pantalla el codigo HTTP y el cuerpo de cada respuesta. */
function registrar(metodo, url, status, statusText, cuerpo) {
    const ok = status >= 200 && status < 300;
    $('badgeEstado').innerHTML =
        '<span class="badge ' + (ok ? 'badge-ok' : 'badge-error') + '">HTTP ' + status + ' ' + statusText + '</span>';
    $('consola').textContent =
        metodo + ' ' + url + '\nStatus: ' + status + ' ' + statusText + '\n\n' +
        (cuerpo === null || cuerpo === '' ? '(cuerpo vacio)' : JSON.stringify(cuerpo, null, 2));
}

async function peticion(metodo, url, datos = null, conToken = true) {
    const opciones = { method: metodo, headers: { 'Accept': 'application/json' } };
    if (conToken && obtenerToken()) {
        opciones.headers['Authorization'] = 'Bearer ' + obtenerToken();
    }
    if (datos !== null) {
        opciones.headers['Content-Type'] = 'application/json';
        opciones.body = JSON.stringify(datos);
    }

    const respuesta = await fetch(url, opciones);

    // 204 No Content no tiene cuerpo que parsear.
    let cuerpo = null;
    if (respuesta.status !== 204) {
        const texto = await respuesta.text();
        try { cuerpo = texto ? JSON.parse(texto) : null; } catch { cuerpo = texto; }
    }

    registrar(metodo, url, respuesta.status, respuesta.statusText, cuerpo);
    return { status: respuesta.status, cuerpo };
}

/* ------------------------- Autenticacion ------------------------- */

$('btnLogin').addEventListener('click', async () => {
    const { status, cuerpo } = await peticion('POST', API + '/auth/login', {
        correo: $('correo').value,
        clave: $('clave').value,
    }, false);

    if (status === 200 && cuerpo.data && cuerpo.data.access_token) {
        guardarToken(cuerpo.data.access_token);
        await listarProyectos();
    } else {
        guardarToken('');
    }
});

$('btnMe').addEventListener('click', () => peticion('GET', API + '/auth/me'));

$('btnLogout').addEventListener('click', async () => {
    await peticion('POST', API + '/auth/logout');
    guardarToken('');
    $('tablaProyectos').innerHTML = '<tr><td colspan="8" class="vacio">Sesion cerrada. Inicie sesion para continuar.</td></tr>';
});

/* ---------------------------- Proyectos ---------------------------- */

function formatearMonto(valor) {
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 2 }).format(valor);
}

async function listarProyectos() {
    const params = new URLSearchParams();
    if ($('filtroNombre').value.trim())      params.append('nombre', $('filtroNombre').value.trim());
    if ($('filtroResponsable').value.trim()) params.append('responsable', $('filtroResponsable').value.trim());

    const url = params.toString() ? API + '/proyectos?' + params : API + '/proyectos';
    const { status, cuerpo } = await peticion('GET', url);

    const tbody = $('tablaProyectos');
    tbody.innerHTML = '';

    if (status === 401) {
        tbody.innerHTML = '<tr><td colspan="8" class="vacio">401 No autorizado. Debe iniciar sesion para obtener un token.</td></tr>';
        return;
    }
    if (!Array.isArray(cuerpo) || cuerpo.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="vacio">No hay proyectos registrados (arreglo vacio).</td></tr>';
        return;
    }

    cuerpo.forEach(p => {
        const fila = document.createElement('tr');
        fila.innerHTML =
            '<td>' + p.id + '</td>' +
            '<td>' + p.nombre + '</td>' +
            '<td>' + p.fecha_inicio + '</td>' +
            '<td>' + p.estado + '</td>' +
            '<td>' + p.responsable + '</td>' +
            '<td>' + formatearMonto(p.monto) + '</td>' +
            '<td>' + p.created_by + '</td>' +
            '<td>' +
                '<button class="btn-secundario" data-ver="' + p.id + '">Ver</button> ' +
                '<button class="btn-editar" data-editar="' + p.id + '">Editar</button> ' +
                '<button class="btn-eliminar" data-eliminar="' + p.id + '">Eliminar</button>' +
            '</td>';
        tbody.appendChild(fila);
    });
}

$('formProyecto').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    const datos = {
        nombre:       $('nombre').value,
        fecha_inicio: $('fecha_inicio').value,
        estado:       $('estado').value,
        responsable:  $('responsable').value,
        monto:        $('monto').value,
        created_by:   $('created_by').value,
    };

    const id = $('proyectoId').value;
    const { status } = id
        ? await peticion('PUT', API + '/proyectos/' + id, datos)
        : await peticion('POST', API + '/proyectos', datos);

    if (status === 201 || status === 200) {
        reiniciarFormulario();
        await listarProyectos();
    }
});

$('tablaProyectos').addEventListener('click', async (evento) => {
    const boton = evento.target;

    if (boton.dataset.ver) {
        await peticion('GET', API + '/proyectos/' + boton.dataset.ver);
    }

    if (boton.dataset.editar) {
        const { status, cuerpo } = await peticion('GET', API + '/proyectos/' + boton.dataset.editar);
        if (status === 200) cargarEnFormulario(cuerpo);
    }

    if (boton.dataset.eliminar) {
        if (!confirm('Eliminar el proyecto #' + boton.dataset.eliminar + '?')) return;
        const { status } = await peticion('DELETE', API + '/proyectos/' + boton.dataset.eliminar);
        if (status === 204) await listarProyectos();
    }
});

function cargarEnFormulario(p) {
    $('proyectoId').value   = p.id;
    $('nombre').value       = p.nombre;
    $('fecha_inicio').value = p.fecha_inicio;
    $('estado').value       = p.estado;
    $('responsable').value  = p.responsable;
    $('monto').value        = p.monto;
    $('created_by').value   = p.created_by;
    $('tituloFormulario').textContent = '2. Actualizar Proyecto #' + p.id + ' (PUT -> 200 OK)';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function reiniciarFormulario() {
    $('formProyecto').reset();
    $('proyectoId').value = '';
    $('created_by').value = 1;
    $('tituloFormulario').textContent = '2. Agregar Proyecto (POST -> 201 Created)';
}

$('btnCancelar').addEventListener('click', reiniciarFormulario);
$('btnListar').addEventListener('click', listarProyectos);
$('btnLimpiar').addEventListener('click', () => {
    $('filtroNombre').value = '';
    $('filtroResponsable').value = '';
    listarProyectos();
});

pintarEstadoToken();
listarProyectos();
</script>
</body>
</html>
