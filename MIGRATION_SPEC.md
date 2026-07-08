# Documento de Especificación Técnica de Migración: Proyecto CERMOPA

Este documento detalla los requerimientos técnicos para migrar el sistema actual de Gestión de Activos (PHP/MySQL) a un nuevo stack tecnológico basado en Python (Flask), PostgreSQL y Frontend moderno (Vanilla JS).

---

### 1. Resumen Ejecutivo del Sistema

*   **Propósito Principal:** El sistema es una plataforma de gestión de inventario y control de activos fijos diseñada para la organización CERMOPA. Resuelve el problema de rastrear la ubicación física, el estado (nuevo, reparación, baja), el color y la cantidad de activos distribuidos en múltiples sedes (Barinas, Guanare, Apure).
*   **Arquitectura Actual:** Monolito híbrido en PHP 7.3. Utiliza una estructura semi-MVC con controladores en `controllers/`, modelos en `models/` y lógica procedimental en `php_action/`. El frontend está construido con Tailwind CSS y depende fuertemente de jQuery y DataTables para la interactividad y visualización de datos.
*   **Flujo de Datos:**
    1.  Autenticación de usuario vía sesión de servidor.
    2.  Consulta y filtrado de activos mediante una vista SQL (`vista_inventario`) que normaliza datos de múltiples tablas.
    3.  Operaciones CRUD enviadas vía AJAX/POST a scripts PHP que interactúan con la base de datos mediante PDO.
    4.  Visualización en tiempo real en el Dashboard mediante consultas de agregación.

---

### 2. Esquema de Base de Datos para PostgreSQL

Se debe implementar un modelo relacional en Tercera Forma Normal (3NF).

| Tabla | Columnas Principales | Tipo PostgreSQL | Restricciones |
| :--- | :--- | :--- | :--- |
| **users** | `user_id` (PK), `username`, `password`, `email` | SERIAL, VARCHAR, TEXT, VARCHAR | `username` UNIQUE, `NOT NULL` |
| **brands** (Sedes) | `brand_id` (PK), `brand_name`, `brand_active`, `brand_status` | SERIAL, VARCHAR, INT, INT | `brand_status` (1: Activo, 2: Borrado) |
| **categories** (Tipos) | `categories_id` (PK), `categories_name`, `categories_active`, `categories_status` | SERIAL, VARCHAR, INT, INT | |
| **colores** | `id_color` (PK), `nombre_color` | SERIAL, VARCHAR | `NOT NULL` |
| **ubicaciones** | `id_ubicacion` (PK), `nombre_ubicacion` | SERIAL, VARCHAR | `NOT NULL` |
| **estados** | `id_estado` (PK), `nombre_estado` | SERIAL, VARCHAR | Ej: 'Nuevo', 'Buen Estado', 'Reparación', 'Baja' |
| **articulos** | `id_articulo` (PK), `nombre_articulo`, `codigo_interno`, `id_marca` (FK), `id_categoria` (FK), `id_color` (FK), `product_image` | SERIAL, VARCHAR, VARCHAR, INT, INT, INT, TEXT | `codigo_interno` UNIQUE |
| **inventario** | `id_inventario` (PK), `id_articulo` (FK), `id_ubicacion` (FK), `id_estado` (FK), `cantidad`, `rate`, `activo`, `status` | SERIAL, INT, INT, INT, INT, VARCHAR, INT, INT | `status`=2 para borrado lógico |

**Relaciones:**
*   `articulos` M:1 `brands`, `categories`, `colores`.
*   `inventario` M:1 `articulos`, `ubicaciones`, `estados`.
*   `users` 1:M `orders` (si se habilita el módulo de salidas).

---

### 3. Mapeo de Rutas y Lógica de Negocio (Backend -> Flask)

| Ruta Flask | Método | Parámetros | Lógica de Negocio | Salida / Vista |
| :--- | :--- | :--- | :--- | :--- |
| `/` | GET | - | Verifica sesión; si existe, redirige a `/dashboard`. | `login.html` |
| `/login` | POST | `username`, `password` | Valida credenciales contra tabla `users`. Soporte `bcrypt`. | JSON {success: bool} |
| `/logout` | GET | - | Destruye la sesión del usuario. | Redirige a `/` |
| `/dashboard` | GET | - | Ejecuta `COUNT` y `GROUP BY` sobre `inventario` y `articulos`. | `dashboard.html` |
| `/api/articulos` | GET | filtros (sede, color) | Consulta `vista_inventario` con filtros dinámicos. | JSON (formato DataTables) |
| `/api/articulos` | POST | Form (datos activo + img) | Inserta en `articulos` e `inventario` (Transacción SQL). | JSON {success: bool} |
| `/api/articulos/<id>`| PUT | JSON | Actualiza datos del activo y su estado en el inventario. | JSON {success: bool} |
| `/api/articulos/<id>`| DELETE | - | Borrado lógico (status=2) en la tabla `inventario`. | JSON {success: bool} |
| `/api/catalogos` | GET | - | Retorna listas de sedes, categorías, colores, etc. | JSON {marcas: [], ...} |
| `/importar` | POST | File (CSV) | Procesa CSV (separador `;`), valida FKs e inserta/actualiza. | JSON {success: int, errors: []} |

---

### 4. Estructura de Interfaz y Vistas (Frontend -> HTML/CSS/JS)

**Organización de Archivos:**
*   `/templates`: `layout.html` (Base), `dashboard.html`, `articulos.html`, `sedes.html`, `categorias.html`, `login.html`.
*   `/static/css`: `styles.css` (Tailwind compilado o CDN).
*   `/static/js`: `auth.js`, `inventory.js`, `api.js` (Módulos Vanilla JS).

**Componentes Clave por Pantalla:**
*   **Inventario:** Tabla dinámica (DataTables.net), Modales de creación/edición, Preview de imagen.
*   **Cargador:** Zona de Drop (Drag & Drop API), Barra de progreso, Consola de errores de validación.
*   **Filtros:** Selects dinámicos poblados desde `/api/catalogos`.

**Interacciones JavaScript (Vanilla JS):**
*   Uso de `fetch()` para todas las comunicaciones con la API.
*   Manipulación del DOM para mostrar alertas (Toasts) y actualizar contadores en el Dashboard.
*   Reemplazo de los plugins de jQuery UI por elementos HTML5 nativos (modales `<dialog>` o clases CSS para overlays).

---

### 5. Dependencias, Configuraciones y Seguridad

*   **Variables de Entorno (.env):**
    *   `DATABASE_URL`: Conexión a PostgreSQL.
    *   `SECRET_KEY`: Para firma de sesiones Flask.
    *   `UPLOAD_FOLDER`: Ruta para imágenes de activos.
*   **Seguridad:**
    *   **Autenticación:** Flask-Login o sesiones nativas de Flask.
    *   **Contraseñas:** Migrar hashes MD5 legacy a `scrypt` o `bcrypt` (usando `Werkzeug.security`).
    *   **Protección:** Middleware para verificar sesión en rutas protegidas y protección CSRF.
*   **Equivalencias de Librerías:**
    *   PHP PDO -> SQLAlchemy o `psycopg2`.
    *   mPDF -> `WeasyPrint` o `ReportLab`.
    *   DataTables (jQuery) -> DataTables (Vanilla JS version) o `Grid.js`.
    *   PHP Sessions -> Flask Session (Client-side sessions con cookies seguras).
