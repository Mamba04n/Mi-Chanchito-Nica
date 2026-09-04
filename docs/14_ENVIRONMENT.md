# 14 — Entorno y arranque local

## Requisitos

- PHP 8.3+
- Composer
- Node.js LTS + npm
- MySQL 8.x
- Git

## Crear desde cero

Si todavía no existe el proyecto:

```bash
composer create-project laravel/laravel mi-chanchito-nica
cd mi-chanchito-nica
composer require livewire/livewire
npm install
```

Configurar `.env`, crear DB y luego:

```bash
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan test
php artisan serve
```

Durante desarrollo puede usarse:

```bash
composer run dev
```

si el `composer.json` del proyecto incluye el script correspondiente.

## Variables de entorno IA

Agregar a `.env.example` sin valores secretos:

```text
AI_PROVIDER=
AI_API_KEY=
AI_MODEL_FAST=
AI_MODEL_REASONING=
AI_EMBEDDING_MODEL=
AI_REQUEST_TIMEOUT=30
```

## Queue

MVP simple:

```text
QUEUE_CONNECTION=database
```

Crear tablas si la versión/proyecto lo requiere y ejecutar worker:

```bash
php artisan queue:work
```

## No hacer

- no commitear `.env`;
- no usar credenciales de producción en demo;
- no ejecutar `migrate:fresh` contra una DB con datos reales;
- no poner la API key de IA en JavaScript.
