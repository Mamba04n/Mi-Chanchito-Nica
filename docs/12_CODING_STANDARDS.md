# 12 — Estándares de código y Git

## PHP / Laravel

- `declare(strict_types=1);` en clases propias cuando sea consistente con el proyecto.
- tipar parámetros y retornos.
- evitar métodos gigantes.
- usar Form Requests o validación Livewire bien encapsulada.
- lógica de negocio en Actions/Services, no en Blade.
- Policies para autorización.
- enums PHP para estados estables cuando aporten claridad.
- Value Objects solo cuando reduzcan errores reales.
- transacciones DB para flujos que modifican varias tablas.
- `decimal`/strings para dinero; nunca float.

## Nombres

- código en inglés;
- interfaz en español;
- DB en snake_case;
- clases PascalCase;
- métodos camelCase;
- event names en pasado (`InvoiceIssued`).

## Estados

Preferir enums explícitos:

```text
InvoiceStatus: Draft, Issued, Partial, Paid, Overdue, Annulled
ReceivableStatus: Open, Partial, Paid, Overdue
ChallengeStatus: Assigned, InProgress, Submitted, Passed, Failed
```

## Controladores / Livewire

Un componente no debe:

- calcular indicadores complejos;
- llamar directamente al SDK IA;
- modificar varias entidades sin una Action transaccional;
- decidir permisos solo ocultando botones.

## Git

Ramas cortas:

```text
feat/core-company
feat/module-registry
feat/sales-invoices
feat/education-courses
feat/ai-recommender
fix/receivable-balance
```

Commits claros:

```text
feat(sales): add credit invoice flow
fix(cxc): prevent payment above balance
test(tenant): cover cross-company access
docs(ai): document challenge output schema
```

## PR/revisión

Cada cambio importante debe responder:

- qué requerimiento cubre;
- qué cambió;
- cómo probarlo;
- riesgo de migración;
- capturas si cambia UI;
- pruebas ejecutadas.

## Deuda técnica

No ocultarla. Agregar a `TASKS.md` con prioridad. No convertir el hackathon en una excusa para acoplar lógica financiera e IA.
