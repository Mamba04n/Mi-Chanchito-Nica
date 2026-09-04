# Contrato Frontend: BLOQUE 2 - CATÁLOGOS BASE

Este documento establece el contrato entre Backend y Frontend para las funcionalidades de Clientes, Proveedores y Catálogo de Productos/Servicios.

## Consideraciones Generales
- Todos los datos están aislados por empresa. El backend se encarga automáticamente de esto.
- Las desactivaciones son lógicas (`active = false`). No existe borrado físico.
- Los módulos deben revisar permisos como `customers.view`, `catalog.create`, etc.
- Puedes utilizar las directivas de Blade u omitir renderizar los botones de "Crear"/"Editar" si el usuario no tiene permisos.

---

## 1. Módulo: Clientes (Customers)

### Componente Livewire: `App\Livewire\Customers\CustomerList`
- **Ubicación Vista:** `resources/views/modules/customers/index.blade.php` (o similar).
- **Propiedades Públicas:**
  - `Collection $customers`: Colección de clientes de la empresa.
  - `string $search`: Propiedad sugerida para barra de búsqueda (por nombre, identificación o email).
- **Métodos:**
  - `deactivate(int $customerId)`: Desactiva lógicamente un cliente (cambia `active` a `false`). Emite un evento u ofusca el error si el cliente tiene facturas pendientes (se resolverá a nivel de Backend con exceptions).

### Componente Livewire: `App\Livewire\Customers\CustomerForm`
- **Ubicación Vista:** `resources/views/modules/customers/form.blade.php`
- **Propiedades Públicas (Mapeadas a Customer):**
  - `int|null $customerId`: Nulo si es creación.
  - `string $type`: 'individual' o 'company'.
  - `string $name`: Obligatorio.
  - `string $legal_name`: Opcional.
  - `string $identification`: Opcional (RUC o Cédula).
  - `string $email`: Opcional.
  - `string $phone`: Opcional.
  - `string $address`: Opcional.
  - `float $credit_limit`: Límite de crédito (Backend maneja decimales). Default 0.
  - `int $credit_days`: Días de crédito. Default 0.
  - `string $notes`: Opcional.
- **Métodos:**
  - `save()`: Valida y guarda (crea o actualiza) el cliente. Emite `customer-saved`.

---

## 2. Módulo: Proveedores (Suppliers)

### Componente Livewire: `App\Livewire\Suppliers\SupplierList`
- **Ubicación Vista:** `resources/views/modules/suppliers/index.blade.php`
- **Propiedades Públicas:**
  - `Collection $suppliers`.
  - `string $search`.
- **Métodos:**
  - `deactivate(int $supplierId)`.

### Componente Livewire: `App\Livewire\Suppliers\SupplierForm`
- **Ubicación Vista:** `resources/views/modules/suppliers/form.blade.php`
- **Propiedades Públicas (Mapeadas a Supplier):**
  - `int|null $supplierId`
  - `string $type`: 'individual' o 'company'.
  - `string $name`, `string $legal_name`, `string $identification`, `string $email`, `string $phone`, `string $address`, `string $notes`.
  - `int $payment_terms_days`: Condiciones de pago en días. Default 0.
- **Métodos:**
  - `save()`: Emite `supplier-saved`.

---

## 3. Módulo: Catálogo (Products, Categories, Units)

### Componente Livewire: `App\Livewire\Catalog\ProductList`
- **Ubicación Vista:** `resources/views/modules/catalog/index.blade.php`
- **Propiedades Públicas:**
  - `Collection $products`
  - `string $search`: Filtro por `name` o `sku`.
- **Métodos:**
  - `deactivate(int $productId)`

### Componente Livewire: `App\Livewire\Catalog\ProductForm`
- **Ubicación Vista:** `resources/views/modules/catalog/form.blade.php`
- **Propiedades Públicas:**
  - `int|null $productId`
  - `int|null $category_id`
  - `int|null $unit_id`
  - `string $sku`: Obligatorio, único dentro de la empresa.
  - `string $name`: Obligatorio.
  - `string $description`: Opcional.
  - `string $type`: 'product' o 'service'.
  - `float $sale_price`: Precio de venta.
  - `float $cost`: Costo.
  - `bool $track_inventory`: True por defecto si es 'product', false si es 'service'.
- **Métodos:**
  - `save()`: Valida que el SKU sea único y crea/actualiza. Emite `product-saved`.
- **Colecciones de Apoyo:**
  - `$categories`: Para llenar el select de categoría.
  - `$units`: Para llenar el select de unidad de medida.

### Componentes Livewire Secundarios: `CategoryList` y `UnitList`
- **Propósito:** Mantenedores simples tipo CRUD en modales o sub-páginas dentro de configuración/catálogo para crear Categorías y Unidades de Medida.
- **Properties Category:** `name`, `description`, `parent_id`.
- **Properties Unit:** `name`, `abbreviation`.
