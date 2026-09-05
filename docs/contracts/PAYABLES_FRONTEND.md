# Contrato Frontend: Cuentas por Pagar (Payables)

## Livewire Components Esperados

### 1. `Payables\PayableDashboard`
- **Ruta:** `/payables` (GET)
- **Propósito:** Mostrar los KPIs principales (Total, Vencido, %) y los proveedores con más saldo pendiente.
- **Servicio:** `PayablesDashboardService->getIndicators()`

### 2. `Payables\PayableList`
- **Ruta:** `/payables/list` (GET)
- **Propósito:** Listado paginado de cuentas por pagar.
- **Estado:**
  - `status` (pending, partial, overdue)
  - `search`
- **Model:** `AccountPayable` (relaciones: `supplier`, `purchase`).

### 3. `Payables\RegisterPayment`
- **Ruta:** Interfaz modal o panel dentro del detalle de la CxP (`/payables/{id}`).
- **Propósito:** Registrar abonos a proveedores.
- **Acción Backend:** `RegisterPayablePayment->execute()`
- **Validaciones Front:** `amount > 0` y `amount <= balance`.
- **Policy:** `payables.payment.create`

## Comportamiento Concurrente
- El backend implementa `lockForUpdate()` para prevenir pagos dobles. Si un pago se rechaza, notificar al usuario.
- El saldo (`balance`) se recalcula estrictamente en el backend.
