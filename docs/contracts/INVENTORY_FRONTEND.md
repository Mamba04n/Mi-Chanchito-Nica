# Contrato Frontend: BLOQUE 3 - INVENTARIO

Este documento establece el contrato entre Backend y Frontend para las funcionalidades de Inventario (Almacenes, Existencias, Movimientos, Ajustes, Transferencias, Kardex).

## Consideraciones Generales
- Todos los datos están aislados por empresa de forma automática.
- Las cantidades son numéricas decimales (`decimal:2`).
- Las modificaciones al stock NUNCA se hacen de manera directa (ej. `update quantity=X`), sino a través de **Movimientos** mediante los servicios centralizados (`InventoryService`).
- El inventario negativo NO está permitido (el Backend arrojará excepción o mensaje de error en la validación).
- Únicamente los productos donde `track_inventory = true` participan del inventario. Los servicios (`type = 'service'`) deben ser filtrados u omitidos en estas vistas.

---

## 1. Módulo: Almacenes (Warehouses)

### Componente Livewire: `App\Livewire\Inventory\WarehouseList`
- **Ubicación Vista Sugerida:** `resources/views/modules/inventory/warehouses/index.blade.php`
- **Propiedades Públicas:**
  - `Collection $warehouses`: Lista de almacenes de la empresa.
  - `string $code`, `string $name`, `string $description`, `string $address`, `bool $active`, `bool $is_default`.
- **Métodos:**
  - `save()`: Crea o actualiza almacén. Valida código único por empresa (`company_id` + `code`).
  - `deactivate(int $warehouseId)`: Desactiva lógicamente.

---

## 2. Módulo: Existencias (Stock)

### Componente Livewire: `App\Livewire\Inventory\ProductStockList`
- **Ubicación Vista Sugerida:** `resources/views/modules/inventory/stock/index.blade.php`
- **Propiedades Públicas:**
  - `Collection $stocks`: Datos cruzados de `InventoryStock` con `Product` y `Warehouse`.
  - Filtros: `int|null $filterWarehouseId`, `string $searchProduct`.
- **Datos expuestos en $stocks:**
  - Producto (SKU y Nombre).
  - Almacén.
  - `quantity`: Cantidad total real.
  - `available_quantity`: Cantidad disponible real (Quantity - Reservado).
  - `minimum_stock`, `maximum_stock`.
- **Indicadores disponibles:**
  - Para mostrar en un pequeño "dashboard" de la vista de stock. Estos datos los provee `InventoryDashboardService`. (Total productos, productos con stock, bajo mínimo, agotados, valor aproximado).

---

## 3. Módulo: Movimientos (Movements)

### Componente Livewire: `App\Livewire\Inventory\MovementList`
- **Ubicación Vista Sugerida:** `resources/views/modules/inventory/movements/index.blade.php`
- **Propiedades Públicas:**
  - `LengthAwarePaginator $movements`: Lista paginada de movimientos.
  - Filtros: `int|null $filterWarehouseId`, `string|null $filterType`, `string $dateFrom`, `string $dateTo`.
- **Datos expuestos por fila:**
  - Fecha (`occurred_at`).
  - Tipo (`App\Enums\MovementType`).
  - Almacén.
  - Producto.
  - Razón / Referencia.
  - Cantidad (+ / -).
  - Usuario.
- **Acciones:**
  - Los movimientos **no se editan ni se eliminan**. Solo lectura.

---

## 4. Módulo: Ajustes de Inventario

### Componente Livewire: `App\Livewire\Inventory\StockAdjustment`
- **Ubicación Vista Sugerida:** `resources/views/modules/inventory/adjustments/form.blade.php`
- **Propiedades Públicas:**
  - `int $warehouse_id`.
  - `int $product_id`.
  - `float $real_quantity`: La cantidad física contada.
  - `string $reason`: Obligatorio (ej. "Conteo físico", "Daño").
  - `string $notes`: Opcional.
- **Métodos:**
  - `submit()`: Llama a `InventoryService::adjustStock`. Captura excepciones (ej. "El almacén no existe" o "La cantidad no ha cambiado"). Emite `stock-adjusted`.

---

## 5. Módulo: Transferencias de Inventario

### Componente Livewire: `App\Livewire\Inventory\StockTransfer`
- **Ubicación Vista Sugerida:** `resources/views/modules/inventory/transfers/form.blade.php`
- **Propiedades Públicas:**
  - `int $source_warehouse_id`: Almacén origen.
  - `int $destination_warehouse_id`: Almacén destino.
  - `int $product_id`.
  - `float $quantity`: Cantidad a transferir.
  - `string $notes`: Opcional.
- **Métodos:**
  - `submit()`: Llama a `InventoryService::transferStock`. Validará que Origen y Destino sean distintos, pertenezcan a la misma empresa y haya stock suficiente en el origen. Emite `stock-transferred`.

---

## 6. Módulo: Kardex

### Componente Livewire: `App\Livewire\Inventory\Kardex`
- **Ubicación Vista Sugerida:** `resources/views/modules/inventory/kardex/index.blade.php`
- **Propósito:** Mostrar la historia cronológica de entradas y salidas de **UN producto** en **UN almacén**, permitiendo ver el `previous_quantity` y `new_quantity` de forma hilada.
- **Propiedades Públicas:**
  - `int $product_id` (Requerido para filtrar).
  - `int $warehouse_id` (Requerido para filtrar).
  - `string $dateFrom`, `string $dateTo`.
  - `LengthAwarePaginator $records`: Básicamente `InventoryMovement` filtrado y ordenado cronológicamente ascendente.
