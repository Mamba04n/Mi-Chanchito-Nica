# TASKS — tablero ejecutable

Leyenda: `[ ]` pendiente · `[-]` en proceso · `[x]` terminado · `[!]` bloqueado

## Fase 0 — Bootstrap
- [x] Crear proyecto Laravel 13.
- [x] Configurar MySQL y `.env.example` sin secretos.
- [x] Instalar/configurar Livewire 4.
- [x] Configurar Tailwind/Vite.
- [x] Configurar Pest.
- [x] Implementar autenticación.
- [x] Crear layout base con design tokens.
- [x] Crear seed de usuario administrador demo.

## Fase 1 — Core y Base
- [x] Migrations `companies` y `company_user`.
- [x] `CompanyContext` y aislamiento multiempresa.
- [x] Roles y permisos mínimos.
- [x] `modules` y `ModuleManager`.
- [x] Onboarding de empresa + selección de módulos.
- [x] Bitácora de auditoría (AuditLogger).
- [x] Tests de aislamiento.
- [x] Catálogo base (Clientes, Proveedores, Categorías, Unidades, Productos/Servicios).

## Fase 2 — Finanzas
- [x] Inventory/stock/movements/warehouses.
- [x] Customers/items invoices/lines (Backend).
- [x] Emisión contado/crédito (Backend).
- [x] Receivables (Backend).
- [x] Customer payments/applications (Backend).
- [x] Cash accounts/movements (Treasury Backend).
- [x] Suppliers/purchases (Backend).
- [x] Payables/supplier payments (Backend).
- [x] Dashboard inicial (Global Financial Analytics).
- [x] Tests de integración financieros.

## Fase 3 — Diagnóstico
- [ ] indicator definitions.
- [ ] snapshots.
- [ ] diagnostic rules.
- [ ] AR_OVERDUE_HIGH.
- [ ] CASH_COVERAGE_LOW.
- [ ] STOCK_BELOW_MIN.
- [ ] UI de señales.

## Fase 4 — Educación
- [x] source documents.
- [x] source chunks/retrieval simple.
- [x] cursos/unidades/lecciones.
- [x] tareas.
- [x] quizzes.
- [x] progreso.
- [x] 3 rutas demo.

## Fase 5 — Gamificación
- [ ] perfil XP/nivel.
- [ ] racha.
- [ ] logros.
- [ ] challenges/user challenges.
- [ ] reglas anti-duplicación de XP.

## Fase 6 — IA
- [ ] `AiProvider`.
- [ ] adapter del proveedor elegido.
- [ ] prompt templates + versions.
- [ ] structured response validator.
- [ ] `FinancialWeaknessExplainer`.
- [ ] `LearningPathRecommender`.
- [ ] `SourceRetriever`.
- [ ] `GroundedTutor`.
- [ ] `ChallengeGenerator`.
- [ ] logs de ejecución.
- [ ] rate limit/timeout/fallback.
- [ ] tests con fake provider.

## Fase 7 — Demo
- [ ] seed empresa Pulpería El Progreso.
- [ ] seed cartera C$100,000 / C$35,000 vencida.
- [ ] seed rutas y fuentes.
- [ ] seed prompts/reglas.
- [ ] demo integral ensayado.
- [ ] revisar mobile.
- [ ] `php artisan test` verde.
- [ ] `npm run build` verde.
- [ ] README de instalación final.
