# EV_U3 — Software de Gestión de Proyectos (Tech Solutions)

API REST con operaciones CRUD sobre base de datos, desarrollada con **Laravel 12** y **Eloquent ORM**,
con autenticación mediante **JWT**.

**Asignatura:** Desarrollo de Software Web I — IF204IINF
**Evaluación:** Sumativa Unidad 3 — Implementa operaciones CRUD para interactuar con la base de datos
**Estudiante:** Diego Abaroa Badilla
**Docente:** _(completar)_

---

## Stack

| Componente | Versión |
|---|---|
| PHP | 8.2 |
| Laravel | 12 |
| Base de datos | MySQL 8 |
| ORM | Eloquent |
| Autenticación | `php-open-source-saver/jwt-auth` 2.8 |

---

## Requisitos previos

- PHP 8.2 o superior
- Composer
- MySQL 8 (o Laragon / XAMPP)

---

## Cómo levantar el proyecto

### 1. Clonar el repositorio

```bash
git clone <URL-DEL-REPOSITORIO>
```

```bash
cd EV_U3_ABAROA_DIEGO
```

### 2. Instalar dependencias

```bash
composer install
```

> La carpeta `vendor/` no se versiona; este comando la reconstruye.

### 3. Crear el archivo de entorno

```bash
cp .env.example .env
```

En Windows (PowerShell):

```bash
Copy-Item .env.example .env
```

### 4. Generar las claves de la aplicación

```bash
php artisan key:generate
```

```bash
php artisan jwt:secret
```

### 5. Crear la base de datos

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS desarrollo_software_1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Verifique que `.env` contenga estas credenciales (son las definidas en la Evaluación U2):

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desarrollo_software_1
DB_USERNAME=root
DB_PASSWORD=desarrollo_software_1
```

### 6. Ejecutar migraciones y datos de prueba

```bash
php artisan migrate:fresh --seed
```

Esto crea las tablas `usuarios` y `proyectos`, un usuario administrador y tres proyectos de ejemplo.

### 7. Levantar el servidor

```bash
php artisan serve
```

| Recurso | URL |
|---|---|
| API | `http://127.0.0.1:8000/api` |
| Panel visual de pruebas | `http://127.0.0.1:8000/panel` |
| CRUD web (Blade) | `http://127.0.0.1:8000/proyectos` |

---

## ⚠️ Cómo probar la API — la API está protegida con JWT

Todos los endpoints del CRUD requieren un token. **Paso 1 obligatorio:**

```
POST http://127.0.0.1:8000/api/auth/login
Content-Type: application/json

{
    "correo": "admin@techsolutions.cl",
    "clave": "desarrollo_software_1"
}
```

La respuesta contiene `data.access_token`. Envíelo en todas las demás peticiones:

```
Authorization: Bearer <access_token>
```

> **Atajo recomendado:** importe `docs/EV_U3.postman_collection.json` en Postman,
> ejecute el request **"00 - Login"** y el token se guarda automáticamente para el resto
> de la colección. Luego puede ejecutar cualquier petición sin configurar nada más.

**Alternativa sin Postman:** abra `http://127.0.0.1:8000/panel`, pulse *Iniciar sesión* y
opere el CRUD desde la interfaz. El panel muestra en pantalla el código HTTP de cada respuesta.

---

## Endpoints del CRUD (Evaluación U3)

| Método | Ruta | Éxito | Errores |
|---|---|---|---|
| `POST` | `/api/proyectos` | **201 Created** | 422 validación · 401 sin token |
| `GET` | `/api/proyectos` | **200 OK** (`[]` si no hay datos) | 401 sin token |
| `GET` | `/api/proyectos/{id}` | **200 OK** | **404** si no existe · 401 |
| `PUT` \| `PATCH` | `/api/proyectos/{id}` | **200 OK** | **404** si no existe · 422 · 401 |
| `DELETE` | `/api/proyectos/{id}` | **204 No Content** (cuerpo vacío) | **404** si no existe · 401 |

El listado admite filtros dinámicos opcionales: `?nombre=`, `?estado=`, `?responsable=`.

### Endpoints de autenticación

| Método | Ruta | Protección | Éxito |
|---|---|---|---|
| `POST` | `/api/auth/register` | Pública | 201 · 422 |
| `POST` | `/api/auth/login` | Pública | 200 (token) · 401 |
| `GET` | `/api/auth/me` | JWT | 200 · 401 |
| `POST` | `/api/auth/logout` | JWT | 200 · 401 |
| `POST` | `/api/auth/refresh` | JWT | 200 · 401 |

---

## Ejemplo de uso

**Crear un proyecto (201 Created):**

```bash
curl -i -X POST http://127.0.0.1:8000/api/proyectos -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"nombre\":\"Nuevo CRM\",\"fecha_inicio\":\"2026-09-01\",\"estado\":\"Pendiente\",\"responsable\":\"Diego\",\"monto\":9500000,\"created_by\":1}"
```

Respuesta:

```json
{
    "id": 4,
    "nombre": "Nuevo CRM",
    "fecha_inicio": "2026-09-01",
    "estado": "Pendiente",
    "responsable": "Diego",
    "monto": "9500000.00",
    "created_by": 1,
    "created_at": "2026-09-02T00:40:49.000000Z",
    "updated_at": "2026-09-02T00:40:49.000000Z"
}
```

**Eliminar un proyecto (204 No Content, cuerpo vacío):**

```bash
curl -i -X DELETE http://127.0.0.1:8000/api/proyectos/3 -H "Authorization: Bearer TOKEN" -H "Accept: application/json"
```

---

## Modelo de datos

**Tabla `proyectos`** (campos definidos en la Evaluación U2):

| Campo | Tipo | Obligatorio |
|---|---|---|
| `id` | bigint PK | auto |
| `nombre` | varchar(255) | sí |
| `fecha_inicio` | date | sí |
| `estado` | varchar(50) | sí — `Pendiente`, `En Proceso`, `Finalizado`, `Cancelado` |
| `responsable` | varchar(255) | sí |
| `monto` | decimal(12,2) | sí |
| `created_by` | FK → `usuarios.id` | sí |

**Tabla `usuarios`** (heredada de la U2): `id`, `nombre`, `correo` (único), `clave` (cifrada con bcrypt).

Relación: `Proyecto belongsTo Usuario` · `Usuario hasMany Proyecto`.

---

## Estructura del proyecto

```
app/
├── DTOs/
│   └── ApiResponseDTO.php              Respuesta estándar {code, message, data}
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── ProyectoApiController.php   ← CRUD de la U3 (los 5 métodos)
│   │   │   └── AuthApiController.php       ← Autenticación JWT
│   │   ├── ProyectoController.php          CRUD web (vistas Blade)
│   │   └── AuthController.php              Login/registro web (U2)
│   └── Requests/
│       ├── StoreProyectoRequest.php        Validaciones POST → 422
│       └── UpdateProyectoRequest.php       Validaciones PUT/PATCH → 422
└── Models/
    ├── Proyecto.php                        Modelo Eloquent + relación
    └── Usuario.php                         Modelo de usuario + JWTSubject

database/
├── migrations/
│   ├── ..._create_usuarios_table.php
│   └── ..._create_proyectos_table.php
└── seeders/
    └── ProyectoSeeder.php

routes/
├── api.php                                 Rutas públicas y protegidas con auth:api
└── web.php                                 Rutas web (U1/U2) + /panel

resources/views/
├── api/panel.blade.php                     Panel visual de pruebas de la API
├── proyectos/                              Vistas CRUD (U1)
└── auth/                                   Login y registro (U2)

docs/
├── EV_U3.postman_collection.json           Colección de pruebas
└── capturas/                               Evidencia de los códigos HTTP
```

---

## Cobertura de los indicadores de la rúbrica

| Indicador | Implementación | Código HTTP |
|---|---|---|
| **Inserta nuevos registros** | `ProyectoApiController::store()` + `StoreProyectoRequest` | 201 · 422 |
| **Recupera datos existentes** | `ProyectoApiController::index()` y `show()` | 200 · 404 |
| **Actualiza registros** | `ProyectoApiController::update()` + `UpdateProyectoRequest` | 200 · 404 · 422 |
| **Elimina registros** | `ProyectoApiController::destroy()` (con transacción) | 204 · 404 |

Cada método declara su código de respuesta de forma explícita mediante las constantes
`Response::HTTP_*`, y las validaciones están delegadas a clases Form Request
(Apunte 3, sección 3.2).

---

## Decisiones de diseño documentadas

**1. El método de actualización devuelve 200, no 201.**
El enunciado de la evaluación se contradice: el encabezado del requisito indica
*"con una respuesta HTTP de 201"* y su sub-viñeta indica *"El codigo de respuesta debe ser 200"*.
Se implementó **200 OK** porque es la instrucción específica de la sub-viñeta, es la convención
REST correcta (201 se reserva para creación de recursos) y coincide con lo expuesto en el
Apunte 3, sección 1.3.2.

**2. Los endpoints GET devuelven el recurso en la raíz del JSON, sin envoltorio.**
El requisito exige que el listado retorne *"un arreglo vacío"* cuando no hay registros.
Envolver la respuesta en un objeto `{code, message, data}` impediría cumplirlo literalmente,
por lo que ese formato se reservó para los endpoints de autenticación (`ApiResponseDTO`),
tal como los presenta el Manual de Integración JWT del curso.

**3. `created_by` es obligatorio en la creación.**
El enunciado indica que *"todos los campos son requeridos y no deben estar vacíos"*, y
`created_by` es parte del modelo Proyecto definido en la U2. Si la petición se hace con un
token válido y no se envía el campo, se completa automáticamente con el id del usuario
autenticado (`StoreProyectoRequest::prepareForValidation`).

**4. Protección de la API con JWT.**
Se aplicó el middleware `auth:api` a todas las rutas del CRUD siguiendo el Manual de
Integración JWT del curso. Las credenciales de prueba y la colección de Postman
autoconfigurada permiten verificar todos los endpoints sin fricción.

---

## Credenciales de prueba

| Usuario | Correo | Clave |
|---|---|---|
| Administrador Tech Solutions | `admin@techsolutions.cl` | `desarrollo_software_1` |

---

## Notas

- El archivo `.env` no se versiona (contiene claves). Use `.env.example` como plantilla.
- La carpeta `vendor/` no se versiona; se reconstruye con `composer install`.
- Las rutas y vistas web de las Unidades 1 y 2 (`/proyectos`, `/login`, `/registro`, `/inicio`)
  se conservan operativas y ahora persisten realmente en la base de datos.
