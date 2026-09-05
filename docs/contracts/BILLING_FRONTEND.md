# Contrato Frontend: Facturación (Billing)

## Livewire Components Esperados

### 1. `Billing\InvoiceList`
- **Ruta:** `/billing/invoices` (GET)
- **Propósito:** Mostrar listado paginado de facturas.
- **Estado:**
  - `search` (string)
  - `status` (enum InvoiceStatus)
  - `sale_type` (enum SaleType)
- **Métodos:** Ninguno crítico.

### 2. `Billing\InvoiceForm`
- **Rutas:** 
  - `/billing/invoices/create` (GET)
  - `/billing/invoices/{id}/edit` (GET)
- **Propósito:** Crear o editar un borrador (Draft).
- **Acciones Backend Invocadas:** `CreateInvoiceDraft`, `UpdateInvoiceDraft`, `CalculateInvoiceTotals`.
- **Estado:**
  - `customer_id`
  - `issue_date`, `due_date`
  - `currency`, `sale_type`
  - `items` (array: `product_id`, `warehouse_id`, `description`, `quantity`, `unit_price`, `discount`, `tax`)
  - `notes`

### 3. `Billing\InvoiceDetail`
- **Ruta:** `/billing/invoices/{id}` (GET)
- **Propósito:** Mostrar el desglose, estado, y permitir confirmación/cancelación si corresponde.
- **Acciones Backend Invocadas:** 
  - `ConfirmInvoice`: Transaccional, deduce inventario y genera CxC.
  - `CancelInvoice`: Cancela factura en draft o confirmada (sin abonos).
- **Políticas Requeridas:** `billing.view`, `billing.confirm`, `billing.cancel`

## Errores Esperables
- Stock insuficiente: El usuario debe ser notificado desde el backend.
- Modificación no permitida: Solo se pueden editar facturas en `draft`.
