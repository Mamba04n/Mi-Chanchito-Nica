# Contrato Frontend: Caja y Tesorería (Treasury)

Este documento define la estructura y requerimientos para el desarrollo de la interfaz de usuario (Livewire + Blade) del módulo de Tesorería del sistema.

## Componentes Livewire Esperados

### 1. `Treasury\Dashboard`
- **Ruta:** `/treasury` (GET)
- **Propósito:** Mostrar las métricas de saldo total, distribución por cuentas (ej. gráfico Donut) y entradas/salidas del periodo seleccionado (ej. últimos 30 días, gráfico de barras/líneas).
- **Servicio Backend:** `TreasuryDashboardService->getDashboardMetrics(companyId, days)`
- **Autorización:** `treasury.view`

### 2. `Treasury\AccountList`
- **Ruta:** `/treasury/accounts` (GET)
- **Propósito:** Listar todas las cuentas financieras (`FinancialAccount`) con sus saldos.
- **Acciones permitidas:**
  - Crear nueva cuenta (con o sin saldo inicial). Modal que llama a `CreateFinancialAccount`.
  - Configurar si la cuenta está activa o es la cuenta por defecto.
- **Autorización:** `treasury.manage_accounts`

### 3. `Treasury\MovementList`
- **Ruta:** `/treasury/movements` (GET)
- **Propósito:** Listado paginado y filtrable del historial completo de movimientos (`FinancialMovement`).
- **Filtros requeridos:**
  - Por Cuenta
  - Por Rango de Fechas
  - Por Tipo de Movimiento (Ingreso, Egreso, Transferencia, Ajuste)

### 4. `Treasury\RegisterTransaction` (Componente o Modal)
- **Ruta:** Accesible globalmente dentro de `/treasury`
- **Propósito:** Registrar un ingreso manual (`RegisterIncome`) o egreso manual (`RegisterExpense`).
- **Validaciones:**
  - Monto > 0.
  - Para Egresos en cuentas tipo `CASH`, el sistema lanzará un `Exception` si no hay fondos suficientes. Interceptar este error y mostrar alerta de advertencia (no crash).
- **Autorización:** `treasury.income.create` o `treasury.expense.create` según corresponda.

### 5. `Treasury\TransferFunds`
- **Ruta:** Accesible desde la lista de cuentas.
- **Propósito:** Trasladar fondos de la Cuenta A a la Cuenta B (`TransferFunds`).
- **Validaciones:**
  - Cuenta origen distinta a la destino.
  - Monto > 0.
  - El backend se encarga de transaccionar la extracción e inserción.

## Integraciones Visuales (Otros Módulos)

### Pagos CxC y CxP
Cuando el módulo de Tesorería (`treasury`) esté encendido:
- En `RegisterReceivablePayment` (Módulo Receivables) **debe mostrarse un select** obligatorio llamado "Cuenta de Ingreso" (`financial_account_id`).
- En `RegisterPayablePayment` (Módulo Payables) **debe mostrarse un select** obligatorio llamado "Cuenta de Egreso" (`financial_account_id`).

### Reversiones (Ajustes)
Para revertir movimientos por error administrativo (`ReverseFinancialMovement`), la opción debe estar protegida bajo el permiso especial `treasury.adjust` y el usuario deberá incluir obligatoriamente el "Motivo" (`reason`).
