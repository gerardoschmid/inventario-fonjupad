# Reporte de Auditoría Técnica: Inventario Fonjupad

## 📊 1. Resumen Ejecutivo
El sistema "Inventario Fonjupad" se encuentra en un estado de **transición arquitectónica**. Existe un esfuerzo evidente por modernizar la interfaz (migración exitosa a Tailwind CSS y componentes visuales modernos) y por adoptar mejores prácticas de backend (uso de PDO y controladores en módulos nuevos). Sin embargo, el núcleo del sistema aún arrastra una deuda técnica significativa proveniente de una base de código procedural y monolítica.

**Nivel de Mantenibilidad Actual: 4/10**
- **Fortaleza:** Interfaz de usuario moderna y responsiva.
- **Debilidad Crítica:** Vulnerabilidades graves de seguridad (SQL Injection) en módulos críticos como el inicio de sesión y la coexistencia de múltiples patrones de acceso a datos que complican la escalabilidad.

---

## 📁 2. Análisis de Estructura y Organización
- **Fortalezas encontradas:**
    - Separación de activos estáticos en la carpeta `assests/` (sic).
    - Intento de implementación de MVC con carpetas `controllers/` y `models/`.
    - Centralización de UI común en `includes/header.php` y `footer.php`.
- **Puntos críticos/Debilidades:**
    - **Inconsistencia de Archivos:** Coexisten archivos de lógica en la raíz (`brand.php`, `product.php`) con scripts de procesamiento en `php_action/` y controladores en `controllers/`.
    - **Redundancia de Configuración:** Existen tres archivos para la conexión a la base de datos (`db_connect.php`, `db_connect_pdo.php`, `config/database.php`), cada uno usando bibliotecas distintas (mysqli vs PDO).
    - **Duplicidad de Vistas:** La carpeta `views/layout/` contiene archivos que parecen duplicar la función de `includes/`.
- **Recomendaciones de reestructuración:**
    1. Unificar la lógica de acceso a datos bajo un solo proveedor (PDO).
    2. Mover todos los archivos de la raíz que contienen lógica de negocio a un esquema de rutas o controladores centralizados.
    3. Eliminar la carpeta `assests/` (corregir a `assets/`) y centralizar bibliotecas de terceros.

---

## 🏗️ 3. Evaluación Arquitectónica y Flujo de Datos
- **Mapeo del Flujo:**
    1. **Frontend:** El usuario interactúa con vistas PHP que utilizan Tailwind CSS.
    2. **Intercepción:** Las acciones (formularios/clicks) son capturadas por scripts en `custom/js/` (ej. `product.js`).
    3. **Transporte:** Se realizan peticiones AJAX hacia `php_action/*.php`.
    4. **Procesamiento:** El script en `php_action` valida la sesión, conecta a la DB y ejecuta la consulta (a veces llamando a controladores, a veces directamente).
    5. **Respuesta:** Se retorna un JSON que el JS procesa para actualizar la UI sin recargar la página.
- **Deficiencias de Diseño:**
    - **Acoplamiento Fuerte:** Muchos scripts de `php_action` dependen de variables globales y estados de sesión de forma directa en lugar de usar inyección de dependencias.
    - **Arquitectura Híbrida:** El sistema opera como un "Monolito Distribuido" donde la lógica está fragmentada entre scripts procedurales y controladores modernos, lo que genera confusión al extender funcionalidades.

---

## 🍝 4. Hallazgos de Código Espagueti y Deuda Técnica
- **Archivos/Funciones específicas con mayor deuda:**
    - `index.php` (Líneas 10-60): Lógica de autenticación mezclada con marcado HTML y consultas SQL directas.
    - `php_action/save_activo.php`: Una función monolítica que maneja tanto la creación como la edición, con una estructura de control `if/else` masiva y poco escalable.
    - `importar_activos.php`: Mezcla lógica de lectura de archivos, validación de negocio e inserción en base de datos en el mismo archivo de presentación.
- **Patrones de anti-diseño detectados:**
    - **God Files:** Scripts que manejan entrada, validación, persistencia y salida.
    - **Copy-Paste Programming:** Inconsistencias en nombres de variables entre archivos JS y PHP (ej. `productStatus` vs `activo`).
    - **Falta de Normalización:** Uso de `md5` para contraseñas, una práctica obsoleta y vulnerable.

---

## 🐛 5. Registro de Bugs Potenciales, Vulnerabilidades y Código Muerto

| Archivo/Componente | Tipo | Descripción del Riesgo | Severidad |
| :--- | :--- | :--- | :--- |
| `index.php` | Seguridad | **SQL Injection Crítico** en el login. Las variables `$_POST['username']` se pasan directo a la query. | Alta |
| `setting.php` | Seguridad | **SQL Injection** vía `$user_id` de la sesión en consulta `mysqli`. | Media |
| `views/articulos/manteleria.php` | Seguridad | **XSS (Cross-Site Scripting)**. Se imprimen variables `$_GET` y datos de DB sin sanitizar (`<?=`). | Alta |
| `importar_barinas.php` | Código Muerto | Script de importación específico que no está enlazado al flujo principal. | Baja |
| `test_import.php` | Código Muerto | Script de prueba con credenciales/rutas hardcoded expuestas. | Media |
| `database/store.sql` | Seguridad | Contiene credenciales de usuario admin con hash md5 predecible. | Alta |
| `core.php` vs `php_action/core.php` | Deuda Técnica | Dos archivos con el mismo nombre y propósito, causando confusión en las inclusiones. | Media |

---

## 🚀 6. Plan de Acción Recomendado (Roadmap de Refactorización)

1. **Prioridad 1: Saneamiento de Seguridad (Semana 1)**
    - Implementar `password_hash()` y `password_verify()` en lugar de `md5`.
    - Reemplazar todas las consultas `mysqli` manuales por Sentencias Preparadas (Prepared Statements) usando el objeto `$pdo` ya existente.
    - Aplicar la función helper `e()` (htmlspecialchars) en todos los puntos de salida de datos en el HTML.

2. **Prioridad 2: Unificación de la Capa de Datos (Semana 2)**
    - Eliminar `db_connect.php` y migrar todos los scripts a `config/database.php` (PDO).
    - Estandarizar la estructura de los modelos para que hereden de una clase base de conexión.

3. **Prioridad 3: Limpieza de Código Muerto (Semana 3)**
    - Eliminar archivos `.bak`, `.sql` (fuera de la carpeta database) y scripts de prueba (`test_*.php`).
    - Consolidar `includes/` y eliminar la carpeta redundante `views/layout/`.

4. **Prioridad 4: Modularización y MVC (Semana 4+)**
    - Migrar la lógica de `php_action/*.php` hacia los controladores en `controllers/`.
    - Implementar un sistema de ruteo básico para evitar tener archivos `.php` expuestos directamente en la raíz.
