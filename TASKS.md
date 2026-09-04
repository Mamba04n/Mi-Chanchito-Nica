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

## Fase 1 — Core
- [x] Migrations `companies` y `company_user`.
- [x] `CompanyContext`.
- [x] autorización multiempresa.
- [ ] roles/permisos mínimos.
- [x] `modules` y `company_modules`.
- [x] `ModuleManager`.
- [x] onboarding de empresa + selección de módulos.
- [ ] bitácora de auditoría.
- [x] tests de aislamiento.

## Fase 2 — Finanzas
- [ ] Customers/items.
- [ ] Invoices/lines.
- [ ] emisión contado/crédito.
- [ ] Receivables.
- [ ] Customer payments/applications.
- [ ] Cash accounts/movements.
- [ ] Inventory/stock/movements.
- [ ] Suppliers/purchases.
- [ ] Payables/supplier payments.
- [ ] Dashboard inicial.
- [ ] tests de integración financieros.

## Fase 3 — Diagnóstico
- [ ] indicator definitions.
- [ ] snapshots.
- [ ] diagnostic rules.
- [ ] AR_OVERDUE_HIGH.
- [ ] CASH_COVERAGE_LOW.
- [ ] STOCK_BELOW_MIN.
- [ ] UI de señales.

## Fase 4 — Educación
- [ ] source documents.
- [ ] source chunks/retrieval simple.
- [ ] cursos/unidades/lecciones.
- [ ] tareas.
- [ ] quizzes.
- [ ] progreso.
- [ ] 3 rutas demo.

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
