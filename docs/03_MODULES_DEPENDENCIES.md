# 03 — Módulos financieros y dependencias

## Principio

La empresa activa solo lo que necesita. Desactivar un módulo oculta su operación nueva, pero **no borra su historial**.

## Núcleo

Siempre activo:

- empresa;
- usuarios y membresías;
- roles/permisos;
- moneda;
- configuración;
- auditoría;
- registro de módulos.

## Sales / Facturación

Funciones MVP:

- clientes;
- productos o servicios vendibles;
- factura/comprobante interno;
- borrador, emitido, parcial, pagado, vencido, anulado;
- contado o crédito;
- cobros vinculados;
- exportación/impresión simple.

No se presenta como facturación fiscal oficial.

## Inventory

Funciones MVP:

- productos;
- existencias;
- entrada, salida y ajuste;
- stock mínimo;
- kardex simple;
- integración con ventas/compras si ambos módulos están activos.

Regla: un ajuste requiere motivo y usuario.

## Purchases

Funciones MVP:

- proveedores;
- compra;
- detalle;
- recepción simple;
- contado/crédito;
- conexión con inventario opcional.

## Receivables — CxC

Funciones MVP:

- saldo originado por venta a crédito;
- vencimiento;
- abonos;
- saldo pendiente;
- estado;
- antigüedad;
- indicador de cartera vencida.

Indicador clave para demo:

```text
ratio_cartera_vencida = saldo_vencido / saldo_total_cxc * 100
```

## Payables — CxP

Funciones MVP:

- obligación originada por compra a crédito;
- vencimiento;
- pagos parciales/completos;
- saldo;
- próximos vencimientos.

## Cash

Funciones MVP:

- una o más cuentas/cajas internas;
- entradas y salidas;
- referencia a cobro/pago cuando corresponda;
- saldo operativo;
- clasificación básica.

No es conciliación bancaria automática.

## Reporting

Indicadores iniciales:

1. ventas del periodo;
2. saldo CxC;
3. porcentaje CxC vencida;
4. saldo CxP;
5. efectivo/caja disponible;
6. productos debajo del mínimo;
7. entradas vs salidas del periodo.

## Reglas de diagnóstico MVP

Las reglas son configurables en código/BD, pero inicialmente pueden seedearse.

| Código | Condición | Severidad | Recomendación educativa |
|---|---|---:|---|
| AR_OVERDUE_HIGH | cartera vencida > 25% | Alta | Crédito y cobranza |
| CASH_COVERAGE_LOW | pagos próximos 7 días > caja disponible | Alta | Flujo de caja y planificación |
| STOCK_BELOW_MIN | productos bajo mínimo > 0 | Media | Control de inventario |
| COLLECTION_DELAY | días promedio de cobro por encima de umbral | Media | Políticas de crédito y seguimiento |

La IA **no decide si la condición existe**. Recibe el resultado de la regla y lo explica.

## Activación de módulos

Durante onboarding:

```text
Servicios básicos -> Sales + CxC + Cash
Comercio -> Sales + Inventory + Purchases + CxC + CxP + Cash
Personalizado -> el usuario elige
```

Los presets son sugerencias, nunca una limitación rígida.
