# 02 — Stack y arquitectura

## Stack base

- **Backend:** PHP 8.3+ / Laravel 13.x
- **UI:** Livewire 4.x + Blade + Alpine.js
- **CSS:** Tailwind CSS
- **DB:** MySQL 8.x
- **Build:** Vite
- **Testing:** Pest
- **Queue:** database para MVP; Redis cuando aporte valor
- **Cache:** Laravel Cache; Redis opcional
- **Auth:** autenticación Laravel + políticas/gates; evitar dependencias innecesarias
- **IA:** proveedor configurable por adapter mediante Laravel HTTP Client o SDK encapsulado

## Estilo arquitectónico

Usar **monolito modular**. Es una sola aplicación desplegable, pero el código se separa por dominio para poder crecer sin convertirse en un bloque acoplado.

Estructura sugerida:

```text
app/
├── Modules/
│   ├── Core/
│   ├── ModuleRegistry/
│   ├── Sales/
│   ├── Inventory/
│   ├── Purchases/
│   ├── Receivables/
│   ├── Payables/
│   ├── Cash/
│   ├── Reporting/
│   ├── Education/
│   ├── Gamification/
│   └── Intelligence/
├── Livewire/
├── Support/
└── Providers/
```

Dentro de cada módulo usar solo las carpetas necesarias, por ejemplo:

```text
Sales/
├── Actions/
├── Models/
├── Services/
├── Policies/
├── Data/
└── Events/
```

No forzar DDD ceremonial. La separación existe para mantener límites claros, no para crear archivos vacíos.

## Capas prácticas

### UI

Livewire/Blade recibe acciones del usuario, muestra validaciones y llama servicios/acciones. No contiene lógica financiera compleja.

### Aplicación

Acciones coordinan casos de uso: `IssueInvoice`, `RecordPayment`, `ReceivePurchase`, `CompleteLesson`.

### Dominio

Servicios y modelos aplican reglas, cálculos y estados.

### Infraestructura

Persistencia, clientes IA, almacenamiento, queues y búsqueda de fuentes.

## Tenant / empresa activa

El usuario puede pertenecer a una o más empresas mediante membresía. El sistema conserva una **empresa activa** en el contexto de sesión.

Reglas:

- resolver empresa activa desde membresía autorizada;
- cada registro empresarial tiene `company_id`;
- políticas validan pertenencia y permiso;
- usar un `CompanyContext` central en lugar de repetir lógica de sesión;
- nunca aceptar `company_id` del cliente como autoridad.

## Registro modular

Tablas conceptuales:

```text
modules
company_modules
```

`ModuleManager` debe permitir:

- consultar módulos disponibles;
- activar módulo;
- validar dependencias;
- desactivar sin borrar historial;
- comprobar `isActive(company, module)`;
- integrar middleware/guards para rutas y acciones.

Dependencias base:

```text
Sales -> Core
Inventory -> Core
Purchases -> Core
Receivables -> Sales
Payables -> Purchases
Cash -> Core
Reporting -> módulos activos
Education -> Core User
Gamification -> Education
Intelligence -> Reporting + Education
```

## Eventos de integración

Usar eventos cuando reduzcan acoplamiento, por ejemplo:

- `InvoiceIssued`
- `PaymentReceived`
- `PurchaseConfirmed`
- `SupplierPaymentRecorded`
- `InventoryMovementCreated`
- `IndicatorThresholdCrossed`
- `LessonCompleted`
- `ChallengeCompleted`

Ejemplo: `InvoiceIssued` puede originar CxC e inventario sin que el componente Livewire conozca los detalles.

## Jobs / colas

Las llamadas IA no deben bloquear el flujo financiero. Usar jobs para recomendaciones o generación cuando la UX lo permita. El registro financiero debe completarse aunque el proveedor IA no responda.

## Cache

Cachear:

- catálogo de módulos;
- fuentes/rutas públicas de lectura frecuente;
- respuestas IA idempotentes cuando sea seguro;
- dashboard pesado por periodos cortos si el MVP lo necesita.

Nunca cachear sin incluir `company_id` cuando el resultado depende de la empresa.

## Errores y observabilidad

- errores técnicos a logs protegidos;
- mensajes sencillos al usuario;
- registrar `ai_executions` sin secretos ni datos sensibles innecesarios;
- correlacionar recomendaciones con regla, indicador, prompt y versión.
