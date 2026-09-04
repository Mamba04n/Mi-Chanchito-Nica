# Estructura del Frontend

Este documento detalla la estructura organizativa exclusiva para el desarrollo del **Frontend** (vistas Blade, CSS, JS) en el contexto de nuestra arquitectura modular basada en Odoo.

## Principios Generales

- **Frontend y Backend Conviven:** El código de la interfaz gráfica (`.blade.php`, `.css`, `.js`) vive en el mismo repositorio Laravel que el backend, pero mantienen fronteras estrictas a nivel de carpetas y responsabilidades.
- **Aislamiento Modular:** Cada módulo de la aplicación (ej. Inventario, Ventas, CXC) tiene su propio espacio de trabajo. Cuando desarrollas la interfaz de un módulo, limitas tu trabajo a la carpeta de dicho módulo.
- **Componentes Globales:** Los elementos visuales que se repiten en todo el ERP (botones primarios, tarjetas, inputs, tablas genéricas) se ubican en `resources/views/components/`. No se deben duplicar dentro de los módulos.

## Convención de Trabajo (Backend → Frontend)

1. El equipo de Backend prepara el esqueleto: migraciones, modelos, controladores y la clase PHP de los componentes Livewire correspondientes a un módulo.
2. El equipo de Frontend recibe la notificación de que el esqueleto está listo y procede a maquetar y diseñar la interfaz en las carpetas preparadas.
3. El frontend **NO debe** modificar la base de datos (migraciones/modelos) ni la lógica central del negocio sin consultar al Product Owner.
4. El frontend tiene total libertad dentro de `resources/views/modules/{nombre_modulo}`, `resources/css` y `resources/js`.

## Árbol de Carpetas

### `resources/views/`
La base de todas las plantillas Blade.

- `layouts/`: Plantillas maestras de la aplicación (Ej: AppLayout, GuestLayout).
- `components/`: Componentes Blade genéricos y globales (Botones, formularios, alertas, modales).
- `shared/`: Vistas genéricas compartidas como *Empty States*, pantallas de error (404, 403), o indicadores de carga.
- `apps/`: Contiene el App Launcher (menú principal tipo Odoo) y el selector visual de módulos.
- `dashboard/`: Pantalla de inicio tras ingresar al sistema.
- `settings/`: Configuración global de la empresa, perfiles, roles y usuarios.
- `auth/`: Vistas de inicio de sesión, registro y recuperación de contraseña.
- `modules/`: **El corazón del trabajo modular.**

### `resources/views/modules/{nombre_modulo}/`
Dentro de cada módulo, la estructura interna debe seguir este patrón:

- `pages/`: Pantallas completas del módulo (ej. `index.blade.php`, `show.blade.php`).
- `components/`: Componentes de UI que solo tienen sentido dentro de este módulo (ej. `kardex-table.blade.php`).
- `forms/`: Formularios específicos del módulo.
- `partials/`: Pequeños fragmentos visuales (headers, tabs) para organizar las vistas más grandes.

*(Nota: En módulos muy complejos, las `pages/` pueden subdividirse en carpetas por subdominio funcional, por ejemplo, en inventario: `products`, `warehouses`, `stock`).*

### `app/Livewire/`
Organización de clases PHP para Livewire.
- Sigue la misma estructura de módulos (ej. `app/Livewire/Inventory/`).
- **Solo Backend:** El desarrollador frontend no necesita crear archivos aquí, pero debe conocer la ruta para enlazar sus propiedades `wire:model` y acciones `wire:click`.

### `resources/css/` & `resources/js/`
- Organizado en `base/`, `components/`, `utilities/` y `modules/`.
- Uso principal para estilos o scripts personalizados que no puedan ser cubiertos exclusivamente por Tailwind CSS o Alpine.js en línea.
