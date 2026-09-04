# 11 — Datos y escenario de demostración

## Objetivo

Tener un demo reproducible que **siempre** produzca una señal clara para explicar el valor del producto.

## Empresa demo

**Nombre:** Pulpería El Progreso

**Moneda:** NIO / C$

**Módulos activos:**

- Sales
- Inventory
- Purchases
- Receivables
- Payables
- Cash
- Reporting
- Education
- Gamification
- Intelligence

## Escenario CxC

Crear cuentas por cobrar activas por **C$100,000** de saldo total.

De ellas, **C$35,000** están vencidas.

```text
ratio = 35,000 / 100,000 * 100 = 35%
```

Regla:

```text
si overdue_ratio > 25% => AR_OVERDUE_HIGH
```

Resultado esperado:

- severidad alta;
- tarjeta de diagnóstico;
- recomendación educativa “Gestión de cuentas por cobrar / Crédito y cobranza”.

## Respuesta IA esperada, no literal

La IA debe explicar algo equivalente a:

> Una parte importante del dinero pendiente de cobro ya superó su fecha de vencimiento. Esto puede presionar el efectivo disponible. Puedes revisar la ruta de crédito y cobranza para aprender políticas de seguimiento y priorización.

No debe decir que conoce la causa específica ni prometer que una acción resolverá la situación.

## Ruta educativa demo

**Curso:** Control financiero para una PyME

**Unidad:** Crédito y cobranza

**Clase 1:** Qué significa vender a crédito

**Clase 2:** Vencimientos y antigüedad de saldos

**Tarea:** clasificar 8 cuentas ficticias por prioridad de seguimiento.

**Examen:** 5-8 preguntas mezclando conceptos y caso.

**Reto IA:** diseñar una política simple de seguimiento para una empresa ficticia con cartera vencida, usando una fuente aprobada y una rúbrica de 4 criterios.

## Evidencia final del demo

1. abrir dashboard;
2. mostrar 35% cartera vencida;
3. abrir “¿Qué significa?”;
4. IA explica;
5. pulsar “Estudiar ahora”;
6. abrir la fuente original;
7. completar clase/tarea rápida;
8. mostrar XP;
9. generar reto;
10. enviar respuesta demo;
11. mostrar feedback y progreso.

## Seeders

Crear seeders idempotentes de demo:

- `DemoCompanySeeder`
- `DemoFinanceSeeder`
- `DemoEducationSeeder`
- `DemoGamificationSeeder`
- `PromptTemplateSeeder`
- `DiagnosticRuleSeeder`

No depender de llamadas reales de IA para seedear datos.
