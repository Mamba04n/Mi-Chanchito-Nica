# Contrato Frontend: Dashboard Financiero Global (Business Dashboard)

Este documento define la estructura y los requerimientos para el desarrollo de la interfaz principal o *Dashboard* de "Mi Chanchito Nica".

## Propósito
Consolidar la información de los diferentes módulos activos (Ventas, Compras, CxC, CxP, Inventario, Tesorería) de manera centralizada. Este dashboard es el punto de entrada para los usuarios y provee una vista rápida de la salud operativa de su negocio, sin IA aún pero preparado para ella mediante un sistema de alertas operativas.

---

## Estructura de Respuesta del Backend

El `BusinessDashboardService->getDashboard(Company $company, Carbon $from, Carbon $to)` devolverá un arreglo estructurado con la siguiente forma garantizada:

```json
{
  "period": {
    "from": "2026-09-01",
    "to": "2026-09-04"
  },
  "sales": {
    "total_period": 15000.50,
    "count": 12,
    "average_ticket": 1250.04,
    "cash_sales": 5000.50,
    "credit_sales": 10000.00,
    "pending_balance": 10000.00,
    "top_customers": [
      { "customer_id": 1, "volume": 8000, "customer": { "id": 1, "name": "Cliente A" } }
    ],
    "daily_series": {
      "2026-09-01": 5000,
      "2026-09-02": 10000
    }
  },
  "purchases": {
    "total_period": 8000.00,
    "count": 5,
    "cash_purchases": 2000.00,
    "credit_purchases": 6000.00,
    "pending_balance": 6000.00,
    "top_suppliers": [
       { "supplier_id": 2, "volume": 5000, "supplier": { "id": 2, "name": "Proveedor B" } }
    ],
    "daily_series": {
      "2026-09-01": 8000
    }
  },
  "receivables": {
    "total_receivable": 20000,
    "total_overdue": 5000,
    "overdue_percentage": 25.0,
    "clients_with_debt": 3,
    "top_debtors": [],
    "aging": []
  },
  "payables": {
    "total_balance": 15000,
    "overdue_balance": 2000,
    "overdue_percentage": 13.3,
    "top_suppliers": []
  },
  "treasury": {
    "total_balance": 35000.00,
    "net_flow": 2500.00,
    "total_in": 5500.00,
    "total_out": 3000.00,
    "accounts": []
  },
  "inventory": {
    "total_inventoriable_products": 45,
    "products_with_stock": 40,
    "products_low_stock": 3,
    "products_out_of_stock": 2,
    "approximate_inventory_value": 140000
  },
  "alerts": [
    {
      "key": "inventory_out_of_stock",
      "domain": "inventory",
      "severity": "critical",
      "value": "2",
      "threshold": null,
      "context": "Existen productos sin existencias.",
      "recommended_learning_topic": "inventory_management"
    }
  ],
  "domain_statuses": {
    "sales": "healthy",
    "purchases": "healthy",
    "receivables": "attention",
    "payables": "healthy",
    "treasury": "healthy",
    "inventory": "critical"
  }
}
```

> [!WARNING]
> Si la empresa **no tiene activado un módulo**, el valor de su llave en la raíz será el string `"unavailable"`. Por ejemplo, si no utilizan Inventario: `"inventory": "unavailable"`. 
> El frontend **DEBE** verificar si el valor es el string `"unavailable"` antes de intentar acceder a llaves como `inventory.approximate_inventory_value` para evitar un error fatal en la vista.

---

## Guía Visual y Componentes Livewire

### 1. `Dashboard\Overview`
- **Ruta:** `/dashboard` (GET)
- **Propósito:** Mostrar los 4-6 KPIs más importantes de la empresa y las alertas operativas críticas.

#### KPIs recomendados para Mobile-First (Hero Section):
1. **Flujo de Caja Total** (`treasury.total_balance`).
2. **Posición de CxC** (`receivables.total_receivable`).
3. **Ventas del Mes** (`sales.total_period`).
4. **Flujo Neto del Mes** (`treasury.net_flow`).

### 2. `Dashboard\AlertsWidget`
- Iterar sobre el array `alerts`.
- Si `severity == 'critical'`, renderizar en color ROJO (e.g., bg-red-100 text-red-800).
- Si `severity == 'attention'`, renderizar en color AMARILLO/NARANJA (e.g., bg-orange-100 text-orange-800).
- Mostrar el `context` (mensaje de alerta). Ocultar el `recommended_learning_topic` por ahora, se utilizará en bloques futuros de Educación IA.

### 3. `Dashboard\ChartsWidget`
Utilizar una librería como **ApexCharts** (vía Alpine.js o nativo) o **Chart.js**.
- **Gráfico de Ventas vs Compras (Líneas/Barras):**
  - Utilizar `sales.daily_series` cruzado con `purchases.daily_series` para mostrar el rendimiento en el período seleccionado.
- **Gráfico de Deuda (Donut o Barras horizontales):**
  - Utilizar `receivables.total_receivable` vs `payables.total_balance` para mostrar un resumen de obligaciones.

### 4. `Dashboard\TopCustomersWidget`
- Mostrar una pequeña lista utilizando `sales.top_customers` o `receivables.top_debtors`. Se recomienda diferenciar claramente "Mejores Clientes" (los que más compran) de "Mayores Deudores" (los que más deben).

---

## Separación Semántica Obligatoria

El frontend **NUNCA** debe sumar las facturas de ventas para mostrar el "Ingreso de Dinero" en el Dashboard. 
- Las **Ventas** (`sales`) representan obligaciones emitidas, estén pagadas o no.
- Los **Ingresos** reales se obtienen de `treasury.total_in`.
- Las **Compras** (`purchases`) representan obligaciones adquiridas, estén pagadas o no.
- Los **Egresos** reales se obtienen de `treasury.total_out`.

Esta distinción visual es crucial para la inteligencia financiera que se le enseñará al usuario.
