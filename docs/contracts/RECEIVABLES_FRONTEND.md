# Contrato Frontend: Cuentas por Cobrar (Receivables)

## Livewire Components Esperados

### 1. `Receivables\ReceivableDashboard`
- **Ruta:** `/receivables` (GET)
- **Propósito:** Mostrar los KPIs principales (Total, Vencido, %) y los deudores Top.
- **Servicio:** `ReceivablesDashboardService->getIndicators()`

### 2. `Receivables\ReceivableList`
- **Ruta:** `/receivables/list` (GET)
- **Propósito:** Listado paginado de la cartera.
- **Estado:**
  - `status` (pending, partial, overdue)
  - `search`
- **Model:** `AccountReceivable` (relaciones: `customer`, `invoice`).

### 3. `Receivables\PaymentModal`
- **Ruta:** Interfaz modal o inline sobre el detalle.
- **Propósito:** Registrar abonos.
- **Acción Backend:** `RegisterReceivablePayment->execute()`
- **Validaciones:** `amount > 0` y `amount <= balance`.

## Errores Esperables
- Error de concurrencia: Evitado en Backend con `lockForUpdate()`. Si falla, mostrar error de "Saldo superado" o similar.
