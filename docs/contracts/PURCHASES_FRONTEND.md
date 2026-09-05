# Contrato Frontend: Compras (Purchases)

## Livewire Components Esperados

### 1. `Purchases\PurchaseList`
- **Ruta:** `/purchases` (GET)
- **Propósito:** Listado paginado de compras.
- **Estado:**
  - `status` (draft, confirmed, partially_paid, paid, cancelled)
  - `search`
- **Model:** `Purchase` (relaciones: `supplier`, `warehouse`).
- **Policy:** `purchases.view`

### 2. `Purchases\PurchaseForm`
- **Ruta:** `/purchases/create` y `/purchases/{id}/edit`
- **Propósito:** Creación y edición de compras en estado Draft.
- **Acción Backend:** `CreatePurchaseDraft->execute()`
- **Validaciones:**
  - `supplier_id` debe existir.
  - Al menos una línea de detalle.
- **Policy:** `purchases.create` y `purchases.update`

### 3. `Purchases\PurchaseDetail`
- **Ruta:** `/purchases/{id}` (GET)
- **Propósito:** Visualizar la compra y permitir acciones (Confirmar, Anular).
- **Acciones Backend:**
  - `ConfirmPurchase->execute()` -> Policy: `purchases.confirm`
  - `CancelPurchase->execute()` -> Policy: `purchases.cancel`
- **Alertas de Error:**
  - Mostrar alertas si `ConfirmPurchase` lanza una `Exception` (por ejemplo, intentar registrar productos sin almacén).

## Errores Esperables
- Error transaccional por fallo en Inventario (e.g. validaciones internas de InventoryService).
- Compras a crédito generarán automáticamente una CxP invisible hasta que se consulte el módulo de Payables.
